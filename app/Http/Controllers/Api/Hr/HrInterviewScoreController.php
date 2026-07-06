<?php

namespace App\Http\Controllers\Api\Hr;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Hr\SimpleModelResource;
use App\Models\Hr\HrInterviewScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HrInterviewScoreController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return HrInterviewScore::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['interviewer_notes'];
    }

    protected function filterableColumns(): array
    {
        return ['candidate_id', 'recommended_to_proceed'];
    }

    protected function withRelations(): array
    {
        return ['candidate'];
    }

    protected function reportDateColumn(): string
    {
        return 'interview_date';
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
            'candidate_id' => ['required', 'exists:recruitment_candidates,id'],
            'interview_date' => ['required', 'date'],
            'technical_competence_score' => ['required', 'numeric', 'min:0', 'max:10'],
            'values_alignment_score' => ['required', 'numeric', 'min:0', 'max:10'],
            'position_specific_score' => ['required', 'numeric', 'min:0', 'max:10'],
            'interviewer_notes' => ['nullable', 'string'],
            'recommended_to_proceed' => ['boolean'],
        ]);

        $record = HrInterviewScore::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, HrInterviewScore $hrInterviewScore)
    {
        Gate::authorize('hr.update');

        $data = $request->validate([
            'technical_competence_score' => ['required', 'numeric', 'min:0', 'max:10'],
            'values_alignment_score' => ['required', 'numeric', 'min:0', 'max:10'],
            'position_specific_score' => ['required', 'numeric', 'min:0', 'max:10'],
            'interviewer_notes' => ['nullable', 'string'],
            'recommended_to_proceed' => ['boolean'],
        ]);

        $hrInterviewScore->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($hrInterviewScore->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('hr.report');

        return $this->genericReport(
            $request,
            HrInterviewScore::query()->with($this->withRelations()),
            'Interview Scoring Matrix (HR-006)',
            [
                'Candidate' => 'candidate.full_name',
                'Interview Date' => 'interview_date',
                'Technical' => 'technical_competence_score',
                'Values' => 'values_alignment_score',
                'Position-Specific' => 'position_specific_score',
                'Recommended' => 'recommended_to_proceed',
            ],
            'hr006-interview-scores'
        );
    }
}
