<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtherStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OtherStaffController extends Controller
{
    /**
     * Display all other staff members.
     */
    public function index(Request $request)
    {
        $query = OtherStaff::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('staff_id', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Designation filter
        if ($request->filled('designation')) {
            $query->where('designation', $request->designation);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $staff = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Statistics
        $totalStaff = OtherStaff::count();

        $activeStaff = OtherStaff::where('status', 'Active')
            ->count();

        $inactiveStaff = OtherStaff::where('status', 'Inactive')
            ->count();

        $librarians = OtherStaff::where('designation', 'Librarian')
            ->where('status', 'Active')
            ->count();

        $designations = OtherStaff::select('designation')
            ->distinct()
            ->orderBy('designation')
            ->pluck('designation');

        return view('admin.other-staff.index', compact(
            'staff',
            'totalStaff',
            'activeStaff',
            'inactiveStaff',
            'librarians',
            'designations'
        ));
    }


    /**
     * Show the form for creating a new staff member.
     */
    public function create()
{
    $lastStaff = OtherStaff::orderByDesc('id')->first();

    if ($lastStaff) {
        $lastNumber = (int) preg_replace('/[^0-9]/', '', $lastStaff->staff_id);
        $nextNumber = $lastNumber + 1;
    } else {
        $nextNumber = 1;
    }

    $nextStaffId = 'STF-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

    return view('admin.other-staff.create', compact('nextStaffId'));
}


    /**
     * Store a newly created staff member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|string|max:50|unique:other_staff,staff_id',
            'name' => 'required|string|max:255',

            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'gender' => 'nullable|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date',

            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:other_staff,email',
            'address' => 'nullable|string',

            'designation' => 'required|in:Librarian,Accountant,Receptionist,Peon,Driver,Other',

            'department' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date|before_or_equal:today',

            'status' => 'required|in:Active,Inactive',
        ]);

        // Upload profile photo
        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request
                ->file('profile_photo')
                ->store('other-staff', 'public');
        }

        OtherStaff::create($validated);

        return redirect()
            ->route('admin.other-staff.index')
            ->with('success', 'Staff member added successfully.');
    }


    /**
     * Display a specific staff member.
     */
    public function show(OtherStaff $otherStaff)
{
    return view('admin.other-staff.show', compact('otherStaff'));
}


    /**
     * Show the form for editing a staff member.
     */
    public function edit(OtherStaff $otherStaff)
    {
        return view(
            'admin.other-staff.edit',
            compact('otherStaff')
        );
    }


    /**
     * Update a staff member.
     */
    public function update(
        Request $request,
        OtherStaff $otherStaff
    ) {
        $validated = $request->validate([
            'staff_id' => 'required|string|max:255|unique:other_staff,staff_id,' . $otherStaff->id,

            'name' => 'required|string|max:255',

            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'gender' => 'nullable|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date',

            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:other_staff,email,' . $otherStaff->id,
            'address' => 'nullable|string',

            'designation' => 'required|in:Librarian,Accountant,Receptionist,Peon,Driver,Other',

            'department' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date|before_or_equal:today',

            'status' => 'required|in:Active,Inactive',
        ]);

        // Replace profile photo
        if ($request->hasFile('profile_photo')) {

            if ($otherStaff->profile_photo) {
                Storage::disk('public')
                    ->delete($otherStaff->profile_photo);
            }

            $validated['profile_photo'] = $request
                ->file('profile_photo')
                ->store('other-staff', 'public');
        }

        $otherStaff->update($validated);

        return redirect()
            ->route('admin.other-staff.show', $otherStaff)
            ->with('success', 'Staff member updated successfully.');
    }


    /**
     * Delete a staff member.
     */
    public function destroy(OtherStaff $otherStaff)
    {
        // Delete profile photo
        if ($otherStaff->profile_photo) {
            Storage::disk('public')
                ->delete($otherStaff->profile_photo);
        }

        $otherStaff->delete();

        return redirect()
            ->route('admin.other-staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }
}