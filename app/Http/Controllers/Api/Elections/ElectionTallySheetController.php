<?php

namespace App\Http\Controllers\Api\Elections;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Elections\SimpleModelResource;
use App\Models\Elections\ElectionTallySheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ElectionTallySheetController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return ElectionTallySheet::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['observer_names'];
    }

    protected function filterableColumns(): array
    {
        return ['nomination_id'];
    }

    protected function withRelations(): array
    {
        return ['nomination.position'];
    }

    protected function reportDateColumn(): string
    {
        return 'election_date';
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
            'nomination_id' => ['required', 'exists:election_nominations,id'],
            'election_date' => ['required', 'date'],
            'votes_received' => ['required', 'integer', 'min:0'],
            'invalid_ballots_count' => ['integer', 'min:0'],
            'independent_observer_present' => ['boolean'],
            'observer_names' => ['nullable', 'string'],
        ]);

        $record = ElectionTallySheet::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, ElectionTallySheet $electionTallySheet)
    {
        Gate::authorize('elections.update');

        $data = $request->validate([
            'votes_received' => ['required', 'integer', 'min:0'],
            'invalid_ballots_count' => ['integer', 'min:0'],
            'independent_observer_present' => ['boolean'],
            'observer_names' => ['nullable', 'string'],
        ]);

        $electionTallySheet->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($electionTallySheet->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('elections.report');

        return $this->genericReport(
            $request,
            ElectionTallySheet::query()->with($this->withRelations()),
            'Election Tally Sheets (ELEC-002)',
            [
                'Candidate' => 'nomination.candidate_full_name',
                'Position' => 'nomination.position.label_en',
                'Election Date' => 'election_date',
                'Votes Received' => 'votes_received',
                'Invalid Ballots' => 'invalid_ballots_count',
            ],
            'elec002-tally-sheets'
        );
    }
}
