<?php

namespace App\Http\Controllers\Api\Elections;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Elections\SimpleModelResource;
use App\Models\Elections\ElectionDispute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ElectionDisputeController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return ElectionDispute::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['submitted_by_name', 'grounds_detail'];
    }

    protected function filterableColumns(): array
    {
        return ['position_id', 'status_id', 'dispute_ground_id'];
    }

    protected function withRelations(): array
    {
        return ['position', 'status', 'disputeGround'];
    }

    protected function reportDateColumn(): string
    {
        return 'submitted_on';
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
            'election_cycle_year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'dispute_ground_id' => ['required', 'exists:lu_dispute_grounds,id'],
            'submitted_by_name' => ['required', 'string', 'max:255'],
            'submitted_on' => ['required', 'date'],
            'grounds_detail' => ['required', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = ElectionDispute::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, ElectionDispute $electionDispute)
    {
        Gate::authorize('elections.update');

        $data = $request->validate([
            'referred_to_cro' => ['boolean'],
            'determination' => ['nullable', 'string'],
            'determination_date' => ['nullable', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $electionDispute->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($electionDispute->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('elections.report');

        return $this->genericReport(
            $request,
            ElectionDispute::query()->with($this->withRelations()),
            'Election Disputes (ELEC-005)',
            [
                'Position' => 'position.label_en',
                'Ground' => 'disputeGround.label_en',
                'Submitted By' => 'submitted_by_name',
                'Submitted On' => 'submitted_on',
                'Status' => 'status.label_en',
            ],
            'elec005-disputes'
        );
    }
}
