<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TambahKuitansiRequest extends FormRequest
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
            'guna_byr1' => 'required',
            'guna_byr2' => 'nullable',
            'guna_byr3' => 'nullable',
            'jumlah' => 'required',
            'pembayaran' => 'required',
            'penerima' => 'required',
            'cara_bayar' => 'required',
            'created_at' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama harus diisi',
            'guna_byr1.required' => 'Guna Bayar harus diisi',
            'jumlah.required' => 'Jumlah harus diisi',
            'pembayaran.required' => 'Pembayaran harus diisi',
            'penerima.required' => 'Penerima harus diisi',
            'cara_bayar.required' => 'Cara Bayar harus diisi',
            'created_at.required' => 'Tanggal Kuitansi wajib diisi!',
            'created_at.date' => 'Tanggal Kuitansi tidak valid!',
        ];
    }
}
