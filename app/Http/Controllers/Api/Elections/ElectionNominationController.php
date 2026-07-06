<?php

namespace App\Http\Controllers\Api\Elections;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Elections\SimpleModelResource;
use App\Models\Elections\ElectionNomination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ElectionNominationController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return ElectionNomination::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['candidate_full_name', 'statement_of_intent'];
    }

    protected function filterableColumns(): array
    {
        return ['position_id', 'status_id', 'election_cycle_year'];
    }

    protected function withRelations(): array
    {
        return ['position', 'status', 'member'];
    }

    protected function reportDateColumn(): string
    {
        return 'nominated_on';
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
            'member_id' => ['nullable', 'exists:members,id'],
            'candidate_full_name' => ['required', 'string', 'max:255'],
            'statement_of_intent' => ['required', 'string', 'max:4000'],
            'eligibility_declaration_signed' => ['boolean'],
            'no_disqualifying_convictions_declared' => ['boolean'],
            'legal_representative_limit_confirmed' => ['boolean'],
            'criminal_record_certificate_date' => ['nullable', 'date'],
            'nominated_on' => ['required', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        // Word-count guard mirrors the 500-word cap on the statement of intent (Art. 68).
        if (str_word_count(strip_tags($data['statement_of_intent'])) > 500) {
            return response()->json(['message' => 'Statement of intent exceeds the 500-word limit (Art. 68).'], 422);
        }

        $record = ElectionNomination::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, ElectionNomination $electionNomination)
    {
        Gate::authorize('elections.update');

        $data = $request->validate([
            'statement_of_intent' => ['required', 'string', 'max:4000'],
            'eligibility_determined_on' => ['nullable', 'date'],
            'eligibility_approved' => ['nullable', 'boolean'],
            'eligibility_notes' => ['nullable', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $electionNomination->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($electionNomination->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('elections.report');

        return $this->genericReport(
            $request,
            ElectionNomination::query()->with($this->withRelations()),
            'Election Nominations (ELEC-001)',
            [
                'Candidate' => 'candidate_full_name',
                'Position' => 'position.label_en',
                'Cycle Year' => 'election_cycle_year',
                'Nominated On' => 'nominated_on',
                'Status' => 'status.label_en',
            ],
            'elec001-nominations'
        );
    }
}
