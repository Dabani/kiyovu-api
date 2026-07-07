<?php

namespace App\Http\Controllers\Api\Operations;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Operations\SimpleModelResource;
use App\Models\Operations\SecurityIncidentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SecurityIncidentReportController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return SecurityIncidentReport::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['incident_description', 'event_description', 'reported_by_name'];
    }

    protected function filterableColumns(): array
    {
        return ['status_id'];
    }

    protected function withRelations(): array
    {
        return ['status'];
    }

    protected function reportDateColumn(): string
    {
        return 'incident_date';
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
            'incident_date' => ['required', 'date'],
            'event_description' => ['required', 'string', 'max:255'],
            'incident_description' => ['required', 'string'],
            'reported_by_name' => ['required', 'string', 'max:255'],
            'reported_on' => ['required', 'date'], // within 24 hours, Art. 129
            'coordinated_with_law_enforcement' => ['boolean'],
            'coordinated_with_stadium_authorities' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = SecurityIncidentReport::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, SecurityIncidentReport $securityIncidentReport)
    {
        Gate::authorize('operations_security_commissions.update');

        $data = $request->validate([
            'action_taken' => ['nullable', 'string'],
            'coordinated_with_law_enforcement' => ['boolean'],
            'coordinated_with_stadium_authorities' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $securityIncidentReport->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($securityIncidentReport->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('operations_security_commissions.report');

        return $this->genericReport(
            $request,
            SecurityIncidentReport::query()->with($this->withRelations()),
            'Security Incident Register (SEC-001)',
            [
                'Incident Date' => 'incident_date',
                'Event' => 'event_description',
                'Reported By' => 'reported_by_name',
                'Status' => 'status.label_en',
            ],
            'sec001-incident-register'
        );
    }
}
