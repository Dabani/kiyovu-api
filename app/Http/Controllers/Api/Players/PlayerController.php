<?php

namespace App\Http\Controllers\Api\Players;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Players\SimpleModelResource;
use App\Models\Players\Player;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PlayerController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return Player::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['full_name', 'national_id_or_passport', 'ferwafa_registration_number'];
    }

    protected function filterableColumns(): array
    {
        return ['team_id', 'status_id', 'is_minor'];
    }

    protected function withRelations(): array
    {
        return ['team', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'registration_date';
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
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'nationality' => ['required', 'string', 'max:100'],
            'position' => ['required', 'string', 'max:100'],
            'team_id' => ['required', 'exists:lu_player_teams,id'],
            'national_id_or_passport' => ['required', 'string', 'max:100'],
            'ferwafa_registration_number' => ['nullable', 'string', 'max:100', 'unique:players,ferwafa_registration_number'],
            'registration_date' => ['required', 'date'],
            'medical_clearance_certified' => ['boolean'],
            'medical_clearance_date' => ['nullable', 'date'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        // Minor status is derived from date of birth, not self-declared (Art. 200-201).
        $data['is_minor'] = Carbon::parse($data['date_of_birth'])->age < 18;

        $record = Player::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, Player $player)
    {
        Gate::authorize('players_safeguarding.update');

        $data = $request->validate([
            'ferwafa_registration_number' => ['nullable', 'string', 'max:100', "unique:players,ferwafa_registration_number,{$player->id}"],
            'medical_clearance_certified' => ['boolean'],
            'medical_clearance_date' => ['nullable', 'date'],
            'itc_reference' => ['nullable', 'string', 'max:100'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $player->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($player->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('players_safeguarding.report');

        return $this->genericReport(
            $request,
            Player::query()->with($this->withRelations()),
            'Player Registrations (PLAYER-002)',
            [
                'Name' => 'full_name',
                'Team' => 'team.label_en',
                'FERWAFA No.' => 'ferwafa_registration_number',
                'Registration Date' => 'registration_date',
                'Status' => 'status.label_en',
            ],
            'player002-registrations'
        );
    }
}
