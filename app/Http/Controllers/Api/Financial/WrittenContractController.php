<?php

namespace App\Http\Controllers\Api\Financial;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Financial\SimpleModelResource;
use App\Models\Financial\WrittenContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class WrittenContractController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return WrittenContract::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['counterparty_name', 'description'];
    }

    protected function filterableColumns(): array
    {
        return ['contract_type_id', 'status_id'];
    }

    protected function withRelations(): array
    {
        return ['contractType', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'start_date';
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
            'contract_type_id' => ['required', 'exists:lu_contract_types,id'],
            'counterparty_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'value_rwf' => ['nullable', 'integer', 'min:0'],
            'monthly_value_rwf' => ['nullable', 'integer', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        // Art. 1013 — partnership notification/approval thresholds, computed not self-declared.
        $monthly = $data['monthly_value_rwf'] ?? 0;
        $annualEquivalent = $data['value_rwf'] ?? ($monthly * 12);
        $data['ga_notified'] = $monthly > 10_000_000 || $annualEquivalent > 120_000_000;
        $data['ga_approval_required'] = $monthly >= 50_000_000;

        $record = WrittenContract::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, WrittenContract $writtenContract)
    {
        Gate::authorize('financial_procurement_asset.update');

        $data = $request->validate([
            'executive_organ_approved' => ['boolean'],
            'ga_approved' => ['boolean'],
            'signed_on' => ['nullable', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        if ($writtenContract->ga_approval_required && ($data['status_id'] ?? null)
            && \App\Models\Lookups\LuStatus::find($data['status_id'])?->code === 'approved'
            && ! ($data['ga_approved'] ?? $writtenContract->ga_approved)) {
            return response()->json([
                'message' => 'This partnership requires prior General Assembly approval before it can be marked approved (Art. 1013, Art. 34(8) of the Constitution).',
            ], 422);
        }

        $writtenContract->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($writtenContract->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('financial_procurement_asset.report');

        return $this->genericReport(
            $request,
            WrittenContract::query()->with($this->withRelations()),
            'Written Contracts (PROC-004)',
            [
                'Counterparty' => 'counterparty_name',
                'Type' => 'contractType.label_en',
                'Start Date' => 'start_date',
                'GA Approval Required' => 'ga_approval_required',
                'Status' => 'status.label_en',
            ],
            'proc004-written-contracts'
        );
    }
}
