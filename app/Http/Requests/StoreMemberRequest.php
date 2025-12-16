<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s\.,\-]+$/',
            'nik' => 'required|string|size:16|unique:members,nik|regex:/^[0-9]+$/',
            'phone' => 'required|string|max:20|regex:/^[0-9\+\-\s]+$/',
            'address' => 'required|string|max:500|min:10',
            'business_type' => 'required|in:pertanian,peternakan,perikanan,umkm',
            'experience' => 'required|in:baru,2-5_tahun,5+_tahun',
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'Nama hanya boleh mengandung huruf, spasi, titik, koma, dan tanda hubung.',
            'nik.regex' => 'NIK hanya boleh mengandung angka.',
            'nik.size' => 'NIK harus 16 digit.',
            'phone.regex' => 'Nomor telepon tidak valid.',
            'address.min' => 'Alamat minimal 10 karakter.',
        ];
    }

    public function sanitize(): array
    {
        return [
            'name' => strip_tags($this->name),
            'phone' => preg_replace('/[^0-9\+\-]/', '', $this->phone),
            'address' => strip_tags($this->address),
        ];
    }
}
