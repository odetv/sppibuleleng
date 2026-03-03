<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        try {
            DB::beginTransaction();

            $role = RefRole::findOrFail($id);
            $role->update([
                'name_role' => $request->name_role
            ]);

            DB::commit();
            return back()->with('success', 'Nama Role berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui Role: ' . $e->getMessage());
        }
    }
}
