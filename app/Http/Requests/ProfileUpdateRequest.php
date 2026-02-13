<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'nullable', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                Rule::unique(User::class)->ignore($this->user()->id_user, 'id_user')
            ],

            // Field lainnya tetap sama sesuai tabel persons
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['nullable', 'string', 'size:16'],
            'no_kk' => ['nullable', 'string', 'size:16'],
            'npwp' => ['nullable', 'string', 'max:255'],
            'title_education' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:L,P'],
            'religion' => ['nullable', 'string', 'max:50'],
            'marital_status' => ['nullable', 'string', 'max:50'],
            'place_birthday' => ['nullable', 'string', 'max:255'],
            'date_birthday' => ['nullable', 'date'],
            'province' => ['nullable', 'string', 'max:255'],
            'regency' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'village' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'gps_coordinates' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    /**
     * Custom error messages (Opsional agar lebih user-friendly)
     */
    public function messages(): array
    {
        return [
            'nik.size' => 'NIK harus tepat 16 digit.',
            'no_kk.size' => 'Nomor KK harus tepat 16 digit.',
            'photo.max' => 'Ukuran foto maksimal adalah 2MB.',
            'photo.image' => 'File yang diunggah harus berupa gambar.',
        ];
    }
}