<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class SoalRequest extends FormRequest
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
            'klasifikasi' => ['required'],
            'soal' => ['required'],
            'gambar' => ['image', 'mimes:png,jpg', 'max:2048'],
            'jawabanA' => ['required'],
            'jawabanB' => ['required'],
            'jawabanC' => ['required'],
            'jawabanD' => ['required'],
            'kunciJawaban' => ['required'],
            'reviewPembahasan' => ['required'],
            'poin' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'klasifikasi.required' => 'Klasifikasi wajib di isi',
            'soal.required' => 'Soal wajib di isi',
            'gambar.image' => 'Gambar harus berupa gambar',
            'gambar.mimes' => 'Gambar harus bertipe png/jpg',
            'gambar.max' => 'Gambar maksimal 2048 MB',
            'jawabanA.required' => 'Jawaban A wajib di isi',
            'jawabanB.required' => 'Jawaban B wajib di isi',
            'jawabanC.required' => 'Jawaban C wajib di isi',
            'jawabanD.required' => 'Jawaban D wajib di isi',
            'kunciJawaban.required' => 'Kunci jawaban wajib di isi',
            'reviewPembahasan.required' => 'Review pembahasan wajib di isi',
            'poin.required' => 'Poin wajib di isi',
        ];
    }
}
