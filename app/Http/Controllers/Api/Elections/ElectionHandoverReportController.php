<?php

namespace App\Http\Controllers\Api\Elections;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Elections\SimpleModelResource;
use App\Models\Elections\ElectionHandoverReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ElectionHandoverReportController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return ElectionHandoverReport::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['outgoing_official_name', 'incoming_official_name'];
    }

    protected function filterableColumns(): array
    {
        return ['position_id', 'status_id'];
    }

    protected function withRelations(): array
    {
        return ['position', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'handover_date';
    }

    protected function deletePermission(): ?string
    {
        return 'elections.delete';
    }

    public function index(Request $request)
    {
        Gate::authorize('elections.view');

        return parent::index($request);
    }

    public function store(Request $request)
    {
        Gate::authorize('elections.create');

        $data = $request->validate([
            'position_id' => ['required', 'exists:lu_elected_positions,id'],
            'outgoing_official_name' => ['required', 'string', 'max:255'],
            'incoming_official_name' => ['required', 'string', 'max:255'],
            'handover_date' => ['required', 'date'],
            'outstanding_matters' => ['nullable', 'string'],
            'key_contacts' => ['nullable', 'string'],
            'pending_decisions' => ['nullable', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = ElectionHandoverReport::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, ElectionHandoverReport $electionHandoverReport)
    {
        Gate::authorize('elections.update');

        $data = $request->validate([
            'outstanding_matters' => ['nullable', 'string'],
            'key_contacts' => ['nullable', 'string'],
            'pending_decisions' => ['nullable', 'string'],
            'access_and_assets_transferred' => ['boolean'],
            'outgoing_signed' => ['boolean'],
            'incoming_signed' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $electionHandoverReport->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($electionHandoverReport->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('elections.report');

        return $this->genericReport(
            $request,
            ElectionHandoverReport::query()->with($this->withRelations()),
            'Election Handover Reports (ELEC-004)',
            [
                'Position' => 'position.label_en',
                'Outgoing' => 'outgoing_official_name',
                'Incoming' => 'incoming_official_name',
                'Handover Date' => 'handover_date',
                'Status' => 'status.label_en',
            ],
            'elec004-handover-reports'
        );
    }
}
