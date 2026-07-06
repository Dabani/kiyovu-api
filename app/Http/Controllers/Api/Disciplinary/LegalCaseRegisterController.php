<?php

namespace App\Http\Controllers\Api\Disciplinary;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Disciplinary\SimpleModelResource;
use App\Models\Disciplinary\LegalCaseRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LegalCaseRegisterController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return LegalCaseRegister::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['case_reference', 'outcome'];
    }

    protected function filterableColumns(): array
    {
        return ['forum_id', 'status_id', 'classification_id'];
    }

    protected function withRelations(): array
    {
        return ['forum', 'classification', 'status', 'intake'];
    }

    protected function reportDateColumn(): string
    {
        return 'opened_on';
    }

    protected function deletePermission(): ?string
    {
        return 'disciplinary_legal.delete';
    }

    public function index(Request $request)
    {
        Gate::authorize('disciplinary_legal.view');

        return parent::index($request);
    }

    public function store(Request $request)
    {
        Gate::authorize('disciplinary_legal.create');

        $data = $request->validate([
            'intake_id' => ['nullable', 'exists:legal_matter_intakes,id'],
            'case_reference' => ['required', 'string', 'max:255', 'unique:legal_case_register,case_reference'],
            'forum_id' => ['required', 'exists:lu_legal_forums,id'],
            'classification_id' => ['nullable', 'exists:lu_document_classifications,id'],
            'opened_on' => ['required', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = LegalCaseRegister::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, LegalCaseRegister $legalCaseRegister)
    {
        Gate::authorize('disciplinary_legal.update');

        $data = $request->validate([
            'last_updated_on' => ['nullable', 'date'],
            'outcome' => ['nullable', 'string'],
            'closed_on' => ['nullable', 'date'],
            'reported_to_executive_organ_quarterly' => ['boolean'],
            'reported_to_ga_annually' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $legalCaseRegister->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($legalCaseRegister->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('disciplinary_legal.report');

        return $this->genericReport(
            $request,
            LegalCaseRegister::query()->with($this->withRelations()),
            'Legal Case Register (LEG-002)',
            [
                'Case Reference' => 'case_reference',
                'Forum' => 'forum.label_en',
                'Opened On' => 'opened_on',
                'Status' => 'status.label_en',
                'Closed On' => 'closed_on',
            ],
            'leg002-legal-case-register'
        );
    }
}
