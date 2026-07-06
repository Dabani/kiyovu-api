<?php

namespace App\Http\Controllers\Api\Disciplinary;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Disciplinary\SimpleModelResource;
use App\Models\Disciplinary\DisciplinaryCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DisciplinaryCaseController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return DisciplinaryCase::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['respondent_name', 'complainant_name', 'incident_description'];
    }

    protected function filterableColumns(): array
    {
        return ['case_source_id', 'status_id'];
    }

    protected function withRelations(): array
    {
        return ['caseSource', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'initiated_on';
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
            'case_source_id' => ['required', 'exists:lu_disciplinary_case_sources,id'],
            'respondent_name' => ['required', 'string', 'max:255'],
            'complainant_name' => ['nullable', 'string', 'max:255'],
            'incident_description' => ['required', 'string'],
            'initiated_on' => ['required', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = DisciplinaryCase::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, DisciplinaryCase $disciplinaryCase)
    {
        Gate::authorize('disciplinary_legal.update');

        $data = $request->validate([
            'receipt_acknowledged_on' => ['nullable', 'date'],
            'preliminary_review_completed_on' => ['nullable', 'date'],
            'jurisdiction_confirmed' => ['nullable', 'boolean'],
            'prima_facie_case' => ['nullable', 'boolean'],
            'investigation_completed_on' => ['nullable', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $disciplinaryCase->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($disciplinaryCase->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('disciplinary_legal.report');

        return $this->genericReport(
            $request,
            DisciplinaryCase::query()->with($this->withRelations()),
            'Disciplinary Cases (DISC-001)',
            [
                'Respondent' => 'respondent_name',
                'Source' => 'caseSource.label_en',
                'Initiated On' => 'initiated_on',
                'Status' => 'status.label_en',
            ],
            'disc001-disciplinary-cases'
        );
    }
}
