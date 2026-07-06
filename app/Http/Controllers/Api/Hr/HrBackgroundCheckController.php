<?php

namespace App\Http\Controllers\Api\Hr;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Hr\SimpleModelResource;
use App\Models\Hr\HrBackgroundCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HrBackgroundCheckController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return HrBackgroundCheck::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['subject_name', 'verification_notes'];
    }

    protected function filterableColumns(): array
    {
        return ['position_id', 'outcome_status_id', 'role_involves_minors'];
    }

    protected function withRelations(): array
    {
        return ['position', 'outcomeStatus', 'candidate'];
    }

    protected function reportDateColumn(): string
    {
        return 'consent_given_on';
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
            'subject_name' => ['required', 'string', 'max:255'],
            'position_id' => ['nullable', 'exists:lu_hq_positions,id'],
            'role_involves_minors' => ['boolean'],
            'consent_given_on' => ['required', 'date'],
            'verification_notes' => ['nullable', 'string'],
            'outcome_status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = HrBackgroundCheck::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, HrBackgroundCheck $hrBackgroundCheck)
    {
        Gate::authorize('hr.update');

        $data = $request->validate([
            'verification_notes' => ['nullable', 'string'],
            'outcome_status_id' => ['required', 'exists:lu_statuses,id'],
            'cleared_by_name' => ['nullable', 'string', 'max:255'],
            'cleared_on' => ['nullable', 'date'],
        ]);

        $hrBackgroundCheck->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($hrBackgroundCheck->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('hr.report');

        return $this->genericReport(
            $request,
            HrBackgroundCheck::query()->with($this->withRelations()),
            'Background Check Consents (HR-002)',
            [
                'Subject' => 'subject_name',
                'Position' => 'position.label_en',
                'Involves Minors' => 'role_involves_minors',
                'Outcome' => 'outcomeStatus.label_en',
                'Cleared On' => 'cleared_on',
            ],
            'hr002-background-checks'
        );
    }
}
