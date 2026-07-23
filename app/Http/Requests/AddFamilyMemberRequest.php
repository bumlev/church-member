<?php

namespace App\Http\Requests;

use App\Enums\RoleType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddFamilyMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'member_id'  => ['required', 'integer', 'exists:member,id'],
            'role_type'  => ['required', Rule::enum(RoleType::class)],
            'start_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'member_id.required' => 'Member is required.',
            'member_id.exists'   => 'Selected member is invalid.',
            'role_type.required' => 'Role type is required.',
            'role_type.enum'     => 'Selected role type is invalid.',
            'start_date.date'    => 'Start date must be a valid date.',
            'end_date.date'      => 'End date must be a valid date.',
            'end_date.after_or_equal' => 'End date must be on or after the start date.',
        ];
    }
}
