<?php

namespace App\Http\Requests\Membership;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Membership\Member::class);
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'national_id' => ['required', 'string', 'max:50', 'unique:members,national_id'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'statement_of_commitment' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:lu_membership_categories,id'],
            'fee_tier_id' => ['required', 'exists:lu_fee_tiers,id'],
            'payment_method_id' => ['nullable', 'exists:lu_payment_methods,id'],
            'application_date' => ['required', 'date'],
            'hardship_payment_plan' => ['boolean'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
        ];
    }
}
