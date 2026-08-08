<?php

namespace App\Http\Requests;

use App\Enums\RoleType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterMemberRequest extends FormRequest
{
    /**
     * ID-list filters: accepted as repeated query params (sex_id[]=1&sex_id[]=2),
     * a comma-separated string (sex_id=1,2), or a bare single ID (sex_id=1).
     */
    private const array ID_LIST_FIELDS = [
        'sex_id', 'marital_status_id',
        'province_id', 'district_id', 'sector_id', 'cellule_id', 'cell_id', 'village_id',
        'occupation_id', 'talent_id', 'spiritual_gift_id',
        'education_id', 'faculty_id', 'department_id', 'church_responsibility_id',
        'family_id',
    ];

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeBoolean('employed');

        foreach (self::ID_LIST_FIELDS as $field) {
            $this->normalizeIdList($field);
        }
    }

    /**
     * Query strings have no native boolean type, so clients send the literal
     * string "true"/"false" (or "1"/"0"/"on"/"off"), which Laravel's strict
     * `boolean` rule rejects. Coerce to a real bool before validation.
     */
    private function normalizeBoolean(string $field): void
    {
        $value = $this->query($field);

        if (is_string($value)) {
            $normalized = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            if ($normalized !== null) {
                $this->merge([$field => $normalized]);
            }
        }
    }

    private function normalizeIdList(string $field): void
    {
        $value = $this->query($field);

        if ($value === null) {
            return;
        }

        if (is_string($value)) {
            $trimmed = trim($value);

            if ($trimmed === '') {
                $this->merge([$field => null]);
                return;
            }

            $value = str_contains($trimmed, ',')
                ? array_map('trim', explode(',', $trimmed))
                : [$trimmed];
        }

        $this->merge([$field => array_values((array) $value)]);
    }

    public function rules(): array
    {
        return [
            // ── Identity / personal info ───────────────────────────────────────
            'first_name'   => ['nullable', 'string', 'max:100'],
            'last_name'    => ['nullable', 'string', 'max:100'],
            'fathers_name' => ['nullable', 'string', 'max:150'],
            'mothers_name' => ['nullable', 'string', 'max:150'],
            'national_id'  => ['nullable', 'string', 'max:20'],

            'sex_id'              => ['nullable', 'array'],
            'sex_id.*'            => ['integer', 'exists:sex,id'],
            'marital_status_id'   => ['nullable', 'array'],
            'marital_status_id.*' => ['integer', 'exists:marital_status,id'],

            'age_min' => ['nullable', 'integer', 'min:0'],
            'age_max' => ['nullable', 'integer', 'min:0', 'gte:age_min'],

            // ── Key dates (range) ──────────────────────────────────────────────
            'date_birthday_from'  => ['nullable', 'date_format:Y-m-d'],
            'date_birthday_to'    => ['nullable', 'date_format:Y-m-d', 'after_or_equal:date_birthday_from'],
            'date_salvation_from' => ['nullable', 'date_format:Y-m-d'],
            'date_salvation_to'   => ['nullable', 'date_format:Y-m-d', 'after_or_equal:date_salvation_from'],
            'date_baptism_from'   => ['nullable', 'date_format:Y-m-d'],
            'date_baptism_to'     => ['nullable', 'date_format:Y-m-d', 'after_or_equal:date_baptism_from'],
            'member_since_from'   => ['nullable', 'date_format:Y-m-d'],
            'member_since_to'     => ['nullable', 'date_format:Y-m-d', 'after_or_equal:member_since_from'],

            // ── Employment ───────────────────────────────────────────────────────
            'employed' => ['nullable', 'boolean'],

            // ── Geographic hierarchy ─────────────────────────────────────────────
            'province_id'   => ['nullable', 'array'],
            'province_id.*' => ['integer', 'exists:province,id'],
            'district_id'   => ['nullable', 'array'],
            'district_id.*' => ['integer', 'exists:district,id'],
            'sector_id'     => ['nullable', 'array'],
            'sector_id.*'   => ['integer', 'exists:sector,id'],
            'cellule_id'    => ['nullable', 'array'],
            'cellule_id.*'  => ['integer', 'exists:cellule,id'],
            'cell_id'       => ['nullable', 'array'],
            'cell_id.*'     => ['integer', 'exists:cell,id'],
            'village_id'    => ['nullable', 'array'],
            'village_id.*'  => ['integer', 'exists:village,id'],

            // ── Many-to-many relations ───────────────────────────────────────────
            'occupation_id'       => ['nullable', 'array'],
            'occupation_id.*'     => ['integer', 'exists:occupation,id'],
            'talent_id'           => ['nullable', 'array'],
            'talent_id.*'         => ['integer', 'exists:talent,id'],
            'spiritual_gift_id'   => ['nullable', 'array'],
            'spiritual_gift_id.*' => ['integer', 'exists:spiritual_gift,id'],
            'education_id'        => ['nullable', 'array'],
            'education_id.*'      => ['integer', 'exists:education,id'],
            'faculty_id'          => ['nullable', 'array'],
            'faculty_id.*'        => ['integer', 'exists:faculty,id'],
            'department_id'       => ['nullable', 'array'],
            'department_id.*'     => ['integer', 'exists:department,id'],
            'church_responsibility_id'   => ['nullable', 'array'],
            'church_responsibility_id.*' => ['integer', 'exists:church_responsibility,id'],

            // ── Family relations ──────────────────────────────────────────────────
            'family_id'        => ['nullable', 'array'],
            'family_id.*'      => ['integer', 'exists:family,id'],
            'family_role_type' => ['nullable', Rule::enum(RoleType::class)],

            // ── Pagination ───────────────────────────────────────────────────────
            'page'     => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'age_max.gte'                       => 'Age max must be greater than or equal to age min.',
            'date_birthday_to.after_or_equal'   => 'Date of birth "to" must be on or after "from".',
            'date_salvation_to.after_or_equal'  => 'Date of salvation "to" must be on or after "from".',
            'date_baptism_to.after_or_equal'    => 'Date of baptism "to" must be on or after "from".',
            'member_since_to.after_or_equal'    => 'Member since "to" must be on or after "from".',
            'sex_id.*.exists'                   => 'Selected sex is invalid.',
            'marital_status_id.*.exists'        => 'Selected marital status is invalid.',
            'province_id.*.exists'              => 'Selected province is invalid.',
            'district_id.*.exists'              => 'Selected district is invalid.',
            'sector_id.*.exists'                => 'Selected sector is invalid.',
            'cellule_id.*.exists'               => 'Selected cellule is invalid.',
            'cell_id.*.exists'                  => 'Selected cell is invalid.',
            'village_id.*.exists'               => 'Selected village is invalid.',
            'occupation_id.*.exists'            => 'Selected occupation is invalid.',
            'talent_id.*.exists'                => 'Selected talent is invalid.',
            'spiritual_gift_id.*.exists'        => 'Selected spiritual gift is invalid.',
            'education_id.*.exists'             => 'Selected education level is invalid.',
            'faculty_id.*.exists'               => 'Selected faculty is invalid.',
            'department_id.*.exists'            => 'Selected department is invalid.',
            'church_responsibility_id.*.exists' => 'Selected church responsibility is invalid.',
            'family_id.*.exists'                => 'Selected family is invalid.',
            'family_role_type.enum'             => 'Selected family role type is invalid.',
        ];
    }
}
