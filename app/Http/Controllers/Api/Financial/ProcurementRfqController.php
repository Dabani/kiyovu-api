<?php

namespace App\Http\Controllers\Api\Financial;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Financial\SimpleModelResource;
use App\Models\Financial\ProcurementRfq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProcurementRfqController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return ProcurementRfq::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['item_description', 'selected_vendor_name'];
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
        return 'rfq_date';
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
            'estimated_value_rwf' => ['required', 'integer', 'min:50001', 'max:5000000'], // Art. 898 medium tier
            'rfq_date' => ['required', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = ProcurementRfq::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, ProcurementRfq $procurementRfq)
    {
        Gate::authorize('financial_procurement_asset.update');

        $data = $request->validate([
            'quotations_received' => ['integer', 'min:0'],
            'evaluation_notes' => ['nullable', 'string'],
            'selected_vendor_name' => ['nullable', 'string', 'max:255'],
            'award_date' => ['nullable', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        // Art. 898 — at least 3 written quotations required before award.
        if (! empty($data['selected_vendor_name']) && ($data['quotations_received'] ?? $procurementRfq->quotations_received) < 3) {
            return response()->json(['message' => 'At least 3 quotations must be on file before a vendor can be selected (Art. 898).'], 422);
        }

        $procurementRfq->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($procurementRfq->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('financial_procurement_asset.report');

        return $this->genericReport(
            $request,
            ProcurementRfq::query()->with($this->withRelations()),
            'Requests for Quotations (PROC-002)',
            [
                'Item' => 'item_description',
                'Estimated Value (RWF)' => 'estimated_value_rwf',
                'Quotations Received' => 'quotations_received',
                'Selected Vendor' => 'selected_vendor_name',
                'Status' => 'status.label_en',
            ],
            'proc002-rfqs'
        );
    }
}
