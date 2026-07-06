<?php

namespace App\Http\Controllers\Api\Membership;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Controllers\Api\Concerns\ScopesToOwnMember;
use App\Http\Resources\Membership\SimpleModelResource;
use App\Models\Membership\MemberFeeWaiverRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MemberFeeWaiverRequestController extends BaseModuleController
{
    use ScopesToOwnMember;

    protected function baseQuery(Request $request): Builder
    {
        return $this->scopeToOwnMember(MemberFeeWaiverRequest::query(), $request);
    }
    protected function modelClass(): string
    {
        return MemberFeeWaiverRequest::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['hardship_justification'];
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
            'hardship_justification' => ['required', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = MemberFeeWaiverRequest::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, MemberFeeWaiverRequest $memberFeeWaiverRequest)
    {
        Gate::authorize('membership.update');

        $data = $request->validate([
            'hardship_justification' => ['required', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
            'reviewed_on' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date'],
        ]);

        $memberFeeWaiverRequest->update([...$data, 'updated_by' => Auth::id()]);

        // Approving a waiver flips the flag on the parent member record for quick registry filtering.
        if ($memberFeeWaiverRequest->status->code === 'approved') {
            $memberFeeWaiverRequest->member->update(['has_active_fee_waiver' => true]);
        }

        return new SimpleModelResource($memberFeeWaiverRequest->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('membership.report');

        return $this->genericReport(
            $request,
            MemberFeeWaiverRequest::query()->with($this->withRelations()),
            'Member Fee Waiver Requests (MEM-005)',
            [
                'Member' => 'member.full_name',
                'Requested On' => 'requested_on',
                'Status' => 'status.label_en',
                'Valid Until' => 'valid_until',
            ],
            'mem005-fee-waiver-requests'
        );
    }
}
