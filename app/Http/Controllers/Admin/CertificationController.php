<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    public function index()
    {
        $certifications = Certification::with('sppgUnit')->latest()->get();
        return view('admin.manage-certification.index', compact('certifications'));
    }
}
