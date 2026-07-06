<?php

namespace App\Http\Controllers\Api\Financial;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Financial\SimpleModelResource;
use App\Models\Financial\PettyCashVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PettyCashVoucherController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return PettyCashVoucher::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['description', 'department', 'requested_by_name'];
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
        return 'voucher_date';
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
            'description' => ['required', 'string', 'max:500'],
            'amount_rwf' => ['required', 'integer', 'min:1', 'max:50000'], // Art. 888 petty cash ceiling
            'department' => ['required', 'string', 'max:255'],
            'requested_by_name' => ['required', 'string', 'max:255'],
            'departmental_head_name' => ['required', 'string', 'max:255'],
            'voucher_date' => ['required', 'date'],
            'receipt_attached' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = PettyCashVoucher::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, PettyCashVoucher $pettyCashVoucher)
    {
        Gate::authorize('financial_procurement_asset.update');

        $data = $request->validate([
            'receipt_attached' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $pettyCashVoucher->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($pettyCashVoucher->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('financial_procurement_asset.report');

        return $this->genericReport(
            $request,
            PettyCashVoucher::query()->with($this->withRelations()),
            'Petty Cash Vouchers (FIN-003)',
            [
                'Description' => 'description',
                'Amount (RWF)' => 'amount_rwf',
                'Department' => 'department',
                'Voucher Date' => 'voucher_date',
                'Status' => 'status.label_en',
            ],
            'fin003-petty-cash-vouchers'
        );
    }
}
