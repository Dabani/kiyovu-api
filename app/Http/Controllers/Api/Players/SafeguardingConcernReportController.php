<?php

namespace App\Http\Controllers\Api\Players;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Players\SimpleModelResource;
use App\Models\Players\SafeguardingConcernReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SafeguardingConcernReportController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return SafeguardingConcernReport::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['description'];
    }

    protected function filterableColumns(): array
    {
        return ['status_id', 'is_anonymous', 'risk_identified'];
    }

    protected function withRelations(): array
    {
        return ['status'];
    }

    protected function reportDateColumn(): string
    {
        return 'concern_date';
    }

    protected function deletePermission(): ?string
    {
        return 'players_safeguarding.delete';
    }

    public function index(Request $request)
    {
        Gate::authorize('players_safeguarding.view');

        return parent::index($request);
    }

    public function store(Request $request)
    {
        Gate::authorize('players_safeguarding.create');

        $data = $request->validate([
            'is_anonymous' => ['boolean'],
            'reporter_name' => ['nullable', 'string', 'max:255'],
            'concern_date' => ['required', 'date'],
            'description' => ['required', 'string'],
            'subject_reference' => ['nullable', 'string', 'max:255'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        // Anonymity protection — same rule as Bundle 4's whistleblower reports (Art. 34).
        if ($data['is_anonymous'] ?? false) {
            $data['reporter_name'] = null;
        }

        $record = SafeguardingConcernReport::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, SafeguardingConcernReport $safeguardingConcernReport)
    {
        Gate::authorize('players_safeguarding.update');

        $data = $request->validate([
            'receipt_acknowledged_on' => ['nullable', 'date'],
            'initial_assessment_completed_on' => ['nullable', 'date'],
            'risk_identified' => ['nullable', 'boolean'],
            'reported_to_authorities_on' => ['nullable', 'date'],
            'accused_suspended_from_minors_contact' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $safeguardingConcernReport->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($safeguardingConcernReport->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('players_safeguarding.report');

        return $this->genericReport(
            $request,
            SafeguardingConcernReport::query()->with($this->withRelations()),
            'Safeguarding Concern Reports (SAFE-001)',
            [
                'Concern Date' => 'concern_date',
                'Risk Identified' => 'risk_identified',
                'Reported to Authorities' => 'reported_to_authorities_on',
                'Status' => 'status.label_en',
            ],
            'safe001-concern-reports'
        );
    }
}
