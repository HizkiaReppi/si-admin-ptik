<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AdminStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'fullname' => ['required', 'string', 'max:255', 'min:2', 'regex:/^[a-zA-Z\s]*$/'],
            'username' => ['required', 'string', 'max:255', 'min:2', 'unique:' . User::class, 'regex:/^[a-zA-Z][a-zA-Z0-9]*$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'min:4', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],
            'foto' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ];

        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'fullname.regex' => 'The name field must be alphabet.',
            'username.unique' => 'The username field must be unique.',
            'username.regex' => 'The username field must be alphabet and number.',
            'password.regex' => 'The password field must be should be at least 8 characters, one uppercase, one lowercase, one number and one special character.',
            'email.unique' => 'The email field must be unique.',
            'email.regex' => 'The email field must be email.',
        ];
    }
}
