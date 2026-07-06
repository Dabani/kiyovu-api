<?php

namespace App\Http\Controllers\Api\Hr;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Hr\SimpleModelResource;
use App\Models\Hr\HrAppointmentRecommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HrAppointmentRecommendationController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return HrAppointmentRecommendation::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['vacancy_title', 'ranking_notes'];
    }

    protected function filterableColumns(): array
    {
        return ['position_id', 'status_id', 'board_approval_required'];
    }

    protected function withRelations(): array
    {
        return ['position', 'status', 'recommendedCandidate'];
    }

    protected function reportDateColumn(): string
    {
        return 'submitted_on';
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
            'recommended_candidate_id' => ['required', 'exists:recruitment_candidates,id'],
            'ranking_notes' => ['required', 'string'],
            'submitted_on' => ['required', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
            'board_approval_required' => ['boolean'],
        ]);

        $record = HrAppointmentRecommendation::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, HrAppointmentRecommendation $hrAppointmentRecommendation)
    {
        Gate::authorize('hr.update');

        $data = $request->validate([
            'ranking_notes' => ['required', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
            'executive_organ_decision_date' => ['nullable', 'date'],
            'board_approval_required' => ['boolean'],
            'board_approved' => ['boolean'],
        ]);

        $hrAppointmentRecommendation->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($hrAppointmentRecommendation->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('hr.report');

        return $this->genericReport(
            $request,
            HrAppointmentRecommendation::query()->with($this->withRelations()),
            'Appointment Recommendations (HR-007)',
            [
                'Vacancy' => 'vacancy_title',
                'Position' => 'position.label_en',
                'Recommended Candidate' => 'recommendedCandidate.full_name',
                'Submitted On' => 'submitted_on',
                'Status' => 'status.label_en',
            ],
            'hr007-appointment-recommendations'
        );
    }
}
