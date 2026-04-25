<?php

namespace App\Http\Requests\Skill;

use Illuminate\Foundation\Http\FormRequest;

class StoreSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:100', 'unique:skills,name'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Category is required.',
            'category_id.integer' => 'Category ID must be an integer.',
            'category_id.exists' => 'Selected category does not exist.',

            'name.required' => 'Skill name is required.',
            'name.string' => 'Skill name must be a valid string.',
            'name.max' => 'Skill name must not exceed 100 characters.',
            'name.unique' => 'This skill already exists.',

            'is_active.boolean' => 'Active status must be true or false.',
        ];
    }
}