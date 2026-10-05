<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Display teacher reports dashboard.
     */
    public function index(Request $request)
    {
        $teachers = Teacher::query()
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('admin.reports.index', compact('teachers'));
    }
}