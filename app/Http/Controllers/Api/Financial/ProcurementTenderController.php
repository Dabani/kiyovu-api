<?php

namespace App\Http\Controllers\Api\Financial;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Financial\SimpleModelResource;
use App\Models\Financial\ProcurementTender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProcurementTenderController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return ProcurementTender::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['item_description', 'awarded_vendor_name'];
    }

    protected function filterableColumns(): array
    {
        return ['status_id'];
    }

    protected function withRelations(): array
    {
        return ['status'];
    }

    protected function reportDateColumn(): string
    {
        return 'tender_published_on';
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
            'item_description' => ['required', 'string', 'max:500'],
            'estimated_value_rwf' => ['required', 'integer', 'min:5000001'], // Art. 898 large-contract floor
            'tender_published_on' => ['required', 'date'],
            'tender_closing_date' => ['required', 'date', 'after:tender_published_on'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = ProcurementTender::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, ProcurementTender $procurementTender)
    {
        Gate::authorize('financial_procurement_asset.update');

        $data = $request->validate([
            'evaluation_committee_names' => ['nullable', 'string'],
            'awarded_vendor_name' => ['nullable', 'string', 'max:255'],
            'award_date' => ['nullable', 'date'],
            'awarded_value_rwf' => ['nullable', 'integer', 'min:0'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $procurementTender->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($procurementTender->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('financial_procurement_asset.report');

        return $this->genericReport(
            $request,
            ProcurementTender::query()->with($this->withRelations()),
            'Competitive Tenders (PROC-003)',
            [
                'Item' => 'item_description',
                'Estimated Value (RWF)' => 'estimated_value_rwf',
                'Published On' => 'tender_published_on',
                'Awarded Vendor' => 'awarded_vendor_name',
                'Status' => 'status.label_en',
            ],
            'proc003-tenders'
        );
    }
}
