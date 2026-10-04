<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckMemberAgeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_birthday' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'date_birthday.required'        => 'Date of birth is required.',
            'date_birthday.date_format'     => 'Date of birth must be in the format yyyy-mm-dd.',
            'date_birthday.before_or_equal' => 'Date of birth cannot be in the future.',
        ];
    }
}
