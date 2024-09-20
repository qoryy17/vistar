<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class ParticipantRequest extends FormRequest
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
            'produk' => ['required', 'string'],
            'customer' => ['required', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'produk.required' => 'Produk tryout wajib dipilih !',
            'produk.string' => 'Produk tryout wajib berupa kalimat !',
            'customer.required' => 'Customer Partisipan wajib dipilih !',
            'customer.string' => 'Customer Partisipan wajib berupa kalimat !',
        ];
    }
}
