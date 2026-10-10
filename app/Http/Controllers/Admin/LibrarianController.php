<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtherStaff;

class LibrarianController extends Controller
{
    /**
     * Display all librarians.
     */
    public function index()
    {
        $librarians = OtherStaff::where('designation', 'Librarian')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.library.librarian.index', compact('librarians'));
    }

    /**
     * Display a single librarian profile.
     */
    public function show(OtherStaff $librarian)
    {
        // Make sure this staff member is actually a Librarian.
        abort_unless(
            $librarian->designation === 'Librarian',
            404
        );

        return view('admin.library.librarian.show', compact('librarian'));
    }
}