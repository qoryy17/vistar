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
            'jawaban_a' => ['required', 'string'],
            'jawaban_b' => ['required', 'string'],
            'jawaban_c' => ['required', 'string'],
            'jawaban_d' => ['required', 'string'],
            'jawaban_e' => ['required', 'string'],
            'poin_a' => ['required', 'numeric'],
            'poin_b' => ['required', 'numeric'],
            'poin_c' => ['required', 'numeric'],
            'poin_d' => ['required', 'numeric'],
            'poin_e' => ['required', 'numeric'],
            'berbobot' => ['required', 'boolean'],
            'kunciJawaban' => [],
            'reviewPembahasan' => ['required', 'string'],
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
            'jawaban_a.required' => 'Jawaban A wajib di isi',
            'jawaban_b.required' => 'Jawaban B wajib di isi',
            'jawaban_c.required' => 'Jawaban C wajib di isi',
            'jawaban_d.required' => 'Jawaban D wajib di isi',
            'jawaban_e.required' => 'Jawaban E wajib di isi',
            'poin_a.required' => 'Poin A wajib di isi',
            'poin_b.required' => 'Poin B wajib di isi',
            'poin_c.required' => 'Poin C wajib di isi',
            'poin_d.required' => 'Poin D wajib di isi',
            'poin_e.required' => 'Poin E wajib di isi',
            'berbobot.required' => 'Berbobot wajib dipilih',
            'kunciJawaban.required' => 'Kunci jawaban wajib di isi',
            'reviewPembahasan.required' => 'Review pembahasan wajib di isi',
        ];
    }
}
