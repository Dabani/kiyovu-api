<?php

namespace App\Http\Controllers\Api\Disciplinary;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Disciplinary\SimpleModelResource;
use App\Models\Disciplinary\DisciplinaryDecision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DisciplinaryDecisionController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return DisciplinaryDecision::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['case_summary', 'findings_of_fact', 'rules_violated'];
    }

    protected function filterableColumns(): array
    {
        return ['case_id', 'sanction_id', 'status_id'];
    }

    protected function withRelations(): array
    {
        return ['case', 'sanction', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'decision_date';
    }

    protected function deletePermission(): ?string
    {
        return 'disciplinary_legal.delete';
    }

    public function index(Request $request)
    {
        Gate::authorize('disciplinary_legal.view');

        return parent::index($request);
    }

    public function store(Request $request)
    {
        Gate::authorize('disciplinary_legal.create');

        $data = $request->validate([
            'case_id' => ['required', 'exists:disciplinary_cases,id'],
            'decision_date' => ['required', 'date'],
            'case_summary' => ['required', 'string'],
            'findings_of_fact' => ['required', 'string'],
            'rules_violated' => ['required', 'string'],
            'reasoning' => ['required', 'string'],
            'sanction_id' => ['nullable', 'exists:lu_disciplinary_sanctions,id'],
            'sanction_effective_date' => ['nullable', 'date'],
            'appeal_deadline' => ['nullable', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = DisciplinaryDecision::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, DisciplinaryDecision $disciplinaryDecision)
    {
        Gate::authorize('disciplinary_legal.update');

        $data = $request->validate([
            'communicated_to_respondent' => ['boolean'],
            'communicated_to_executive_organ' => ['boolean'],
            'recorded_by_secretary_general' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $disciplinaryDecision->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($disciplinaryDecision->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('disciplinary_legal.report');

        return $this->genericReport(
            $request,
            DisciplinaryDecision::query()->with($this->withRelations()),
            'Disciplinary Decisions (DISC-002)',
            [
                'Case' => 'case.respondent_name',
                'Decision Date' => 'decision_date',
                'Sanction' => 'sanction.label_en',
                'Status' => 'status.label_en',
            ],
            'disc002-disciplinary-decisions'
        );
    }
}
