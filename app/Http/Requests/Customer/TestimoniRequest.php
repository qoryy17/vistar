<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class TestimoniRequest extends FormRequest
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
            'testimoni' => ['required', 'string'],
            'rating' => ['required', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'testimoni.required' => 'Testimoni wajib di isi',
            'testimoni.required' => 'Testimoni wajib di isi',
            'rating.required' => 'Rating harus wajbi di isi'
        ];
    }
}
