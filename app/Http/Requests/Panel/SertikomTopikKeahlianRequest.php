<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class SertikomTopikKeahlianRequest extends FormRequest
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
            'TopikKeahlian' => ['required', 'string', 'max:255'],
            'Deskripsi' => ['required', 'string'],
            'Publish' => ['required', 'string', 'max:1']
        ];
    }

    public function messages(): array
    {
        return [
            'TopikKeahlian.required' => 'Topik Keahlian wajib di isi !',
            'TopikKeahlian.string' => 'Topik Keahlian harus berupa kalimat !',
            'TopikKeahlian.max' => 'Topik Keahlian maksimal 255 karakter !',
            'Deskripsi.required' => 'Deskripsi wajib di isi !',
            'Deskripsi.string' => 'Deskripsi harus berupa kalimat !',
            'Publish.required' => 'Publish wajib di pilih !',
            'Publish.string' => 'Publish harus berupa kalimat !',
            'Publish.max' => 'Publish maksimal 1 karakter !',
        ];
    }
}
