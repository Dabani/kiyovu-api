<?php

namespace App\Http\Controllers\Api\Players;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Players\SimpleModelResource;
use App\Models\Players\PlayerContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PlayerContractController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return PlayerContract::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['player_obligations', 'organisation_obligations'];
    }

    protected function filterableColumns(): array
    {
        return ['player_id', 'status_id'];
    }

    protected function withRelations(): array
    {
        return ['player', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'term_start';
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
            'player_id' => ['required', 'exists:players,id'],
            'term_start' => ['required', 'date'],
            'term_end' => ['required', 'date', 'after:term_start'],
            'base_salary_rwf' => ['required', 'integer', 'min:0'],
            'bonuses_notes' => ['nullable', 'string'],
            'benefits_notes' => ['nullable', 'string'],
            'player_obligations' => ['required', 'string'],
            'organisation_obligations' => ['required', 'string'],
            'termination_grounds' => ['nullable', 'string'],
            'dispute_resolution_mechanism' => ['nullable', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = PlayerContract::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, PlayerContract $playerContract)
    {
        Gate::authorize('players_safeguarding.update');

        $data = $request->validate([
            'ceo_signed_on' => ['nullable', 'date'],
            'sporting_director_signed_on' => ['nullable', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $playerContract->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($playerContract->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('players_safeguarding.report');

        return $this->genericReport(
            $request,
            PlayerContract::query()->with($this->withRelations()),
            'Player Contracts (PLAYER-001)',
            [
                'Player' => 'player.full_name',
                'Term Start' => 'term_start',
                'Term End' => 'term_end',
                'Base Salary (RWF)' => 'base_salary_rwf',
                'Status' => 'status.label_en',
            ],
            'player001-contracts'
        );
    }
}
