<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
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

            // Identitas Resmi
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16', Rule::unique('persons')->ignore($this->user()->id_person, 'id_person')],
            'no_kk' => ['required', 'string', 'size:16'],
            'nip' => ['required', 'string', 'max:255'],
            'npwp' => ['required', 'string', 'max:255'],
            
            // Pendidikan & Penempatan
            'title_education' => ['required', 'string', 'max:255'],
            'last_education' => ['required', 'in:D-III,D-IV,S-1,S-2'],
            'major_education' => ['required', 'string', 'max:255'],
            'id_work_assignment' => ['required', 'exists:work_assignments,id_work_assignment'],
            'batch' => ['required', 'in:1,2,3,Non-SPPI'],
            'employment_status' => ['required', 'in:ASN,Non-ASN'],

            // Atribut Fisik
            'clothing_size' => ['required', 'in:XS,S,M,L,XL,XXL,XXXL,XXXXL,4XL,5XL,6XL,7XL,8XL,9XL,10XL'],
            
            // PERBAIKAN DI SINI: Gunakan 'in' untuk Enum string, bukan 'between'
            'shoe_size' => ['required', 'in:35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50'],

            // Payroll (Wajib didaftarkan agar fill() di Controller bekerja)
            'payroll_bank_name' => ['required', 'in:BNI,Mandiri,BCA,BTN,BSI,BPD Bali'],
            'payroll_bank_account_number' => ['required', 'string', 'max:50'],
            'payroll_bank_account_name' => ['required', 'string', 'max:255'],

            // Info Pribadi
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['required', 'in:L,P'],
            'religion' => ['required', 'string', 'max:50'],
            'marital_status' => ['required', 'string', 'max:50'],
            'place_birthday' => ['required', 'string', 'max:255'],
            'date_birthday' => ['required', 'date'],

            // Alamat & GPS
            'province' => ['required', 'string', 'max:255'],
            'regency' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'village' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'gps_coordinates' => ['required', 'string', 'max:255'],

            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus tepat 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar di sistem.',
            'no_kk.required' => 'Nomor KK wajib diisi.',
            'no_kk.size' => 'Nomor KK harus tepat 16 digit.',
            'payroll_bank_name.required' => 'Nama bank wajib dipilih.',
            'payroll_bank_account_number.required' => 'Nomor rekening wajib diisi.',
            'payroll_bank_account_name.required' => 'Nama pemilik rekening wajib diisi.',
            'photo.max' => 'Ukuran foto maksimal adalah 2MB.',
            'photo.image' => 'File yang diunggah harus berupa gambar.',
        ];
    }
}