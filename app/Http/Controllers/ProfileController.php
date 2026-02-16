<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\WorkAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function show(Request $request): View
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the form for editing the profile.
     */
    public function edit(Request $request): View
    {
        $workAssignments = WorkAssignment::with('sppgUnit')->get();
        return view('profile.edit', [
            'user' => $request->user(),
            'workAssignments' => $workAssignments
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
{
    $user = Auth::user();

    // 1. Update Akun (Phone)
    if ($request->filled('phone')) {
        $user->phone = $request->phone;
        $user->save();
    }

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

        // 3. Update Person (Sertakan Payroll)
        $person->fill([
            'name'              => $request->name,
            'nik'               => $request->nik,
            'no_kk'             => $request->no_kk,
            'nip'               => $request->nip,
            'npwp'              => $request->npwp,
            'title_education'   => $request->title_education,
            'last_education'    => $request->last_education,
            'major_education'   => $request->major_education,
            'id_work_assignment'=> $request->id_work_assignment,
            'batch'             => $request->batch,
            'employment_status' => $request->employment_status,
            'clothing_size'     => $request->clothing_size,
            'shoe_size'         => $request->shoe_size,
            'payroll_bank_name'           => $request->payroll_bank_name,
            'payroll_bank_account_number' => $request->payroll_bank_account_number,
            'payroll_bank_account_name'   => $request->payroll_bank_account_name,
            'gender'            => $request->gender,
            'religion'          => $request->religion,
            'marital_status'    => $request->marital_status,
            'place_birthday'    => $request->place_birthday,
            'date_birthday'     => $request->date_birthday,
            'province'          => $request->province,
            'regency'           => $request->regency,
            'district'          => $request->district,
            'village'           => $request->village,
            'address'           => $request->address,
            'gps_coordinates'   => $request->gps_coordinates,
            'age'               => $request->filled('date_birthday')
                                    ? Carbon::parse($request->date_birthday)->age
                                    : $person->age,
        ]);

        $person->save();
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
