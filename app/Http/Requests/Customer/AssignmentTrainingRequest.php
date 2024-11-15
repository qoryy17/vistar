<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentTrainingRequest extends FormRequest
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
        return [
            'preTest' => ['required', 'string'],
            'postTest' => ['required', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'preTest.required' => 'Link Pre Test tidak di isi',
            'preTest.string' => 'Link Pre Test harus berupa string valid',
            'postTest.required' => 'Link Post Test wajib di isi',
            'postTest.string' => 'Link Post Test harus berupa string valid'
        ];
    }
}
