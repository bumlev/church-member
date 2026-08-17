<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckMemberExistsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'    => ['required', 'string', 'max:100'],
            'last_name'     => ['required', 'string', 'max:100'],
            'date_birthday' => ['required', 'date_format:Y-m-d'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'       => 'First name is required.',
            'last_name.required'        => 'Last name is required.',
            'date_birthday.required'    => 'Date of birth is required.',
            'date_birthday.date_format' => 'Date of birth must be in the format yyyy-mm-dd.',
        ];
    }
}
