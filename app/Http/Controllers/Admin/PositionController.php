<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PositionController extends Controller
{
    public function index()
    {
        $positions = RefPosition::all();
        return view('admin.manage-position.index', compact('positions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name_position' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $position = RefPosition::findOrFail($id);
            $position->update([
                'name_position' => $request->name_position
            ]);

            DB::commit();
            return back()->with('success', 'Nama Jabatan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui Jabatan: ' . $e->getMessage());
        }
    }
}
