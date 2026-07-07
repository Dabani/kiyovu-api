<?php

namespace App\Http\Controllers\Api\Players;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Players\SimpleModelResource;
use App\Models\Players\PlayerLoanAgreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PlayerLoanAgreementController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return PlayerLoanAgreement::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['counterparty_club_name'];
    }

    protected function filterableColumns(): array
    {
        return ['player_id', 'direction_id', 'status_id'];
    }

    protected function withRelations(): array
    {
        return ['player', 'direction', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'start_date';
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
            'direction_id' => ['required', 'exists:lu_loan_directions,id'],
            'counterparty_club_name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'compensation_rwf' => ['nullable', 'integer', 'min:0'],
            'obligations_notes' => ['nullable', 'string'],
            'recall_provisions' => ['nullable', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = PlayerLoanAgreement::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, PlayerLoanAgreement $playerLoanAgreement)
    {
        Gate::authorize('players_safeguarding.update');

        $data = $request->validate([
            'executive_organ_approved' => ['boolean'],
            'board_notified' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $playerLoanAgreement->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($playerLoanAgreement->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('players_safeguarding.report');

        return $this->genericReport(
            $request,
            PlayerLoanAgreement::query()->with($this->withRelations()),
            'Player Loan Agreements (PLAYER-003)',
            [
                'Player' => 'player.full_name',
                'Direction' => 'direction.label_en',
                'Counterparty' => 'counterparty_club_name',
                'Start Date' => 'start_date',
                'Status' => 'status.label_en',
            ],
            'player003-loan-agreements'
        );
    }
}
