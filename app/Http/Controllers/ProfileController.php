<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\WorkAssignment;
use App\Models\RefPosition;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function show(Request $request): View
    {
        $user = User::with(['person.socialMedia', 'person.position', 'person.workAssignment.sppgUnit'])->find(Auth::id());

        return view('profile.show', [
            'user' => $user,
        ]);
    }

    /**
     * Display the form for editing the profile.
     */
    public function edit(Request $request): View
    {
        $workAssignments = WorkAssignment::with('sppgUnit')->get();
        $positions = RefPosition::all();
        return view('profile.edit', [
            'user' => $request->user(),
            'workAssignments' => $workAssignments,
            'positions' => $positions
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();

        // 1. Update Akun (Phone)
        // if ($request->filled('phone')) {
        //     $user->phone = $request->phone;
        //     $user->save();
        // }
        $user = $request->user();
        $user->update([
            'phone' => $request->phone,
        ]);

        $person = $user->person;

        if ($person) {
            // 2. Handle Foto
            if ($request->hasFile('photo')) {
                if ($person->photo) {
                    Storage::disk('public')->delete($person->photo);
                }
                $path = $request->file('photo')->store('photos', 'public');
                $person->photo = $path;
            }

            // --- PERBAIKAN: Handle Opsi 'Belum Penugasan' ---
            $workAssignment = $request->filled('id_work_assignment') ? $request->id_work_assignment : null;
            $positionId = $request->filled('id_ref_position') ? $request->id_ref_position : null;

            // 3. Update Person dengan Field Baru
            $person->fill([
                'name'              => $request->name,
                'nik'               => $request->nik,
                'no_kk'             => $request->no_kk,
                'nip'               => $request->nip,
                'npwp'              => $request->npwp,
                'title_education'   => $request->title_education,
                'last_education'    => $request->last_education,
                'major_education'   => $request->major_education,
                'id_work_assignment' => $workAssignment,
                'id_ref_position'   => $positionId,
                'batch'             => $request->batch,
                'employment_status' => $request->employment_status,
                'clothing_size'     => $request->clothing_size,
                'shoe_size'         => $request->shoe_size,
                'payroll_bank_name' => $request->payroll_bank_name,
                'payroll_bank_account_number' => $request->payroll_bank_account_number,
                'payroll_bank_account_name'   => $request->payroll_bank_account_name,
                'gender'            => $request->gender,
                'religion'          => $request->religion,
                'marital_status'    => $request->marital_status,
                'place_birthday'    => $request->place_birthday,
                'date_birthday'     => $request->date_birthday,

                // Field BPJS
                'no_bpjs_kes'       => $request->no_bpjs_kes,
                'no_bpjs_tk'        => $request->no_bpjs_tk,

                // Alamat KTP
                'province_ktp'      => $request->province_ktp,
                'regency_ktp'       => $request->regency_ktp,
                'district_ktp'      => $request->district_ktp,
                'village_ktp'       => $request->village_ktp,
                'address_ktp'       => $request->address_ktp,

                // Alamat Domisili
                'province_domicile' => $request->province_domicile,
                'regency_domicile'  => $request->regency_domicile,
                'district_domicile' => $request->district_domicile,
                'village_domicile'  => $request->village_domicile,
                'address_domicile'  => $request->address_domicile,

                // Field GPS
                'latitude_gps_domicile'  => $request->latitude_gps_domicile,
                'longitude_gps_domicile' => $request->longitude_gps_domicile,

                'age' => $request->filled('date_birthday')
                    ? Carbon::parse($request->date_birthday)->age
                    : $person->age,
            ]);

            $person->save();

            // 4. Update Social Media
            $person->socialMedia()->updateOrCreate(
                ['socialable_id' => $person->id_person, 'socialable_type' => get_class($person)],
                [
                    'facebook_url'  => $request->facebook_url,
                    'instagram_url' => $request->instagram_url,
                    'tiktok_url'    => $request->tiktok_url,
                ]
            );
        }

        return Redirect::route('profile.show')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
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
