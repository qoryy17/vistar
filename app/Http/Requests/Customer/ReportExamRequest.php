<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class ReportExamRequest extends FormRequest
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
            'idProduk' => ['required', 'string'],
            'idSoal' => ['required', 'string'],
            'deskripsi' => ['required', 'string', 'min:10', 'max:1000'],
            'screenshot' => ['required', 'image', 'max:2048', 'mimes:png,jpg,jpeg'],
        ];
    }

    public function messages(): array
    {
        return [
            'idProduk.required' => 'ID Produk wajib di isi',
            'idSoal.required' => 'ID Soal wajib di isi',
            'deskripsi.required' => 'Deskripsi permasalahan wajib di isi',
            'deskripsi.min' => 'Deskripsi permasalahan minimal 10 karakter',
            'deskripsi.max' => 'Deskripsi permasalahan maksimal 1000 karakter',
            'screenshot.required' => 'Screenhost wajib di isi',
            'screenshot.image' => 'Screenhost hanya boleh gambar',
            'screenshot.mimes' => 'Screenhost hanya boleh bertipe png/jpg/jpeg',
            'screenshot.max' => 'Screenshot maksimal berukuran 2MB',
        ];
    }
}
