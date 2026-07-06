<?php

namespace App\Http\Controllers\Api\Hr;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Hr\SimpleModelResource;
use App\Models\Hr\RecruitmentCandidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RecruitmentCandidateController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return RecruitmentCandidate::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['full_name', 'vacancy_title', 'phone', 'email'];
    }

    protected function filterableColumns(): array
    {
        return ['position_id', 'status_id', 'shortlisted'];
    }

    protected function withRelations(): array
    {
        return ['position', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'application_date';
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
            'vacancy_title' => ['required', 'string', 'max:255'],
            'position_id' => ['required', 'exists:lu_hq_positions,id'],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'application_date' => ['required', 'date'],
            'vacancy_published_on' => ['nullable', 'date'],
            'vacancy_closing_date' => ['nullable', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = RecruitmentCandidate::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, RecruitmentCandidate $recruitmentCandidate)
    {
        Gate::authorize('hr.update');

        $data = $request->validate([
            'status_id' => ['required', 'exists:lu_statuses,id'],
            'shortlisted' => ['boolean'],
            'shortlist_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'shortlisting_notes' => ['nullable', 'string'],
            'shortlisted_on' => ['nullable', 'date'],
        ]);

        $recruitmentCandidate->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($recruitmentCandidate->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('hr.report');

        return $this->genericReport(
            $request,
            RecruitmentCandidate::query()->with($this->withRelations()),
            'Recruitment Shortlisting Records (HR-005)',
            [
                'Candidate' => 'full_name',
                'Vacancy' => 'vacancy_title',
                'Position' => 'position.label_en',
                'Shortlisted' => 'shortlisted',
                'Score' => 'shortlist_score',
                'Status' => 'status.label_en',
            ],
            'hr005-shortlisting-records'
        );
    }
}
