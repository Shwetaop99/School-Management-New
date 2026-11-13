<?php

namespace App\Http\Controllers\Sports;

use App\Http\Controllers\Controller;

class SportsController extends Controller
{
    public function index()
    {
        return view('admin.sports.index');
    }
}