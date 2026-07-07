<?php

namespace App\Http\Controllers\Api\Operations;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Operations\SimpleModelResource;
use App\Models\Operations\CommissionKpiReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommissionKpiReportController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return CommissionKpiReport::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['kpis_established'];
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
        return 'established_on';
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
            'kpis_established' => ['required', 'string'],
            'established_on' => ['required', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = CommissionKpiReport::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, CommissionKpiReport $commissionKpiReport)
    {
        Gate::authorize('operations_security_commissions.update');

        $data = $request->validate([
            'mid_year_review_notes' => ['nullable', 'string'],
            'mid_year_reviewed_on' => ['nullable', 'date'],
            'year_end_review_notes' => ['nullable', 'string'],
            'year_end_reviewed_on' => ['nullable', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $commissionKpiReport->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($commissionKpiReport->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('operations_security_commissions.report');

        return $this->genericReport(
            $request,
            CommissionKpiReport::query()->with($this->withRelations()),
            'Commission KPI Reports (COMM-002)',
            [
                'Pillar' => 'pillar.label_en',
                'Year' => 'plan_year',
                'Established On' => 'established_on',
                'Mid-Year Reviewed' => 'mid_year_reviewed_on',
                'Year-End Reviewed' => 'year_end_reviewed_on',
            ],
            'comm002-kpi-reports'
        );
    }
}
