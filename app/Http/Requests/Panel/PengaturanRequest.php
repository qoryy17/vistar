<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class PengaturanRequest extends FormRequest
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
            'namaBisnis' => ['required', 'string', 'max:255'],
            'tagline' => ['required', 'string', 'max:500'],
            'perusahaan' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'email' => ['required', 'email', 'string'],
            'facebook' => ['required', 'string', 'url:http,https'],
            'instagram' => ['required', 'string', 'url:http,https'],
            'kontak' => ['required', 'numeric'],
            'logo' => ['image', 'mimes:png,jpg', 'max:2048'],
            'metaAuthor' => ['required', 'string', 'max:255'],
            'metaKeyword' => ['required', 'string'],
            'metaDescription' => ['required', 'string'],
        ];
    }
}
