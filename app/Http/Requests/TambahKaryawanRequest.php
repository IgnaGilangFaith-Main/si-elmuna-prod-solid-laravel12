<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TambahKaryawanRequest extends FormRequest
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
            'nama' => 'required',
            'jabatan' => 'required',
            'foto_karyawan' => 'required|image|mimes:jpg,jpeg,png|max:10240',
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama harus diisi!',
            'jabatan.required' => 'Jabatan harus diisi!',
            'foto_karyawan.required' => 'Tanda tangan harus diisi!',
            'foto_karyawan.image' => 'Tanda tangan harus berupa gambar!',
            'foto_karyawan.mimes' => 'Ekstensi yang diperbolehkan hanya untuk format jpeg, jpg, dan png!',
            'foto_karyawan.max' => 'Ukuran gambar tidak boleh lebih dari :max MB!',

        ];
    }
}
