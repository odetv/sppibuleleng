<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pic;
use Illuminate\Http\Request;

class PicController extends Controller
{
    public function index()
    {
        $pics = Pic::with(['foundation', 'partner'])->latest()->get();
        return view('admin.manage-pic.index', compact('pics'));
    }
}
