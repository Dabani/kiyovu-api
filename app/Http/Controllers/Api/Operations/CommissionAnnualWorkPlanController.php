<?php

namespace App\Http\Controllers\Api\Operations;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Operations\SimpleModelResource;
use App\Models\Operations\CommissionAnnualWorkPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommissionAnnualWorkPlanController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return CommissionAnnualWorkPlan::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['objectives'];
    }

    protected function filterableColumns(): array
    {
        return ['pillar_id', 'status_id', 'plan_year'];
    }

    protected function withRelations(): array
    {
        return ['pillar', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'submitted_on';
    }

    protected function deletePermission(): ?string
    {
        return 'operations_security_commissions.delete';
    }

    public function index(Request $request)
    {
        Gate::authorize('operations_security_commissions.view');

        return parent::index($request);
    }

    public function store(Request $request)
    {
        Gate::authorize('operations_security_commissions.create');

        $data = $request->validate([
            'pillar_id' => ['required', 'exists:lu_commission_pillars,id'],
            'plan_year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'objectives' => ['required', 'string'],
            'submitted_on' => ['required', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = CommissionAnnualWorkPlan::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, CommissionAnnualWorkPlan $commissionAnnualWorkPlan)
    {
        Gate::authorize('operations_security_commissions.update');

        $data = $request->validate([
            'objectives' => ['required', 'string'],
            'executive_organ_approved_on' => ['nullable', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $commissionAnnualWorkPlan->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($commissionAnnualWorkPlan->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('operations_security_commissions.report');

        return $this->genericReport(
            $request,
            CommissionAnnualWorkPlan::query()->with($this->withRelations()),
            'Commission Annual Work Plans (COMM-001)',
            [
                'Pillar' => 'pillar.label_en',
                'Year' => 'plan_year',
                'Submitted On' => 'submitted_on',
                'Executive Organ Approved' => 'executive_organ_approved_on',
                'Status' => 'status.label_en',
            ],
            'comm001-annual-work-plans'
        );
    }
}
