<?php

namespace App\Http\Controllers\Api\Financial;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Financial\SimpleModelResource;
use App\Models\Financial\AssetHandover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AssetHandoverController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return AssetHandover::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['outgoing_custodian_name', 'incoming_custodian_name'];
    }

    protected function filterableColumns(): array
    {
        return ['asset_id', 'status_id'];
    }

    protected function withRelations(): array
    {
        return ['asset', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'handover_date';
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
            'asset_id' => ['required', 'exists:asset_register,id'],
            'outgoing_custodian_name' => ['required', 'string', 'max:255'],
            'incoming_custodian_name' => ['required', 'string', 'max:255'],
            'handover_date' => ['required', 'date'],
            'condition_notes' => ['nullable', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = AssetHandover::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, AssetHandover $assetHandover)
    {
        Gate::authorize('financial_procurement_asset.update');

        $data = $request->validate([
            'condition_notes' => ['nullable', 'string'],
            'outgoing_signed' => ['boolean'],
            'incoming_signed' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $assetHandover->update([...$data, 'updated_by' => Auth::id()]);

        // Once both parties have signed, sync the asset register's custodian of record.
        if (($data['outgoing_signed'] ?? $assetHandover->outgoing_signed)
            && ($data['incoming_signed'] ?? $assetHandover->incoming_signed)) {
            $assetHandover->asset->update(['custodian_name' => $assetHandover->incoming_custodian_name]);
        }

        return new SimpleModelResource($assetHandover->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('financial_procurement_asset.report');

        return $this->genericReport(
            $request,
            AssetHandover::query()->with($this->withRelations()),
            'Asset Handover Reports (ASSET-003)',
            [
                'Asset' => 'asset.description',
                'Outgoing' => 'outgoing_custodian_name',
                'Incoming' => 'incoming_custodian_name',
                'Handover Date' => 'handover_date',
                'Status' => 'status.label_en',
            ],
            'asset003-handovers'
        );
    }
}
