<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function toggleMaintenance()
    {
        // Ambil status saat ini, jika tidak ada default ke '0' (mati)
        $currentStatus = Setting::where('key', 'is_maintenance')->first()?->value ?? '0';
        $newStatus = $currentStatus === '1' ? '0' : '1';

        // Update atau buat baru jika belum ada
        Setting::updateOrCreate(
            ['key' => 'is_maintenance'],
            ['value' => $newStatus]
        );

        $msg = $newStatus === '1' ? 'Mode Maintenance diaktifkan!' : 'Mode Maintenance dimatikan!';

        return back()->with('status', $msg);
    }
}
