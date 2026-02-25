<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\RefPosition;
use App\Models\WorkAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PersonController extends Controller
{
    /**
     * Tampilkan form pengisian profil.
     */
    public function create()
    {
        // Jika user sudah punya profil, lempar ke dashboard
        if (Auth::user()->id_person) {
            return redirect()->route('dashboard');
        }

        // Ambil variabel yang dibutuhkan oleh view complete.blade.php
        $user = Auth::user();
        $workAssignments = WorkAssignment::with(['sppgUnit', 'decree'])->get();
        $positions = RefPosition::all(); // Tambahkan pengambilan data jabatan

        return view('profile.complete', compact('user', 'workAssignments', 'positions'));
    }

    /**
     * Simpan data ke tabel persons secara lengkap (Strict Mode).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik'               => 'required|digits:16|unique:persons,nik',
            'no_kk'             => 'required|digits:16',
            'nip'               => 'required|string|max:25',
            'npwp'              => 'required|string|max:25',
            'name'              => 'required|string|max:255',
            'photo'             => 'required|image|max:2048',
            'title_education'   => 'required|string|max:50',
            'last_education'    => 'required|in:D-III,D-IV,S-1',
            'major_education'   => 'required|string|max:255',
            'clothing_size'     => 'required|in:XS,S,M,L,XL,XXL,XXXL,XXXXL,4XL,5XL,6XL,7XL,8XL,9XL,10XL',
            'shoe_size'         => 'required|in:35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50',
            'batch'             => 'required|in:1,2,3,Non-SPPI',
            'employment_status' => 'required|in:ASN,Non-ASN',
            'payroll_bank_name'           => 'required|in:BNI,Mandiri,BCA,BTN,BSI,BPD Bali',
            'payroll_bank_account_number' => 'required|string|max:50',
            'payroll_bank_account_name'   => 'required|string|max:255',
            'gender'            => 'required|in:L,P',
            'place_birthday'    => 'required|string|max:255',
            'date_birthday'     => 'required|date',
            'religion'          => 'required|string',
            'marital_status'    => 'required|string',

            'no_bpjs_kes'       => 'nullable|string|max:20',
            'no_bpjs_tk'        => 'nullable|string|max:20',

            'village_ktp'       => 'required|string|max:255',
            'district_ktp'      => 'required|string|max:255',
            'regency_ktp'       => 'required|string|max:255',
            'province_ktp'      => 'required|string|max:255',
            'address_ktp'       => 'required|string',

            'village_domicile'  => 'required|string|max:255',
            'district_domicile' => 'required|string|max:255',
            'regency_domicile'  => 'required|string|max:255',
            'province_domicile' => 'required|string|max:255',
            'address_domicile'  => 'required|string',

            'latitude_gps_domicile'  => 'required|numeric',
            'longitude_gps_domicile' => 'required|numeric',

            // Validasi id_work_assignment (mendukung 'none')
            'id_work_assignment' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== 'none' && !DB::table('work_assignments')->where('id_work_assignment', $value)->exists()) {
                        $fail('Unit Penugasan yang dipilih tidak valid.');
                    }
                }
            ],

            // TAMBAHAN: Validasi id_ref_position (mendukung 'none')
            'id_ref_position' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== 'none' && !DB::table('ref_positions')->where('id_ref_position', $value)->exists()) {
                        $fail('Jabatan yang dipilih tidak valid.');
                    }
                }
            ],

            'facebook_url'       => 'nullable|url',
            'instagram_url'      => 'nullable|url',
            'tiktok_url'         => 'nullable|url',
        ]);

        return DB::transaction(function () use ($request) {

            $birthDate = Carbon::parse($request->date_birthday);
            $age = $birthDate->age;

            $dataPerson = $request->except(['photo', 'facebook_url', 'instagram_url', 'tiktok_url']);
            $dataPerson['age'] = $age;

            // TAMBAHKAN INI: Beri nilai string kosong agar MySQL tidak error
            $dataPerson['photo'] = '';

            if ($request->id_work_assignment === 'none') {
                $dataPerson['id_work_assignment'] = null;
            }

            if ($request->id_ref_position === 'none') {
                $dataPerson['id_ref_position'] = null;
            }

            // 1. Simpan Person (sekarang kolom photo terisi string kosong '')
            $person = Person::create($dataPerson);

            // 2. Handle Foto
            if ($request->hasFile('photo')) {
                $folderHash = md5($person->id_person . config('app.key'));
                $path = $request->file('photo')->store("persons/{$folderHash}/photos", 'public');

                // Update dengan path asli
                $person->update(['photo' => $path]);
            }

            // 3. Simpan Sosial Media
            $person->socialMedia()->create([
                'facebook_url'  => $request->facebook_url,
                'instagram_url' => $request->instagram_url,
                'tiktok_url'    => $request->tiktok_url,
            ]);

            // 4. Hubungkan User dengan Person
            auth()->user()->update([
                'id_person' => $person->id_person
            ]);

            return redirect()->route('dashboard')->with('success', 'Profil lengkap berhasil disimpan!');
        });
    }
}
