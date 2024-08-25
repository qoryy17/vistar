<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class TryoutGratisRequest extends FormRequest
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
            'buktiShare' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'buktiFollow' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'informasi' => ['required', 'string'],
            'alasan' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'buktiShare.required' => 'Bukti share wajib diunggah',
            'buktiShare.image' => 'Bukti harus berupa foto/gambar',
            'buktiShare.mimes' => 'Bukti share hanya boleh bertipe png/jpg/jpeg',
            'buktiShare.max' => 'Bukti share maksimal 2MB',
            'buktiFollow.required' => 'Bukti share wajib diunggah',
            'buktiFollow.image' => 'Bukti harus berupa foto/gambar',
            'buktiFollow.mimes' => 'Bukti share hanya boleh bertipe png/jpg/jpeg',
            'buktiFollow.max' => 'Bukti share maksimal 2MB',
            'informasi.required' => 'Informasi wajib di isi',
            'informasi.string' => 'Informasi harus berupa kalimat',
            'alasan.required' => 'Alasan wajib di isi',
            'alasan.string' => 'Alasan harus berupa kalimat',
        ];
    }
}
