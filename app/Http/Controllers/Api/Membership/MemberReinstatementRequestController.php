<?php

namespace App\Http\Controllers\Api\Membership;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Controllers\Api\Concerns\ScopesToOwnMember;
use App\Http\Resources\Membership\SimpleModelResource;
use App\Models\Membership\MemberReinstatementRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MemberReinstatementRequestController extends BaseModuleController
{
    use ScopesToOwnMember;

    protected function baseQuery(Request $request): Builder
    {
        return $this->scopeToOwnMember(MemberReinstatementRequest::query(), $request);
    }
    protected function modelClass(): string
    {
        return MemberReinstatementRequest::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['compliance_evidence', 'cro_recommendation'];
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
            'suspension_completed_on' => ['required', 'date'],
            'compliance_evidence' => ['required', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = MemberReinstatementRequest::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, MemberReinstatementRequest $memberReinstatementRequest)
    {
        Gate::authorize('membership.update');

        $data = $request->validate([
            'cro_recommendation' => ['nullable', 'string'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
            'decided_on' => ['nullable', 'date'],
            'ongoing_conditions' => ['nullable', 'string'],
        ]);

        $memberReinstatementRequest->update([...$data, 'updated_by' => Auth::id()]);

        if ($memberReinstatementRequest->status->code === 'approved') {
            $active = \App\Models\Lookups\LuStatus::where('code', 'member_active')->first();
            if ($active) {
                $memberReinstatementRequest->member->update([
                    'status_id' => $active->id,
                    'status_since' => $memberReinstatementRequest->decided_on ?? now(),
                    'status_reason' => 'Reinstated following completed suspension',
                ]);
            }
        }

        return new SimpleModelResource($memberReinstatementRequest->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('membership.report');

        return $this->genericReport(
            $request,
            MemberReinstatementRequest::query()->with($this->withRelations()),
            'Member Reinstatement Requests (MEM-007)',
            [
                'Member' => 'member.full_name',
                'Submitted On' => 'submitted_on',
                'Status' => 'status.label_en',
                'Decided On' => 'decided_on',
            ],
            'mem007-reinstatement-requests'
        );
    }
}
