<?php

namespace App\Http\Controllers\Api\Financial;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Financial\SimpleModelResource;
use App\Models\Financial\AssetRegisterEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AssetRegisterController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return AssetRegisterEntry::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['asset_tag', 'description', 'custodian_name'];
    }

    protected function filterableColumns(): array
    {
        return ['category_id', 'status_id'];
    }

    protected function withRelations(): array
    {
        return ['category', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'acquisition_date';
    }

    protected function deletePermission(): ?string
    {
        return 'financial_procurement_asset.delete';
    }

    public function index(Request $request)
    {
        Gate::authorize('financial_procurement_asset.view');

        return parent::index($request);
    }

    public function store(Request $request)
    {
        Gate::authorize('financial_procurement_asset.create');

        $data = $request->validate([
            'asset_tag' => ['required', 'string', 'max:100', 'unique:asset_register,asset_tag'],
            'description' => ['required', 'string', 'max:500'],
            'category_id' => ['required', 'exists:lu_asset_categories,id'],
            'acquisition_date' => ['required', 'date'],
            'acquisition_cost_rwf' => ['required', 'integer', 'min:0'],
            'custodian_name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = AssetRegisterEntry::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, AssetRegisterEntry $assetRegisterEntry)
    {
        Gate::authorize('financial_procurement_asset.update');

        $data = $request->validate([
            'custodian_name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'last_verified_on' => ['nullable', 'date'],
            'disposal_approved' => ['boolean'],
            'disposed_on' => ['nullable', 'date'],
            'disposal_proceeds_rwf' => ['nullable', 'integer', 'min:0'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        // Art. 102 of the Constitution — significant disposals need GA, not just Executive Organ sign-off;
        // this flags the case rather than silently accepting it (final GA approval workflow lives in Bundle 8's commissions/GA tooling).
        if (! empty($data['disposed_on']) && $assetRegisterEntry->acquisition_cost_rwf >= 1_000_000 && ! ($data['disposal_approved'] ?? false)) {
            return response()->json([
                'message' => 'Assets acquired at or above RWF 1,000,000 require documented Executive Organ (or GA, if significant) approval before disposal (Art. 102).',
            ], 422);
        }

        $assetRegisterEntry->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($assetRegisterEntry->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('financial_procurement_asset.report');

        return $this->genericReport(
            $request,
            AssetRegisterEntry::query()->with($this->withRelations()),
            'Asset Register (ASSET-001)',
            [
                'Tag' => 'asset_tag',
                'Description' => 'description',
                'Category' => 'category.label_en',
                'Custodian' => 'custodian_name',
                'Acquisition Cost (RWF)' => 'acquisition_cost_rwf',
                'Status' => 'status.label_en',
            ],
            'asset001-register'
        );
    }
}
