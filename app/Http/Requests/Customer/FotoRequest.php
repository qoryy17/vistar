<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class FotoRequest extends FormRequest
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
            'foto' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048']
        ];
    }

    public function messages(): array
    {
        return [
            'foto.required' => 'Foto tidak boleh kosong',
            'foto.image' => 'Foto hanya boleh bertipe png/jpg/jpeg',
            'foto.mimes' => 'Foto hanya boleh bertipe png/jpg/jpeg',
            'foto.max' => 'Ukuran file hanya boleh maksimal 2MB'
        ];
    }
}
