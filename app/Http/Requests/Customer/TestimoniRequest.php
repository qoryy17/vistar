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
            'exam_result_id' => ['required'],
            'product_id' => ['required'],
            'testimoni' => ['required', 'string', 'min:3', 'max:250'],
            'rating' => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'testimoni.min' => 'Minimal Testimoni 3 karakter',
            'testimoni.max' => 'Minimal Testimoni 250 karakter',
            'testimoni.required' => 'Testimoni wajib di isi',
            'rating.required' => 'Rating harus wajbi di isi',
        ];
    }
}
