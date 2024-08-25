<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'namaLengkap' => ['required'],
            'email' => ['required', 'email'],
            // 'password' => [
            //     'required',
            //     'min:8',
            //     'string',
            //     'regex:/[A-Z]/',       // must contain at least one uppercase letter
            //     'regex:/[a-z]/',       // must contain at least one lowercase letter
            //     'regex:/[0-9]/',       // must contain at least one digit
            //     'regex:/[@$!%*?&]/'   // must contain a special character
            // ],
            'role' => ['required'],
            'blokir' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'namaLengkap.required' => 'Nama lengkap wajib di isi',
            'email' => 'Email wajib di isi',
            'email.email' => 'Email harus valid',
            'role.required' => 'Role wajib di isi',
            'blokir.required' => 'Blokir wajib di isi',
            'password.min' => 'Password harus mengandung 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf kapital, angka dan karakter',
        ];
    }
}
