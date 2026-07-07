<?php

namespace App\Http\Controllers\Api\Players;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Players\SimpleModelResource;
use App\Models\Players\CodeOfConductAcknowledgement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CodeOfConductAcknowledgementController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return CodeOfConductAcknowledgement::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['signatory_name'];
    }

    protected function filterableColumns(): array
    {
        return ['signatory_type_id', 'position_id', 'status_id'];
    }

    protected function withRelations(): array
    {
        return ['signatoryType', 'position', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'signed_date';
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
            'signatory_name' => ['required', 'string', 'max:255'],
            'signatory_type_id' => ['required', 'exists:lu_signatory_types,id'],
            'position_id' => ['nullable', 'exists:lu_hq_positions,id'],
            'signed_date' => ['required', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = CodeOfConductAcknowledgement::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, CodeOfConductAcknowledgement $codeOfConductAcknowledgement)
    {
        Gate::authorize('players_safeguarding.update');

        $data = $request->validate([
            'safeguarding_training_completed_on' => ['nullable', 'date'],
            'safeguarding_certification_expiry' => ['nullable', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $codeOfConductAcknowledgement->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($codeOfConductAcknowledgement->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('players_safeguarding.report');

        return $this->genericReport(
            $request,
            CodeOfConductAcknowledgement::query()->with($this->withRelations()),
            'Code of Conduct Acknowledgements (SAFE-003)',
            [
                'Signatory' => 'signatory_name',
                'Type' => 'signatoryType.label_en',
                'Signed Date' => 'signed_date',
                'Training Completed' => 'safeguarding_training_completed_on',
                'Status' => 'status.label_en',
            ],
            'safe003-conduct-acknowledgements'
        );
    }
}
