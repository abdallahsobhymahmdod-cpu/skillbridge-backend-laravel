<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $userId],
            'bio' => ['nullable', 'string'],
            'avatar_url' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a valid string.',
            'name.max' => 'Name must not exceed 255 characters.',

            'email.required' => 'Email is required.',
            'email.string' => 'Email must be a valid string.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email must not exceed 255 characters.',
            'email.unique' => 'This email is already taken.',

            'bio.string' => 'Bio must be a valid string.',

            'avatar_url.string' => 'Avatar URL must be a valid string.',
            'avatar_url.max' => 'Avatar URL must not exceed 255 characters.',

            'country.string' => 'Country must be a valid string.',
            'country.max' => 'Country must not exceed 100 characters.',
        ];
    }
}