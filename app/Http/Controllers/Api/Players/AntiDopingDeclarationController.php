<?php

namespace App\Http\Controllers\Api\Players;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Players\SimpleModelResource;
use App\Models\Players\AntiDopingDeclaration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AntiDopingDeclarationController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return AntiDopingDeclaration::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['tue_notes'];
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
        return 'declaration_date';
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
            'declaration_date' => ['required', 'date'],
            'wada_list_acknowledged' => ['boolean'],
            'tue_application_filed' => ['boolean'],
            'tue_notes' => ['nullable', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = AntiDopingDeclaration::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, AntiDopingDeclaration $antiDopingDeclaration)
    {
        Gate::authorize('players_safeguarding.update');

        $data = $request->validate([
            'tue_application_filed' => ['boolean'],
            'tue_notes' => ['nullable', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $antiDopingDeclaration->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($antiDopingDeclaration->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('players_safeguarding.report');

        return $this->genericReport(
            $request,
            AntiDopingDeclaration::query()->with($this->withRelations()),
            'Anti-Doping Declarations (PLAYER-004)',
            [
                'Player' => 'player.full_name',
                'Declaration Date' => 'declaration_date',
                'WADA List Acknowledged' => 'wada_list_acknowledged',
                'Status' => 'status.label_en',
            ],
            'player004-anti-doping'
        );
    }
}
