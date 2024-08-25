<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class ProdukTryoutRequest extends FormRequest
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
            'namaTryout' => ['required', 'string', 'max:255'],
            'keterangan' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string'],
            'kategori' => ['required', 'string'],
            'harga' => ['required', 'numeric'],
            'hargaPromo' => ['numeric'],
            'durasiUjian' => ['required', 'numeric'],
            'masaAktif' => ['required', 'numeric'],
            'thumbnail' => ['image', 'mimes:png,jpg', 'max:2048'],
            'passingGrade' => ['required', 'numeric']
        ];
    }

    public function messages(): array
    {
        return [
            'namaTryout.required' => 'Nama tryout wajib di isi',
            'namaTryout.string' => 'Nama tryout harus berupa kalimat',
            'namaTryout.max' => 'Nama tryout maksimal 255 karakter',
            'status.required' => 'Status wajib di isi',
            'status.string' => 'Status harus berupa kalimat',
            'kategori.required' => 'Kategori wajib di isi',
            'kategori.string' => 'Kategori harus berupa kalimat',
            'harga.required' => 'Harga harus di isi',
            'harga.numeric' => 'Harga harus berupa angka',
            'hargaPromo.numeric' => 'Harga Promo harus berupa angka',
            'durasiUjian.required' => 'Durasi ujian wajib di isi',
            'durasiUjian.numeric' => 'Durasi ujian harus berupa angka',
            'masaAktif.required' => 'Masa aktif wajib di isi',
            'masaAktif.numeric' => 'Masa aktif harus berupa angka',
            'thumbnail.image' => 'Thubmnail harus berupa gambar',
            'thumbnail.mimes' => 'Thumbnail harus bertipe png/jpg',
            'thumbnail.max' => 'Thumbnail maksimal 2048 MB',
            'passingGrade.required' => 'Passing grade wajib di isi',
            'passingGrade.numeric' => 'Passing grade harus berupa angka'
        ];
    }
}
