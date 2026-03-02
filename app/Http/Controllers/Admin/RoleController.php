<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefRole;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = RefRole::all();
        return view('admin.manage-role.index', compact('roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name_role' => 'required|string|max:255',
        ]);

        $role = RefRole::findOrFail($id);
        $role->update([
            'name_role' => $request->name_role
        ]);

        return back()->with('success', 'Nama Role berhasil diperbarui.');
    }
}
