<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class KategoriRequest extends FormRequest
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
            'judul' => ['required', 'string', 'max:255'],
            'status' => ['required'],
            'aktif' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul wajib di isi',
            'judul.string' => 'Judul harus berupa kalimat',
            'judul.max' => 'Judul maksimal 255 karakter',
            'status.required' => 'Status wajib di isi',
            'aktif.required' => 'Aktif wajib di isi'
        ];
    }
}
