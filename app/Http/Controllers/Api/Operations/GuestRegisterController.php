<?php

namespace App\Http\Controllers\Api\Operations;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\Operations\SimpleModelResource;
use App\Models\Operations\GuestRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class GuestRegisterController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return GuestRegister::class;
    }

    protected function resourceClass(): string
    {
        return SimpleModelResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['guest_name', 'guest_organization', 'event_description'];
    }

    protected function filterableColumns(): array
    {
        return ['status_id', 'is_partner_guest'];
    }

    protected function withRelations(): array
    {
        return ['status'];
    }

    protected function reportDateColumn(): string
    {
        return 'match_date';
    }

    protected function deletePermission(): ?string
    {
        return 'operations_security_commissions.delete';
    }

    public function index(Request $request)
    {
        Gate::authorize('operations_security_commissions.view');

        return parent::index($request);
    }

    public function store(Request $request)
    {
        Gate::authorize('operations_security_commissions.create');

        $data = $request->validate([
            'match_date' => ['required', 'date'],
            'event_description' => ['required', 'string', 'max:255'],
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_organization' => ['nullable', 'string', 'max:255'],
            'is_partner_guest' => ['boolean'],
            'host_name' => ['required', 'string', 'max:255'],
            'ceo_approved_on' => ['nullable', 'date'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $record = GuestRegister::create([
            ...$data, 'created_by' => Auth::id(), 'updated_by' => Auth::id(),
        ]);

        return new SimpleModelResource($record->load($this->withRelations()));
    }

    public function update(Request $request, GuestRegister $guestRegister)
    {
        Gate::authorize('operations_security_commissions.update');

        $data = $request->validate([
            'ceo_approved_on' => ['nullable', 'date'],
            'guest_signed' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ]);

        $guestRegister->update([...$data, 'updated_by' => Auth::id()]);

        return new SimpleModelResource($guestRegister->load($this->withRelations()));
    }

    public function report(Request $request)
    {
        Gate::authorize('operations_security_commissions.report');

        return $this->genericReport(
            $request,
            GuestRegister::query()->with($this->withRelations()),
            'Guest Register (OPS-001)',
            [
                'Match Date' => 'match_date',
                'Guest' => 'guest_name',
                'Organization' => 'guest_organization',
                'Host' => 'host_name',
                'Status' => 'status.label_en',
            ],
            'ops001-guest-register'
        );
    }
}
