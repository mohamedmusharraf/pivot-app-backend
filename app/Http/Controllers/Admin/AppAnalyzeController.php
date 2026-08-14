<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AppAnalyzeController extends Controller
{
    public function index()
    {
        return view('admin.app-analyze.index');
    }
}