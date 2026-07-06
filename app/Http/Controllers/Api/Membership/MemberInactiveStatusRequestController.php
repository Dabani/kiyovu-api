<?php

namespace App\Http\Controllers\Api\Membership;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Controllers\Api\Concerns\ScopesToOwnMember;
use App\Http\Resources\Membership\SimpleModelResource;
use App\Models\Membership\MemberInactiveStatusRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MemberInactiveStatusRequestController extends BaseModuleController
{
    use ScopesToOwnMember;

    protected function baseQuery(Request $request): Builder
    {
        return $this->scopeToOwnMember(MemberInactiveStatusRequest::query(), $request);
    }
    protected function modelClass(): string
    {
        return MemberInactiveStatusRequest::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['reason'];
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
        return 'requested_on';
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
            'requested_on' => ['required', 'date'],
            'reason' => ['required', 'string'],
            'effective_from' => ['required', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        // Art. 17 hard cap: inactive status may not exceed 2 years.
        $data['max_end_date'] = \Carbon\Carbon::parse($data['effective_from'])->addYears(2)->toDateString();

        $record = MemberInactiveStatusRequest::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, MemberInactiveStatusRequest $memberInactiveStatusRequest)
    {
        Gate::authorize('membership.update');

        $data = $request->validate([
            'reason' => ['required', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
            'reverted_to_active_on' => ['nullable', 'date'],
        ]);

        $memberInactiveStatusRequest->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($memberInactiveStatusRequest->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('membership.report');

        return $this->genericReport(
            $request,
            MemberInactiveStatusRequest::query()->with($this->withRelations()),
            'Member Inactive Status Requests (MEM-004)',
            [
                'Member' => 'member.full_name',
                'Effective From' => 'effective_from',
                'Max End Date' => 'max_end_date',
                'Status' => 'status.label_en',
            ],
            'mem004-inactive-status-requests'
        );
    }
}
