<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }
    
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();
        
        // 1. Update data Akun User (Hanya nomor telepon yang diizinkan berubah)
        if ($request->filled('phone')) {
            $user->phone = $request->phone;
            $user->save();
        }

        // 2. Ambil model Person melalui relasi BelongsTo
        $person = $user->person;

        if ($person) {
            // Handle Foto Profil
            if ($request->hasFile('photo')) {
                if ($person->photo) {
                    Storage::disk('public')->delete($person->photo);
                }
                $path = $request->file('photo')->store('photos', 'public');
                $person->photo = $path;
            }

            // 3. Update data di tabel persons
            $person->fill([
                'name' => $request->name,
                'nik' => $request->nik,
                'no_kk' => $request->no_kk,
                'npwp' => $request->npwp,
                'title_education' => $request->title_education,
                'gender' => $request->gender,
                'religion' => $request->religion,
                'marital_status' => $request->marital_status,
                'place_birthday' => $request->place_birthday,
                'date_birthday' => $request->date_birthday,
                'province' => $request->province,
                'regency' => $request->regency,
                'district' => $request->district,
                'village' => $request->village,
                'address' => $request->address,
                'gps_coordinates' => $request->gps_coordinates,
                'age' => $request->filled('date_birthday') ? \Carbon\Carbon::parse($request->date_birthday)->age : $person->age,
            ]);

            $person->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}