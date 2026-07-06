<?php

namespace App\Http\Controllers\Api\Hr;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Hr\SimpleModelResource;
use App\Models\Hr\HrEmploymentContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HrEmploymentContractController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return HrEmploymentContract::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['employee_full_name', 'reporting_line'];
    }

    protected function filterableColumns(): array
    {
        return ['position_id', 'employment_type_id', 'status_id'];
    }

    protected function withRelations(): array
    {
        return ['position', 'employmentType', 'status', 'candidate'];
    }

    protected function reportDateColumn(): string
    {
        return 'term_start';
    }

    protected function deletePermission(): ?string
    {
        return 'hr.delete';
    }

    public function index(Request $request)
    {
        Gate::authorize('hr.view');

        return parent::index($request);
    }

    public function store(Request $request)
    {
        Gate::authorize('hr.create');

        $data = $request->validate([
            'candidate_id' => ['nullable', 'exists:recruitment_candidates,id'],
            'employee_full_name' => ['required', 'string', 'max:255'],
            'position_id' => ['required', 'exists:lu_hq_positions,id'],
            'employment_type_id' => ['required', 'exists:lu_employment_types,id'],
            'duties_and_kpis' => ['required', 'string'],
            'qualifications_required' => ['nullable', 'string'],
            'reporting_line' => ['nullable', 'string', 'max:255'],
            'remuneration_rwf_monthly' => ['required', 'integer', 'min:0'],
            'working_hours' => ['nullable', 'string', 'max:255'],
            'term_start' => ['required', 'date'],
            'term_end' => ['nullable', 'date', 'after:term_start'],
            'termination_grounds' => ['nullable', 'string'],
            'confidentiality_acknowledged' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = HrEmploymentContract::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, HrEmploymentContract $hrEmploymentContract)
    {
        Gate::authorize('hr.update');

        $data = $request->validate([
            'duties_and_kpis' => ['required', 'string'],
            'reporting_line' => ['nullable', 'string', 'max:255'],
            'remuneration_rwf_monthly' => ['required', 'integer', 'min:0'],
            'term_end' => ['nullable', 'date'],
            'termination_grounds' => ['nullable', 'string'],
            'confidentiality_acknowledged' => ['boolean'],
            'ceo_signed_on' => ['nullable', 'date'],
            'appointee_signed_on' => ['nullable', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $hrEmploymentContract->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($hrEmploymentContract->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('hr.report');

        return $this->genericReport(
            $request,
            HrEmploymentContract::query()->with($this->withRelations()),
            'Employment Contracts (HR-001)',
            [
                'Employee' => 'employee_full_name',
                'Position' => 'position.label_en',
                'Type' => 'employmentType.label_en',
                'Term Start' => 'term_start',
                'Status' => 'status.label_en',
            ],
            'hr001-employment-contracts'
        );
    }
}
