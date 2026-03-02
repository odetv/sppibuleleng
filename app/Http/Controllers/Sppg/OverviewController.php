<?php

namespace App\Http\Controllers\Sppg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SppgUnit;

class OverviewController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $person = $user->person;
        
        $sppg = null;
        $decree = null;

        if ($person) {
            // Check if person is a leader
            $sppg = SppgUnit::with(['leader', 'nutritionist', 'accountant', 'socialMedia'])->where('leader_id', $person->id_person)->first();

            // Fetch decree from work assignment
            if ($person->id_work_assignment) {
                $decree = $person->workAssignment->decree ?? null;
            }
        }

        return view('sppg.overview', compact('sppg', 'decree'));
    }
}
