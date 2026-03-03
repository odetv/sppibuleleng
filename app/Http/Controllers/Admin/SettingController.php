<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function toggleMaintenance()
    {
        try {
            DB::beginTransaction();

            // Ambil status saat ini, jika tidak ada default ke '0' (mati)
            $currentStatus = Setting::where('key', 'is_maintenance')->first()?->value ?? '0';
            $newStatus = $currentStatus === '1' ? '0' : '1';

            // Update atau buat baru jika belum ada
            Setting::updateOrCreate(
                ['key' => 'is_maintenance'],
                ['value' => $newStatus]
            );

            DB::commit();

            $msg = $newStatus === '1' ? 'Mode Maintenance diaktifkan!' : 'Mode Maintenance dimatikan!';
            return back()->with('status', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengubah mode maintenance: ' . $e->getMessage());
        }
    }
}
