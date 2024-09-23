<?php

namespace App\Http\Requests\Customer;

use App\Enums\UserRole;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ProfilRequest extends FormRequest
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
        $rules = [
            'namaLengkap' => ['required', 'string', 'max:255'],
        ];

        if (in_array(Auth::user()->role, [UserRole::CUSTOMER->value])) {
            $rules = array_merge($rules, [
                'tanggalLahir' => ['required'],
                'jenisKelamin' => ['required'],
                'kontak' => ['required', 'max:15'],
                'alamat' => ['required', 'string', 'max:300'],
                'provinsi' => ['required', 'string', 'max:100'],
                'kotaKab' => ['required', 'string', 'max:100'],
                'kecamatan' => ['required', 'string', 'max:100'],
                'pendidikan' => ['required', 'string'],
                'jurusan' => ['required', 'string'],
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'namaLengkap.required' => 'Nama lengkap wajib di isi',
            'namaLengkap.string' => 'Nama lengkap wajib berupa kalimat',
            'namaLengkap.max' => 'Nama lengkap maksimal 300 karakter',
            'tanggalLahir.required' => 'Tanggal lahir wajib di isi',
            'jenisKelamin.required' => 'Jenis kelamin wajib di isi',
            'kontak.required' => 'Kontak wajib di isi',
            'kontak.max' => 'Kontak maksimal 155 karakter',
            'alamat.required' => 'Alamat wajib di isi',
            'alamat.string' => 'Alamat harus berupa kalimat',
            'alamat.max' => 'Alamat maksimal 300 karakter',
            'provinsi.required' => 'Provinsi wajib di isi',
            'provinsi.string' => 'Provinsi harus berupa kalimat',
            'provinsi.max' => 'Provinsi maksimal 100 karakter',
            'kotaKab.required' => 'Kota/Kabupaten wajib di isi',
            'kotaKab.string' => 'Kota/Kabupaten harus berupa kalimat',
            'kotaKab.max' => 'Kota/Kabupaten maksimal 100 karakter',
            'kecamatan.required' => 'Kecamatan wajib di isi',
            'kecamatan.string' => 'Kecamatan harus berupa kalimat',
            'kecamatan.max' => 'Kecamatan maksimal 100 karakter',
            'pendidikan.required' => 'Pendidikan wajib di isi',
            'pendidikan.string' => 'Pendidikan harus berupa kalimat',
            'jurusan.required' => 'Jurusan wajib di isi',
            'jurusan.string' => 'Jurusan harus berupa kalimat',
        ];
    }
}
