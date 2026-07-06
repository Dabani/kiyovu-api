<?php

namespace App\Http\Controllers\Api\Disciplinary;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Disciplinary\SimpleModelResource;
use App\Models\Disciplinary\DisciplinaryNotice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DisciplinaryNoticeController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return DisciplinaryNotice::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['allegations_summary', 'hearing_venue'];
    }

    protected function filterableColumns(): array
    {
        return ['case_id', 'notice_type_id', 'status_id'];
    }

    protected function withRelations(): array
    {
        return ['case', 'noticeType', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'issued_on';
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
            'case_id' => ['required', 'exists:disciplinary_cases,id'],
            'notice_type_id' => ['required', 'exists:lu_notice_types,id'],
            'issued_on' => ['required', 'date'],
            'response_deadline' => ['nullable', 'date', 'after_or_equal:issued_on'],
            'hearing_date' => ['nullable', 'date'],
            'hearing_venue' => ['nullable', 'string', 'max:255'],
            'allegations_summary' => ['nullable', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = DisciplinaryNotice::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, DisciplinaryNotice $disciplinaryNotice)
    {
        Gate::authorize('disciplinary_legal.update');

        $data = $request->validate([
            'respondent_acknowledged' => ['boolean'],
            'respondent_response_received_on' => ['nullable', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $disciplinaryNotice->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($disciplinaryNotice->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('disciplinary_legal.report');

        return $this->genericReport(
            $request,
            DisciplinaryNotice::query()->with($this->withRelations()),
            'Disciplinary Notices (DISC-003)',
            [
                'Case' => 'case.respondent_name',
                'Type' => 'noticeType.label_en',
                'Issued On' => 'issued_on',
                'Response Deadline' => 'response_deadline',
                'Status' => 'status.label_en',
            ],
            'disc003-disciplinary-notices'
        );
    }
}
