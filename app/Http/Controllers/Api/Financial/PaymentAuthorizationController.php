<?php

namespace App\Http\Controllers\Api\Financial;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Financial\SimpleModelResource;
use App\Models\Financial\PaymentAuthorization;
use App\Models\Lookups\LuExpenditureTier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PaymentAuthorizationController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return PaymentAuthorization::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['description', 'payee_name'];
    }

    protected function filterableColumns(): array
    {
        return ['expenditure_tier_id', 'status_id'];
    }

    protected function withRelations(): array
    {
        return ['expenditureTier', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'payment_date';
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
            'amount_rwf' => ['required', 'integer', 'min:50001'], // routine tier floor — FIN-003 covers petty cash below this
            'payee_name' => ['required', 'string', 'max:255'],
            'payment_date' => ['required', 'date'],
            'authorized_by_ceo_name' => ['nullable', 'string', 'max:255'],
            'co_signed_by_treasurer_name' => ['nullable', 'string', 'max:255'],
            'executive_organ_resolution' => ['boolean'],
            'ga_resolution' => ['boolean'],
            'supporting_documentation_ref' => ['nullable', 'string', 'max:255'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        // Art. 888 — the tier (and therefore required authorisers) is derived
        // from the amount, never left to the submitter to self-select.
        $tier = LuExpenditureTier::where('min_amount_rwf', '<=', $data['amount_rwf'])
            ->where(function ($q) use ($data) {
                $q->whereNull('max_amount_rwf')->orWhere('max_amount_rwf', '>=', $data['amount_rwf']);
            })
            ->orderByDesc('min_amount_rwf')
            ->first();

        if (! $tier) {
            return response()->json(['message' => 'No expenditure tier configured for this amount.'], 422);
        }

        if (in_array($tier->code, ['significant', 'major', 'capital'], true) && empty($data['co_signed_by_treasurer_name'])) {
            return response()->json(['message' => "Amounts in the '{$tier->label_en}' tier require Treasurer co-signature (Art. 888)."], 422);
        }

        $record = PaymentAuthorization::create([
            ...$data, 'expenditure_tier_id' => $tier->id,
            'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, PaymentAuthorization $paymentAuthorization)
    {
        Gate::authorize('financial_procurement_asset.update');

        $data = $request->validate([
            'authorized_by_ceo_name' => ['nullable', 'string', 'max:255'],
            'co_signed_by_treasurer_name' => ['nullable', 'string', 'max:255'],
            'executive_organ_resolution' => ['boolean'],
            'ga_resolution' => ['boolean'],
            'supporting_documentation_ref' => ['nullable', 'string', 'max:255'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $paymentAuthorization->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($paymentAuthorization->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('financial_procurement_asset.report');

        return $this->genericReport(
            $request,
            PaymentAuthorization::query()->with($this->withRelations()),
            'Payment Authorizations (FIN-001)',
            [
                'Description' => 'description',
                'Amount (RWF)' => 'amount_rwf',
                'Tier' => 'expenditureTier.label_en',
                'Payee' => 'payee_name',
                'Payment Date' => 'payment_date',
                'Status' => 'status.label_en',
            ],
            'fin001-payment-authorizations'
        );
    }
}
