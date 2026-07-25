<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberRequest extends FormRequest
{
    /**
     * ID-list fields: normally a JSON-array string over multipart/form-data,
     * but a client sending just one ID as a bare value (e.g. talent=1) is
     * also accepted by wrapping it into a single-element array.
     */
    private const array SCALAR_LIST_FIELDS = ['talent', 'spiritual_gift', 'occupation'];

    /**
     * Array-of-object fields: cannot travel as real nested arrays over
     * multipart/form-data unless the client builds bracket-notation keys by
     * hand. Every practical multipart client — including Swagger UI's own
     * form tester — instead sends them as a single JSON-encoded string. A
     * bare scalar has no sensible object interpretation, so it is left
     * as-is and surfaces as a normal "must be an array" validation error.
     */
    private const array NESTED_OBJECT_ARRAY_FIELDS = ['education', 'department'];

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeBoolean('employed');

        foreach (self::SCALAR_LIST_FIELDS as $field) {
            $this->normalizeArrayField($field, wrapScalars: true);
        }

        foreach (self::NESTED_OBJECT_ARRAY_FIELDS as $field) {
            $this->normalizeArrayField($field, wrapScalars: false);
        }
    }

    /**
     * multipart/form-data has no native boolean type, so clients send the
     * literal string "true"/"false" (or "1"/"0"/"on"/"off"), which Laravel's
     * strict `boolean` rule rejects. Coerce to a real bool before validation.
     */
    private function normalizeBoolean(string $field): void
    {
        $value = $this->input($field);

        if (is_string($value)) {
            $normalized = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            if ($normalized !== null) {
                $this->merge([$field => $normalized]);
            }
        }
    }

    private function normalizeArrayField(string $field, bool $wrapScalars): void
    {
        $value = $this->input($field);

        if ($value === null) {
            return;
        }

        if (is_string($value)) {
            $trimmed = trim($value);

            // A blank submission means "nothing selected" — treat as absent
            // so `nullable`/`required` produce their own clear message
            // instead of a confusing "must be an array" one.
            if ($trimmed === '') {
                $this->merge([$field => null]);
                return;
            }

            $decoded = json_decode($trimmed, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            } elseif ($wrapScalars && str_contains($trimmed, ',')) {
                // Common shorthand for an ID list that isn't valid JSON: "1,2,3".
                $value = array_map('trim', explode(',', $trimmed));
            }
        }

        if ($wrapScalars && !is_array($value)) {
            $value = [$value];
        }

        $this->merge([$field => $value]);
    }

    public function rules(): array
    {
        $rules = [
            // ── Required fields (still required on update) ─────────────────────
            'first_name'        => ['sometimes', 'required', 'string', 'max:100'],
            'last_name'         => ['sometimes', 'required', 'string', 'max:100'],
            'talent'            => ['sometimes', 'required', 'array', 'min:1'],
            'talent.*'          => ['integer', 'distinct', 'exists:talent,id'],
            'spiritual_gift'    => ['sometimes', 'required', 'array', 'min:1'],
            'spiritual_gift.*'  => ['integer', 'distinct', 'exists:spiritual_gift,id'],
            'sex_id'            => ['sometimes', 'required', 'integer', 'exists:sex,id'],
            'marital_status_id' => ['sometimes', 'required', 'integer', 'exists:marital_status,id'],
            'date_birthday'     => ['sometimes', 'required', 'date_format:Y-m-d'],

            // ── Optional personal fields ───────────────────────────────────────
            'fathers_name'      => ['nullable', 'string', 'max:150'],
            'mothers_name'      => ['nullable', 'string', 'max:150'],
            'employed'          => ['nullable', 'boolean'],
            'national_id'       => [
                'nullable', 'string', 'max:20',
                Rule::unique('member', 'national_id')->ignore($this->route('member')),
            ],
            'picture'           => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],

            // ── Optional dates ─────────────────────────────────────────────────
            'date_salvation'    => ['nullable', 'date_format:Y-m-d'],
            'date_baptism'      => ['nullable', 'date_format:Y-m-d'],
            'member_since'      => ['nullable', 'date_format:Y-m-d'],

            // ── Optional lookup FKs ────────────────────────────────────────────
            'occupation'                => ['nullable', 'array'],
            'occupation.*'              => ['integer', 'distinct', 'exists:occupation,id'],
            'education'                 => ['nullable', 'array'],
            'education.*.education_id' => ['required', 'integer', 'exists:education,id', 'distinct'],
            'education.*.faculty'      => ['nullable', 'array'],
            'department'                          => ['nullable', 'array'],
            'department.*.department_id'          => ['required', 'integer', 'exists:department,id', 'distinct'],
            'department.*.church_responsibility'  => ['nullable', 'array'],

            // ── Contact ────────────────────────────────────────────────────────
            'mobile_tel'        => ['nullable', 'string', 'max:20'],
            'email'             => ['nullable', 'email', 'max:150'],

            // ── Geographic FKs ─────────────────────────────────────────────────
            'province_id'       => ['nullable', 'integer', 'exists:province,id'],
            'district_id'       => ['nullable', 'integer', 'exists:district,id'],
            'sector_id'         => ['nullable', 'integer', 'exists:sector,id'],
            'cellule_id'        => ['nullable', 'integer', 'exists:cellule,id'],
            'cell_id'           => ['nullable', 'integer', 'exists:cell,id'],
            'village_id'        => ['nullable', 'integer', 'exists:village,id'],
        ];

        // Each faculty is validated against the education_id of its own pair.
        $educationEntries = $this->input('education');
        foreach (is_array($educationEntries) ? $educationEntries : [] as $index => $entry) {
            $rules["education.$index.faculty.*"] = [
                'integer',
                'distinct',
                Rule::exists('education_faculty', 'faculty_id')
                    ->where('education_id', is_array($entry) ? ($entry['education_id'] ?? null) : null),
            ];
        }

        // Each church responsibility is validated against the department_id of its own pair.
        $departmentEntries = $this->input('department');
        foreach (is_array($departmentEntries) ? $departmentEntries : [] as $index => $entry) {
            $rules["department.$index.church_responsibility.*"] = [
                'integer',
                'distinct',
                Rule::exists('church_responsibility', 'id')
                    ->where('department_id', is_array($entry) ? ($entry['department_id'] ?? null) : null),
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'first_name.required'        => 'First name is required.',
            'last_name.required'         => 'Last name is required.',
            'talent.required'            => 'Talent is required.',
            'talent.array'                => 'Talent must be a list of talent IDs.',
            'talent.min'                  => 'Select at least one talent.',
            'talent.*.integer'            => 'Each selected talent must be a valid ID.',
            'talent.*.distinct'           => 'Duplicate talent selected.',
            'talent.*.exists'             => 'Selected talent is invalid.',
            'spiritual_gift.required'     => 'Spiritual gift is required.',
            'spiritual_gift.array'        => 'Spiritual gift must be a list of spiritual gift IDs.',
            'spiritual_gift.min'          => 'Select at least one spiritual gift.',
            'spiritual_gift.*.integer'    => 'Each selected spiritual gift must be a valid ID.',
            'spiritual_gift.*.distinct'   => 'Duplicate spiritual gift selected.',
            'spiritual_gift.*.exists'     => 'Selected spiritual gift is invalid.',
            'sex_id.required'            => 'Sex is required.',
            'sex_id.exists'              => 'Selected sex is invalid.',
            'marital_status_id.required' => 'Marital status is required.',
            'marital_status_id.exists'   => 'Selected marital status is invalid.',
            'national_id.max'            => 'National ID must not exceed 20 characters.',
            'national_id.unique'         => 'This national ID is already assigned to another member.',
            'picture.image'              => 'Picture must be an image file.',
            'picture.mimes'              => 'Picture must be a file of type: jpeg, jpg, png, webp.',
            'picture.max'                => 'Picture must not exceed 2MB.',
            'date_birthday.date_format'  => 'Date of birth must be in the format yyyy-mm-dd.',
            'date_salvation.date_format' => 'Date of salvation must be in the format yyyy-mm-dd.',
            'date_baptism.date_format'   => 'Date of baptism must be in the format yyyy-mm-dd.',
            'member_since.date_format'   => 'Member since must be in the format yyyy-mm-dd.',
            'occupation.array'            => 'Occupation must be a list of occupation IDs.',
            'occupation.*.integer'        => 'Each selected occupation must be a valid ID.',
            'occupation.*.distinct'       => 'Duplicate occupation selected.',
            'occupation.*.exists'         => 'Selected occupation is invalid.',
            'education.array'                     => 'Education must be a list of education entries.',
            'education.*.education_id.required'   => 'Each education entry must specify an education_id.',
            'education.*.education_id.exists'     => 'Selected education level is invalid.',
            'education.*.education_id.distinct'   => 'Duplicate education level selected.',
            'education.*.faculty.array'           => 'Faculty must be a list of faculty IDs.',
            'education.*.faculty.*.integer'       => 'Each selected faculty must be a valid ID.',
            'education.*.faculty.*.distinct'      => 'Duplicate faculty selected within an education entry.',
            'education.*.faculty.*.exists'        => 'Selected faculty is not valid for the chosen education level.',
            'department.array'                             => 'Department must be a list of department entries.',
            'department.*.department_id.required'          => 'Each department entry must specify a department_id.',
            'department.*.department_id.exists'            => 'Selected department is invalid.',
            'department.*.department_id.distinct'          => 'Duplicate department selected.',
            'department.*.church_responsibility.array'     => 'Church responsibility must be a list of church responsibility IDs.',
            'department.*.church_responsibility.*.integer' => 'Each selected church responsibility must be a valid ID.',
            'department.*.church_responsibility.*.distinct' => 'Duplicate church responsibility selected within a department entry.',
            'department.*.church_responsibility.*.exists'  => 'Selected church responsibility is not valid for the chosen department.',
            'email.email'                => 'Please provide a valid email address.',
            'province_id.exists'         => 'Selected province is invalid.',
            'district_id.exists'         => 'Selected district is invalid.',
            'sector_id.exists'           => 'Selected sector is invalid.',
            'cellule_id.exists'          => 'Selected cellule is invalid.',
            'cell_id.exists'             => 'Selected cell is invalid.',
            'village_id.exists'          => 'Selected village is invalid.',
        ];
    }
}
