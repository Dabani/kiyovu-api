<?php

namespace App\Http\Controllers\Api\Membership;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Controllers\Api\Concerns\ScopesToOwnMember;
use App\Http\Resources\Membership\SimpleModelResource;
use App\Models\Membership\MemberInformationRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MemberInformationRequestController extends BaseModuleController
{
    use ScopesToOwnMember;

    protected function baseQuery(Request $request): Builder
    {
        return $this->scopeToOwnMember(MemberInformationRequest::query(), $request);
    }
    protected function modelClass(): string
    {
        return MemberInformationRequest::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['information_requested', 'response_notes'];
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
            'information_requested' => ['required', 'string'],
            'requested_on' => ['required', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = MemberInformationRequest::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, MemberInformationRequest $memberInformationRequest)
    {
        Gate::authorize('membership.update');

        $data = $request->validate([
            'information_requested' => ['required', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
            'responded_on' => ['nullable', 'date'],
            'response_notes' => ['nullable', 'string'],
            'denial_reason' => ['nullable', 'string'],
            'appealed_to_board' => ['boolean'],
        ]);

        $memberInformationRequest->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($memberInformationRequest->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('membership.report');

        return $this->genericReport(
            $request,
            MemberInformationRequest::query()->with($this->withRelations()),
            'Member Information Requests (MEM-003)',
            [
                'Member' => 'member.full_name',
                'Requested On' => 'requested_on',
                'Status' => 'status.label_en',
                'Responded On' => 'responded_on',
            ],
            'mem003-information-requests'
        );
    }
}
