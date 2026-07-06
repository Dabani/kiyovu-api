<?php

namespace App\Http\Controllers\Api\Disciplinary;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Disciplinary\SimpleModelResource;
use App\Models\Disciplinary\WhistleblowerReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class WhistleblowerReportController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return WhistleblowerReport::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['description', 'reporter_name'];
    }

    protected function filterableColumns(): array
    {
        return ['category_id', 'status_id', 'is_anonymous'];
    }

    protected function withRelations(): array
    {
        return ['category', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'reported_on';
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
            'category_id' => ['required', 'exists:lu_whistleblower_categories,id'],
            'is_anonymous' => ['boolean'],
            'reporter_name' => ['nullable', 'string', 'max:255', 'required_if:is_anonymous,false'],
            'reported_on' => ['required', 'date'],
            'description' => ['required', 'string'],
            'related_disciplinary_case_id' => ['nullable', 'exists:disciplinary_cases,id'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        // Anonymity is protected — never persist a name alongside is_anonymous=true (Art. 274).
        if ($data['is_anonymous'] ?? false) {
            $data['reporter_name'] = null;
        }

        $record = WhistleblowerReport::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, WhistleblowerReport $whistleblowerReport)
    {
        Gate::authorize('disciplinary_legal.update');

        $data = $request->validate([
            'receipt_acknowledged_on' => ['nullable', 'date'],
            'initial_assessment_completed_on' => ['nullable', 'date'],
            'referred_to' => ['nullable', 'string', 'max:255'],
            'related_disciplinary_case_id' => ['nullable', 'exists:disciplinary_cases,id'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $whistleblowerReport->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($whistleblowerReport->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('disciplinary_legal.report');

        return $this->genericReport(
            $request,
            WhistleblowerReport::query()->with($this->withRelations()),
            'Whistleblower Reports (DISC-005)',
            [
                'Category' => 'category.label_en',
                'Anonymous' => 'is_anonymous',
                'Reported On' => 'reported_on',
                'Status' => 'status.label_en',
            ],
            'disc005-whistleblower-reports'
        );
    }
}
