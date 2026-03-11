<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Foundation;
use Illuminate\Http\Request;

class FoundationController extends Controller
{
    public function index()
    {
        $foundations = Foundation::latest()->get();
        return view('admin.manage-foundation.index', compact('foundations'));
    }
}
