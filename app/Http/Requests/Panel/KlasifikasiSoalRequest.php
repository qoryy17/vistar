<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class KlasifikasiSoalRequest extends FormRequest
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
            'alias' => ['required', 'string'],
            'judul' => ['required', 'string', 'max:255'],
            'passingGrade' => ['required', 'numeric'],
            'ordering' => ['required', 'numeric'],
            'aktif' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'alias.required' => 'Alias wajib di isi',
            'alias.string' => 'Judul harus berupa kalimat',
            'judul.required' => 'Judul wajib di isi',
            'judul.string' => 'Judul harus berupa kalimat',
            'judul.max' => 'Judul maksimal 255 karakter',
            'passingGrade.required' => 'Passing grade harus di isi',
            'passingGrade.numeric' => 'Passing grade harus berupa angka',
            'ordering.required' => 'Urutan harus di isi',
            'ordering.numeric' => 'Urutan harus berupa angka',
            'aktif.required' => 'Aktif wajib di isi',
        ];
    }
}
