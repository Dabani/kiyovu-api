<?php

namespace App\Http\Controllers\Api\Hr;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Hr\SimpleModelResource;
use App\Models\Hr\HrGiftDeclaration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HrGiftDeclarationController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return HrGiftDeclaration::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['declarant_name', 'gift_description'];
    }

    protected function filterableColumns(): array
    {
        return ['position_id', 'status_id', 'disposition_id'];
    }

    protected function withRelations(): array
    {
        return ['position', 'status', 'disposition'];
    }

    protected function reportDateColumn(): string
    {
        return 'date_received';
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
            'gift_description' => ['required', 'string', 'max:500'],
            'estimated_value_rwf' => ['required', 'integer', 'min:30001'], // Art. 128 threshold
            'date_received' => ['required', 'date'],
            'declared_on' => ['required', 'date', 'after_or_equal:date_received'],
            'disposition_id' => ['nullable', 'exists:lu_gift_dispositions,id'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = HrGiftDeclaration::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, HrGiftDeclaration $hrGiftDeclaration)
    {
        Gate::authorize('hr.update');

        $data = $request->validate([
            'disposition_id' => ['nullable', 'exists:lu_gift_dispositions,id'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $hrGiftDeclaration->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($hrGiftDeclaration->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('hr.report');

        return $this->genericReport(
            $request,
            HrGiftDeclaration::query()->with($this->withRelations()),
            'Gift Declarations (HR-004)',
            [
                'Declarant' => 'declarant_name',
                'Gift' => 'gift_description',
                'Value (RWF)' => 'estimated_value_rwf',
                'Date Received' => 'date_received',
                'Disposition' => 'disposition.label_en',
            ],
            'hr004-gift-declarations'
        );
    }
}
