<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmissionAdminUpdateRequest extends FormRequest
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
            'status' => ['required', 'in:submitted,pending,proses_kajur,proses_dekan,done,rejected,canceled,expired'],
            'note' => ['nullable', 'string'],
        ];

        if ($this->status == 'pending' || $this->status == 'rejected' || $this->status == 'canceled' || $this->status == 'expired') {
            $rules['note'] = ['required', 'string'];
        }

        if ($this->hasFile('file_result')) {
            $rules['file_result'] = ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:3000'];
        }

        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'file_result.max' => 'File size must be less than 3 MB.',
            'status.in' => 'The status field must be one of the following: submitted, pending, proses_kajur, proses_dekan, done, rejected, canceled, expired.',
        ];
    }
}
