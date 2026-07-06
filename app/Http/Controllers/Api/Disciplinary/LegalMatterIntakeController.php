<?php

namespace App\Http\Controllers\Api\Disciplinary;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Disciplinary\SimpleModelResource;
use App\Models\Disciplinary\LegalMatterIntake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LegalMatterIntakeController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return LegalMatterIntake::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['matter_description', 'notified_by_name'];
    }

    protected function filterableColumns(): array
    {
        return ['forum_id', 'urgency_id', 'status_id'];
    }

    protected function withRelations(): array
    {
        return ['forum', 'urgency', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'notified_on';
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
            'matter_description' => ['required', 'string'],
            'notified_by_name' => ['required', 'string', 'max:255'],
            'notified_by_role' => ['nullable', 'string', 'max:255'],
            'notified_on' => ['required', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = LegalMatterIntake::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, LegalMatterIntake $legalMatterIntake)
    {
        Gate::authorize('disciplinary_legal.update');

        $data = $request->validate([
            'forum_id' => ['nullable', 'exists:lu_legal_forums,id'],
            'urgency_id' => ['nullable', 'exists:lu_legal_urgency,id'],
            'classified_on' => ['nullable', 'date'],
            'deadline_date' => ['nullable', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        // Art. 613 — a deadline under 14 days is automatically flagged urgent and reported to the President.
        if (! empty($data['deadline_date'])) {
            $daysToDeadline = now()->diffInDays(\Carbon\Carbon::parse($data['deadline_date']), false);
            $data['reported_to_president'] = $daysToDeadline >= 0 && $daysToDeadline < 14;
        }

        $legalMatterIntake->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($legalMatterIntake->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('disciplinary_legal.report');

        return $this->genericReport(
            $request,
            LegalMatterIntake::query()->with($this->withRelations()),
            'Legal Matter Intakes (LEG-001)',
            [
                'Notified By' => 'notified_by_name',
                'Notified On' => 'notified_on',
                'Forum' => 'forum.label_en',
                'Urgency' => 'urgency.label_en',
                'Status' => 'status.label_en',
            ],
            'leg001-legal-matter-intakes'
        );
    }
}
