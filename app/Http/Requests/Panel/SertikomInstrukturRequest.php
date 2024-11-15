<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class SertikomInstrukturRequest extends FormRequest
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
            'Instruktur' => ['required', 'string', 'max:255'],
            'Keahlian' => ['required', 'string'],
            'Deskripsi' => ['required', 'string'],
            'Publish' => ['required', 'string', 'max:1']
        ];
    }

    public function messages(): array
    {
        return [
            'Instruktur.required' => 'Instruktur wajib di isi !',
            'Instruktur.string' => 'Instruktur harus berupa kalimat !',
            'Instruktur.max' => 'Instruktur maksimal 255 karakter !',
            'Keahlian.required' => 'Keahlian wajib di isi !',
            'Keahlian.string' => 'Keahlian harus berupa kalimat !',
            'Deskripsi.required' => 'Deskripsi wajib di isi !',
            'Deskripsi.string' => 'Deskripsi harus berupa kalimat !',
            'Publish.required' => 'Publish wajib di pilih !',
            'Publish.string' => 'Publish harus berupa kalimat !',
            'Publish.max' => 'Publish maksimal 1 karakter !',
        ];
    }
}
