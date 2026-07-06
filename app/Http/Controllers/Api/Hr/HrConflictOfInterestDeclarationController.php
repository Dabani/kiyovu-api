<?php

namespace App\Http\Controllers\Api\Hr;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Hr\SimpleModelResource;
use App\Models\Hr\HrConflictOfInterestDeclaration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HrConflictOfInterestDeclarationController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return HrConflictOfInterestDeclaration::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['declarant_name', 'conflict_description'];
    }

    protected function filterableColumns(): array
    {
        return ['position_id', 'status_id', 'recusal_required'];
    }

    protected function withRelations(): array
    {
        return ['position', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'declaration_date';
    }

    protected function deletePermission(): ?string
    {
        return 'hr.delete';
    }

    public function index(Request $request)
    {
        Gate::authorize('hr.view');

        return parent::index($request);
    }

    public function store(Request $request)
    {
        Gate::authorize('hr.create');

        $data = $request->validate([
            'declarant_name' => ['required', 'string', 'max:255'],
            'position_id' => ['nullable', 'exists:lu_hq_positions,id'],
            'declaration_date' => ['required', 'date'],
            'conflict_description' => ['required', 'string'],
            'recusal_required' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        // Art. 144 — declarations are reviewed annually.
        $data['next_annual_update_due'] = \Carbon\Carbon::parse($data['declaration_date'])->addYear()->toDateString();

        $record = HrConflictOfInterestDeclaration::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, HrConflictOfInterestDeclaration $hrConflictOfInterestDeclaration)
    {
        Gate::authorize('hr.update');

        $data = $request->validate([
            'conflict_description' => ['required', 'string'],
            'recusal_required' => ['boolean'],
            'reviewed_by_name' => ['nullable', 'string', 'max:255'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $hrConflictOfInterestDeclaration->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($hrConflictOfInterestDeclaration->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('hr.report');

        return $this->genericReport(
            $request,
            HrConflictOfInterestDeclaration::query()->with($this->withRelations()),
            'Conflict of Interest Declarations (HR-003)',
            [
                'Declarant' => 'declarant_name',
                'Position' => 'position.label_en',
                'Declaration Date' => 'declaration_date',
                'Recusal Required' => 'recusal_required',
                'Next Review Due' => 'next_annual_update_due',
            ],
            'hr003-conflict-of-interest'
        );
    }
}
