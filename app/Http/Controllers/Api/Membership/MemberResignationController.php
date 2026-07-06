<?php

namespace App\Http\Controllers\Api\Membership;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Controllers\Api\Concerns\ScopesToOwnMember;
use App\Http\Resources\Membership\SimpleModelResource;
use App\Models\Membership\MemberResignation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MemberResignationController extends BaseModuleController
{
    use ScopesToOwnMember;

    protected function baseQuery(Request $request): Builder
    {
        return $this->scopeToOwnMember(MemberResignation::query(), $request);
    }
    protected function modelClass(): string
    {
        return MemberResignation::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['resignation_letter'];
    }

    protected function filterableColumns(): array
    {
        return ['member_id', 'status_id'];
    }

    protected function withRelations(): array
    {
        return ['member', 'status'];
    }

    protected function reportDateColumn(): string
    {
        return 'submitted_on';
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
            'member_id' => ['required', 'exists:members,id'],
            'submitted_on' => ['required', 'date'],
            'resignation_letter' => ['required', 'string'],
            'outstanding_obligations' => ['boolean'],
            'outstanding_obligations_notes' => ['nullable', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = MemberResignation::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, MemberResignation $memberResignation)
    {
        Gate::authorize('membership.update');

        $data = $request->validate([
            'outstanding_obligations' => ['boolean'],
            'outstanding_obligations_notes' => ['nullable', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
            'ga_approval_date' => ['nullable', 'date'],
        ]);

        $memberResignation->update([...$data, 'updated_by' => Auth::id()]);

        if ($memberResignation->status->code === 'approved') {
            $terminated = \App\Models\Lookups\LuStatus::where('code', 'member_terminated')->first();
            if ($terminated) {
                $memberResignation->member->update([
                    'status_id' => $terminated->id,
                    'status_since' => $memberResignation->ga_approval_date ?? now(),
                    'status_reason' => 'Resignation approved by General Assembly',
                ]);
            }
        }

        return new SimpleModelResource($memberResignation->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('membership.report');

        return $this->genericReport(
            $request,
            MemberResignation::query()->with($this->withRelations()),
            'Member Resignations (MEM-006)',
            [
                'Member' => 'member.full_name',
                'Submitted On' => 'submitted_on',
                'Status' => 'status.label_en',
                'GA Approval Date' => 'ga_approval_date',
            ],
            'mem006-resignations'
        );
    }
}
