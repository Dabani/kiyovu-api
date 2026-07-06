<?php

namespace App\Http\Controllers\Api\Elections;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Elections\SimpleModelResource;
use App\Models\Elections\ElectionResultsCertification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ElectionResultsCertificationController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return ElectionResultsCertification::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['commission_member_1_name', 'commission_member_2_name', 'commission_member_3_name'];
    }

    protected function filterableColumns(): array
    {
        return ['position_id', 'status_id', 'election_cycle_year'];
    }

    protected function withRelations(): array
    {
        return ['position', 'status', 'winningNomination'];
    }

    protected function reportDateColumn(): string
    {
        return 'certified_on';
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
            'election_cycle_year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'position_id' => ['required', 'exists:lu_elected_positions,id'],
            'winning_nomination_id' => ['required', 'exists:election_nominations,id'],
            'was_tie_broken_by_lots' => ['boolean'],
            'certified_on' => ['required', 'date'],
            'commission_member_1_name' => ['required', 'string', 'max:255'],
            'commission_member_2_name' => ['required', 'string', 'max:255'],
            'commission_member_3_name' => ['required', 'string', 'max:255'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = ElectionResultsCertification::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, ElectionResultsCertification $electionResultsCertification)
    {
        Gate::authorize('elections.update');

        $data = $request->validate([
            'filed_with_secretary_general' => ['boolean'],
            'handover_date' => ['nullable', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $electionResultsCertification->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($electionResultsCertification->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('elections.report');

        return $this->genericReport(
            $request,
            ElectionResultsCertification::query()->with($this->withRelations()),
            'Election Results Certifications (ELEC-003)',
            [
                'Position' => 'position.label_en',
                'Winner' => 'winningNomination.candidate_full_name',
                'Cycle Year' => 'election_cycle_year',
                'Certified On' => 'certified_on',
                'Filed with SG' => 'filed_with_secretary_general',
            ],
            'elec003-results-certifications'
        );
    }
}
