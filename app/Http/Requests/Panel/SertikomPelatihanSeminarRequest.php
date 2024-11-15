<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class SertikomPelatihanSeminarRequest extends FormRequest
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
            'Harga' => ['required', 'numeric', 'string'],
            'Deskripsi' => ['required', 'string'],
            'Instruktur' => ['string'],
            'Kategori' => ['required', 'string'],
            'TopikKeahlian' => ['required', 'string'],
            'TanggalMulai' => ['required', 'date', 'string'],
            'TanggalSelesai' => ['required', 'date', 'string'],
            'JamMulai' => ['required', 'string'],
            'JamSelesai' => ['required', 'string'],
            'Thumbnail' => ['image', 'mimes:png,jpg', 'max:2048'],
            'Publish' => ['required', 'string'],
            'LinkZoom' => ['required', 'string'],
            'LinkWA' => ['required', 'string'],
            'LinkRekaman' => ['string'],
            'Status' => ['required', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'Harga.required' => 'Harga wajib di isi !',
            'Harga.numeric' => 'Harga wajib berupa angka !',
            'Harga.string' => 'Harga wajib harus berupa string !',
            'Deskripsi.required' => 'Deskripsi wajib di isi !',
            'Deskripsi.string' => 'Deskripsi harus berupa string !',
            'Instruktur.string' => 'Instruktur harus berupa string !',
            'Kategori.required' => 'Kategori wajib di pilih !',
            'Kategori.string' => 'Kategori harus berupa string !',
            'TopikKeahlian.required' => 'Topik keahlian wajib di pilih !',
            'TopikKeahlian.string' => 'Topik keahlian harus berupa string !',
            'TanggalMulai.required' => 'Tanggal mulai wajib di isi !',
            'TanggalMulai.date' => 'Tanggal mulai harus berupa tanggal valid !',
            'TanggalMulai.string' => 'Tanggal mulai harus berupa string !',
            'TanggalSelesai.required' => 'Tanggal selesai wajib di isi !',
            'TanggalSelesai.date' => 'Tanggal selesai harus berupa tanggal valid !',
            'TanggalSelesai.string' => 'Tanggal selesai harus berupa string !',
            'JamMulai.required' => 'Jam mulai wajib di isi !',
            'JamMulai.string' => 'Jam mulai harus berupa string !',
            'JamSelesai.required' => 'Jam mulai wajib di isi !',
            'JamSelesai.string' => 'Jam mulai harus berupa string !',
            'Thumbnail.image' => 'Thumbnail harus berupa image !',
            'Thumbnail.mimes' => 'Thumbnail hanya boleh bertipe png/jpg !',
            'Thumbnail.max' => 'Thubmnail maksimal berukuran 2 MB',
            'Publish.required' => 'Publish wajib di pilih !',
            'Publish.string' => 'Publish harus berupa string !',
            'LinkZoom.required' => 'Link Zoom wajib di pilih !',
            'LinkZoom.string' => 'Link Zoom harus berupa string !',
            'LinkWA.required' => 'Link WhatsApp wajib di pilih !',
            'LinkWA.string' => 'Link WhatsApp harus berupa string !',
            'LinkRekaman.string' => 'Link Rekaman harus berupa string !',
            'Status.required' => 'Status wajib di pilih !',
            'Status.string' => 'Status harus berupa string !'
        ];
    }
}
