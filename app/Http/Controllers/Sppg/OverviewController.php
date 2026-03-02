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
            // Priority 1: Check if person is a leader via leader_id (Direct link)
            $sppg = SppgUnit::with(['leader', 'nutritionist', 'accountant', 'socialMedia'])
                ->where('leader_id', $person->id_person)
                ->first();

            // Priority 2: If not leader, check based on their work assignment
            if (!$sppg && $person->id_work_assignment) {
                $assignment = $person->workAssignment;
                if ($assignment) {
                    $sppg = SppgUnit::with(['leader', 'nutritionist', 'accountant', 'socialMedia'])
                        ->where('id_sppg_unit', $assignment->id_sppg_unit)
                        ->first();
                }
            }

            // Fetch decree from work assignment
            if ($person->id_work_assignment) {
                $decree = $person->workAssignment->decree ?? null;
            }
        }

        return view('sppg.overview', compact('sppg', 'decree'));
    }
}
