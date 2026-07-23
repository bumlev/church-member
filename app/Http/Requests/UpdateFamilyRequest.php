<?php

namespace App\Http\Requests;

use App\Enums\RoleType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFamilyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // ── Required fields (still required on update) ─────────────────────
            'family_name'           => ['sometimes', 'required', 'string', 'max:150'],

            // ── Optional fields ────────────────────────────────────────────────
            'address'               => ['nullable', 'string', 'max:255'],
            'date_formed'           => ['nullable', 'date'],

            // ── Members (each paired with the role they play in this family) ───
            'members'               => ['nullable', 'array'],
            'members.*.member_id'   => ['required', 'integer', 'exists:member,id'],
            'members.*.role_type'   => ['required', Rule::enum(RoleType::class)],
            'members.*.start_date'  => ['nullable', 'date'],
            'members.*.end_date'    => ['nullable', 'date', 'after_or_equal:members.*.start_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'family_name.required'         => 'Family name is required.',
            'family_name.max'              => 'Family name must not exceed 150 characters.',
            'date_formed.date'             => 'Date formed must be a valid date.',
            'members.array'                => 'Members must be a list of member entries.',
            'members.*.member_id.required' => 'Each member entry must specify a member_id.',
            'members.*.member_id.exists'   => 'Selected member is invalid.',
            'members.*.role_type.required' => 'Each member entry must specify a role_type.',
            'members.*.role_type.enum'     => 'Selected role type is invalid.',
            'members.*.start_date.date'    => 'Start date must be a valid date.',
            'members.*.end_date.date'      => 'End date must be a valid date.',
            'members.*.end_date.after_or_equal' => 'End date must be on or after the start date.',
        ];
    }
}
