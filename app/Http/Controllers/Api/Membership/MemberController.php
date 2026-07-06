<?php

namespace App\Http\Controllers\Api\Membership;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Requests\Membership\StoreMemberRequest;
use App\Http\Requests\Membership\UpdateMemberRequest;
use App\Http\Resources\Membership\MemberResource;
use App\Models\Membership\Member;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends BaseModuleController
{
  protected function modelClass(): string
  {
    return Member::class;
  }

  protected function resourceClass(): string
  {
    return MemberResource::class;
  }

  protected function searchableColumns(): array
  {
    return ['full_name', 'national_id', 'phone', 'email'];
  }

  protected function filterableColumns(): array
  {
    return ['category_id', 'fee_tier_id', 'status_id'];
  }

  protected function withRelations(): array
  {
    return ['category', 'feeTier', 'status'];
  }

  protected function reportDateColumn(): string
  {
    return 'application_date';
  }

  protected function baseQuery(Request $request): Builder
  {
    $query = Member::query();
    $user = $request->user();

    // Self-service: a plain `member` sees only their own registry entry.
    if ($user->hasRole('member') && ! $user->hasAnyRole(['super_admin', 'president', 'secretary_general'])) {
      $query->where('user_id', $user->id);
    }

    return $query;
  }

  public function index(Request $request)
  {
    $this->authorize('viewAny', Member::class);

    return parent::index($request);
  }

  public function store(StoreMemberRequest $request)
  {
    $member = Member::create([
      ...$request->validated(),
      'created_by' => Auth::id(),
      'updated_by' => Auth::id(),
    ]);

    return new MemberResource($member->load($this->withRelations()));
  }

  public function show(int $id)
  {
    $member = Member::with($this->withRelations())->findOrFail($id);
    $this->authorize('view', $member);

    return new MemberResource($member);
  }

  public function update(UpdateMemberRequest $request, Member $member)
  {
    $member->update([...$request->validated(), 'updated_by' => Auth::id()]);

    return new MemberResource($member->load($this->withRelations()));
  }

  /**
   * GET /api/members/report?period=monthly — server-rendered PDF, mirrored
   * on every module controller. Frontend's ReportPeriodModal calls this.
   */
  public function report(Request $request)
  {
    $this->authorize('viewAny', Member::class);

    $query = Member::query()->with($this->withRelations());
    $query = $this->applyReportPeriod($query, $request);
    $members = $query->orderBy('full_name')->get();

    $pdf = Pdf::loadView('reports.members', [
      'members' => $members,
      'period' => $request->string('period', 'all')->toString(),
      'generatedAt' => now(),
    ])->setPaper('a4', 'landscape');

    return $pdf->download('kiyovu-members-report-' . now()->format('Y-m-d') . '.pdf');
  }
}
