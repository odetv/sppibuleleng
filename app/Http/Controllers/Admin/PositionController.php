<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefPosition;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        // Mengambil semua data jabatan
        $positions = RefPosition::all();
        return view('admin.positions.index', compact('positions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name_position' => 'required|string|max:255',
        ]);

        $position = RefPosition::findOrFail($id);
        $position->update([
            'name_position' => $request->name_position
        ]);

        return back()->with('success', 'Nama Jabatan berhasil diperbarui.');
    }
}
