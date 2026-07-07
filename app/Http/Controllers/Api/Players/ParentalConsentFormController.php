<?php

namespace App\Http\Controllers\Api\Players;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Players\SimpleModelResource;
use App\Models\Players\ParentalConsentForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ParentalConsentFormController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return ParentalConsentForm::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['guardian_name'];
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
        return 'consent_date';
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
            'guardian_name' => ['required', 'string', 'max:255'],
            'relationship_to_minor' => ['required', 'string', 'max:100'],
            'guardian_phone' => ['required', 'string', 'max:30'],
            'consent_date' => ['required', 'date'],
            'activities_covered' => ['required', 'string'],
            'medical_treatment_consent' => ['boolean'],
            'media_image_consent' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = ParentalConsentForm::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, ParentalConsentForm $parentalConsentForm)
    {
        Gate::authorize('players_safeguarding.update');

        $data = $request->validate([
            'activities_covered' => ['required', 'string'],
            'medical_treatment_consent' => ['boolean'],
            'media_image_consent' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $parentalConsentForm->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($parentalConsentForm->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('players_safeguarding.report');

        return $this->genericReport(
            $request,
            ParentalConsentForm::query()->with($this->withRelations()),
            'Parental Consent Forms (SAFE-002)',
            [
                'Player' => 'player.full_name',
                'Guardian' => 'guardian_name',
                'Consent Date' => 'consent_date',
                'Status' => 'status.label_en',
            ],
            'safe002-parental-consent'
        );
    }
}
