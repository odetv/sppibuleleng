<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\WorkAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonController extends Controller
{
    /**
     * Tampilkan form pengisian profil.
     */
    public function create()
    {
        if (Auth::user()->id_person) {
            return redirect()->route('dashboard');
        }

        $workAssignments = WorkAssignment::with(['sppgUnit', 'decree'])->get();
        return view('profile.complete', compact('workAssignments'));
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
            'village'           => 'required|string',
            'district'          => 'required|string',
            'regency'           => 'required|string',
            'province'          => 'required|string',
            'address'           => 'required|string',
            'gps_coordinates'   => 'required|string',
            'id_work_assignment' => 'nullable|exists:work_assignments,id_work_assignment',
        ]);

        // Hitung Umur
        $birthDate = Carbon::parse($request->date_birthday);
        $age = $birthDate->age;

        $data = $request->all();
        $data['age'] = $age;

        // Foto
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $data['photo'] = $path;
        }

        // Simpan ke database
        $person = Person::create($data);

        // Hubungkan ke User
        auth()->user()->update([
            'id_person' => $person->id_person
        ]);

        return redirect()->route('dashboard')->with('success', 'Profil lengkap berhasil disimpan!');
    }
}
