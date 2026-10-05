<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtherStaff;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class OtherStaffController extends Controller
{
    /**
     * Display a listing of other staff.
     */
    public function index(Request $request)
    {
        $query = OtherStaff::query();

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('staff_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('designation', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }

        // Designation filter
        if ($request->filled('designation')) {
            $query->where('designation', $request->designation);
        }

        // Department filter
        if ($request->filled('department')) {
            $query->where('department', $request->department);
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

        $activeStaff = OtherStaff::where('status', 'Active')->count();

        $inactiveStaff = OtherStaff::where('status', 'Inactive')->count();

        // Designations
        $designations = OtherStaff::query()
            ->whereNotNull('designation')
            ->where('designation', '!=', '')
            ->select('designation')
            ->distinct()
            ->orderBy('designation')
            ->pluck('designation');


            $librarians = OtherStaff::where('status', 'Active')
    ->where('designation', 'Librarian')
    ->count();

        // Departments
        $departments = OtherStaff::query()
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->select('department')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

    return view('admin.other-staff.index', compact(
    'staff',
    'totalStaff',
    'activeStaff',
    'inactiveStaff',
    'librarians',
    'designations',
    'departments'
));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Generate Next Staff ID
        |--------------------------------------------------------------------------
        */

        $lastStaff = OtherStaff::orderByDesc('id')->first();

        if ($lastStaff) {
            $lastNumber = (int) preg_replace(
                '/[^0-9]/',
                '',
                $lastStaff->staff_id
            );

            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $nextStaffId = 'STF-' . str_pad(
            $nextNumber,
            3,
            '0',
            STR_PAD_LEFT
        );

        return view(
            'admin.other-staff.create',
            compact('nextStaffId')
        );
    }

    /**
     * Store a newly created staff member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'gender' => [
                'nullable',
                'string',
                'max:50',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'designation' => [
                'required',
                'string',
                'max:255',
            ],

            'department' => [
                'nullable',
                'string',
                'max:255',
            ],

            'qualification' => [
                'nullable',
                'string',
                'max:255',
            ],

            'license_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'license_expiry' => [
                'nullable',
                'date',
            ],

            'joining_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                Rule::in(['Active', 'Inactive']),
            ],

            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate Staff ID
        |--------------------------------------------------------------------------
        */

        $lastStaff = OtherStaff::orderByDesc('id')->first();

        if ($lastStaff) {
            $lastNumber = (int) preg_replace(
                '/[^0-9]/',
                '',
                $lastStaff->staff_id
            );

            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $validated['staff_id'] = 'STF-' . str_pad(
            $nextNumber,
            3,
            '0',
            STR_PAD_LEFT
        );

        /*
        |--------------------------------------------------------------------------
        | Cloudinary Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('profile_photo')) {
            try {
                $uploadApi = new UploadApi();

                $uploadResult = $uploadApi->upload(
                    $request->file('profile_photo')->getRealPath(),
                    [
                        'folder' => 'school-management/other-staff',
                        'resource_type' => 'image',
                    ]
                );

                /*
                | Store Cloudinary public_id in database
                */
                $validated['profile_photo'] = $uploadResult['public_id'];

            } catch (\Throwable $e) {

                Log::error('Cloudinary Other Staff Upload Failed', [
                    'error' => $e->getMessage(),
                ]);

                return back()
                    ->withInput()
                    ->withErrors([
                        'profile_photo' =>
                            'Profile image upload failed. Please try again.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Create Staff
        |--------------------------------------------------------------------------
        */

        OtherStaff::create($validated);

        return redirect()
            ->route('admin.other-staff.index')
            ->with(
                'success',
                'Other staff member added successfully.'
            );
    }

    /**
     * Display staff profile.
     */
    public function show(OtherStaff $otherStaff)
    {
        return view(
            'admin.other-staff.show',
            compact('otherStaff')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(OtherStaff $otherStaff)
    {
        return view(
            'admin.other-staff.edit',
            compact('otherStaff')
        );
    }

    /**
     * Update staff member.
     */
    public function update(
        Request $request,
        OtherStaff $otherStaff
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'gender' => [
                'nullable',
                'string',
                'max:50',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'designation' => [
                'required',
                'string',
                'max:255',
            ],

            'department' => [
                'nullable',
                'string',
                'max:255',
            ],

            'qualification' => [
                'nullable',
                'string',
                'max:255',
            ],

            'license_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'license_expiry' => [
                'nullable',
                'date',
            ],

            'joining_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                Rule::in(['Active', 'Inactive']),
            ],

            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | New Profile Photo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('profile_photo')) {

            try {

                $uploadApi = new UploadApi();

                /*
                |--------------------------------------------------------------------------
                | Delete Old Cloudinary Image
                |--------------------------------------------------------------------------
                */

                if (!empty($otherStaff->profile_photo)) {

                    $oldPhoto = $otherStaff->profile_photo;

                    /*
                    | Skip old local-storage paths and URLs.
                    */
                    $isOldLocalPhoto =
                        str_starts_with($oldPhoto, 'storage/') ||
                        str_starts_with($oldPhoto, 'other-staff/');

                    $isUrl =
                        str_starts_with($oldPhoto, 'http://') ||
                        str_starts_with($oldPhoto, 'https://');

                    if (!$isOldLocalPhoto && !$isUrl) {

                        try {

                            $uploadApi->destroy(
                                $oldPhoto,
                                [
                                    'resource_type' => 'image',
                                    'type' => 'upload',
                                ]
                            );

                        } catch (\Throwable $e) {

                            Log::warning(
                                'Old Cloudinary Other Staff Image Could Not Be Deleted',
                                [
                                    'public_id' => $oldPhoto,
                                    'error' => $e->getMessage(),
                                ]
                            );
                        }
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Upload New Image
                |--------------------------------------------------------------------------
                */

                $uploadResult = $uploadApi->upload(
                    $request->file('profile_photo')->getRealPath(),
                    [
                        'folder' => 'school-management/other-staff',
                        'resource_type' => 'image',
                    ]
                );

                /*
                | Save Cloudinary public_id.
                */
                $validated['profile_photo'] =
                    $uploadResult['public_id'];

            } catch (\Throwable $e) {

                Log::error(
                    'Cloudinary Other Staff Update Upload Failed',
                    [
                        'staff_id' => $otherStaff->staff_id,
                        'error' => $e->getMessage(),
                    ]
                );

                return back()
                    ->withInput()
                    ->withErrors([
                        'profile_photo' =>
                            'Profile image upload failed. Please try again.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update Database
        |--------------------------------------------------------------------------
        */

        $otherStaff->update($validated);

        return redirect()
            ->route('admin.other-staff.index')
            ->with(
                'success',
                'Other staff member updated successfully.'
            );
    }

    /**
     * Delete staff member.
     */
    public function destroy(OtherStaff $otherStaff)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete Cloudinary Image
        |--------------------------------------------------------------------------
        */

        if (!empty($otherStaff->profile_photo)) {

            $photo = $otherStaff->profile_photo;

            $isOldLocalPhoto =
                str_starts_with($photo, 'storage/') ||
                str_starts_with($photo, 'other-staff/');

            $isUrl =
                str_starts_with($photo, 'http://') ||
                str_starts_with($photo, 'https://');

            if (!$isOldLocalPhoto && !$isUrl) {

                try {

                    $uploadApi = new UploadApi();

                    $uploadApi->destroy(
                        $photo,
                        [
                            'resource_type' => 'image',
                            'type' => 'upload',
                        ]
                    );

                } catch (\Throwable $e) {

                    Log::warning(
                        'Cloudinary Other Staff Image Delete Failed',
                        [
                            'public_id' => $photo,
                            'error' => $e->getMessage(),
                        ]
                    );
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Staff Record
        |--------------------------------------------------------------------------
        */

        $otherStaff->delete();

        return redirect()
            ->route('admin.other-staff.index')
            ->with(
                'success',
                'Other staff member deleted successfully.'
            );
    }
}