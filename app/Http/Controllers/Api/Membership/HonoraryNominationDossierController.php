<?php

namespace App\Http\Controllers\Api\Membership;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Membership\SimpleModelResource;
use App\Models\Membership\HonoraryNominationDossier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HonoraryNominationDossierController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return HonoraryNominationDossier::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['contributions_summary', 'justification', 'prepared_by_name'];
    }

    protected function filterableColumns(): array
    {
        return ['honorary_nomination_id'];
    }

    protected function withRelations(): array
    {
        return ['nomination'];
    }

    protected function reportDateColumn(): string
    {
        return 'prepared_on';
    }

    protected function deletePermission(): ?string
    {
        return 'membership.delete';
    }

    public function index(Request $request)
    {
        Gate::authorize('membership.view');

        return parent::index($request);
    }

    public function store(Request $request)
    {
        Gate::authorize('membership.create');

        $data = $request->validate([
            'honorary_nomination_id' => ['required', 'exists:honorary_nominations,id', 'unique:honorary_nomination_dossiers,honorary_nomination_id'],
            'contributions_summary' => ['required', 'string'],
            'justification' => ['required', 'string'],
            'prepared_on' => ['required', 'date'],
            'prepared_by_name' => ['required', 'string', 'max:255'],
        ]);

        $record = HonoraryNominationDossier::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, HonoraryNominationDossier $honoraryNominationDossier)
    {
        Gate::authorize('membership.update');

        $data = $request->validate([
            'contributions_summary' => ['required', 'string'],
            'justification' => ['required', 'string'],
            'prepared_by_name' => ['required', 'string', 'max:255'],
        ]);

        $honoraryNominationDossier->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($honoraryNominationDossier->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('membership.report');

        return $this->genericReport(
            $request,
            HonoraryNominationDossier::query()->with($this->withRelations()),
            'Honorary Nomination Dossiers (HON-002)',
            [
                'Nominee' => 'nomination.nominee_name',
                'Prepared On' => 'prepared_on',
                'Prepared By' => 'prepared_by_name',
            ],
            'hon002-nomination-dossiers'
        );
    }
}
