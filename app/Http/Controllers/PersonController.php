<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PersonController extends Controller
{
    /**
     * Tampilkan form pengisian profil (NIK, No KK, Nama, dsb).
     */
    public function create()
    {
        // Pastikan user belum mengisi profil sebelumnya
        if (Auth::user()->id_person) {
            return redirect()->route('dashboard');
        }

        return view('profile.complete');
    }

    /**
     * Simpan data ke tabel persons dan hubungkan ke tabel users.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|size:16|unique:persons,nik',
            'no_kk' => 'required|size:16',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'date_birthday' => 'required|date',
            // 'age' dihapus dari validasi karena kita hitung sendiri
            'religion' => 'required|string',
            'marital_status' => 'required|string',
            'village' => 'required|string',
            'district' => 'required|string',
            'regency' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        // HITUNG UMUR OTOMATIS
        $birthDate = Carbon::parse($request->date_birthday);
        $age = $birthDate->age; 

        $data = $request->all();
        $data['age'] = $age; // Masukkan hasil hitungan ke array data

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $person = Person::create($data);

        // Update id_person di tabel users
        auth()->user()->update([
            'id_person' => $person->id_person
        ]);

        return redirect()->route('dashboard')->with('success', 'Profil berhasil disimpan!');
    }
}
