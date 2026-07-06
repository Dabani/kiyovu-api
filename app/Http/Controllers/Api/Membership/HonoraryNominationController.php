<?php

namespace App\Http\Controllers\Api\Membership;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Membership\SimpleModelResource;
use App\Models\Membership\HonoraryNomination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HonoraryNominationController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return HonoraryNomination::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['nominee_name', 'basis_for_nomination'];
    }

    protected function filterableColumns(): array
    {
        return ['nominee_type_id', 'status_id'];
    }

    protected function withRelations(): array
    {
        return ['nomineeType', 'status', 'dossier'];
    }

    protected function reportDateColumn(): string
    {
        return 'nominated_on';
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
            'nominee_name' => ['required', 'string', 'max:255'],
            'nominee_type_id' => ['required', 'exists:lu_nominee_types,id'],
            'basis_for_nomination' => ['required', 'string'],
            'executive_organ_endorsed' => ['boolean'],
            'board_endorsed' => ['boolean'],
            'nominated_on' => ['required', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
            'conflict_of_interest_disclosed' => ['boolean'],
            'conflict_of_interest_notes' => ['nullable', 'string'],
        ]);

        $record = HonoraryNomination::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, HonoraryNomination $honoraryNomination)
    {
        Gate::authorize('membership.update');

        $data = $request->validate([
            'basis_for_nomination' => ['required', 'string'],
            'executive_organ_endorsed' => ['boolean'],
            'board_endorsed' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
            'ga_decision_date' => ['nullable', 'date'],
            'conflict_of_interest_disclosed' => ['boolean'],
            'conflict_of_interest_notes' => ['nullable', 'string'],
        ]);

        // Art. 20 — GA may not act on a nomination lacking dual endorsement.
        if (($data['status_id'] ?? null)
            && \App\Models\Lookups\LuStatus::find($data['status_id'])?->code === 'approved'
            && ! ($data['executive_organ_endorsed'] && $data['board_endorsed'])) {
            return response()->json([
                'message' => 'Cannot approve: nomination requires both Executive Organ and Board endorsement first.',
            ], 422);
        }

        $honoraryNomination->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($honoraryNomination->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('membership.report');

        return $this->genericReport(
            $request,
            HonoraryNomination::query()->with($this->withRelations()),
            'Honorary Membership Nominations (HON-001)',
            [
                'Nominee' => 'nominee_name',
                'Type' => 'nomineeType.label_en',
                'Nominated On' => 'nominated_on',
                'Status' => 'status.label_en',
                'GA Decision Date' => 'ga_decision_date',
            ],
            'hon001-honorary-nominations'
        );
    }
}
