<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class StudentDocumentsController extends Controller
{
    public function index()
    {
        return view('admin.student-documents.index');
    }
}