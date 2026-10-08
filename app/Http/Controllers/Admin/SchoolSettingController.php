<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;

class SchoolSettingController extends Controller
{
    /**
     * Display school settings.
     */
    public function index()
    {
        $school = SchoolSetting::first();

        return view(
            'admin.settings.index',
            compact('school')
        );
    }

    /**
     * Show create school profile form.
     *
     * Only one school profile is allowed.
     */
    public function create()
    {
        $school = SchoolSetting::first();

        if ($school) {
            return redirect()
                ->route('admin.settings.edit');
        }

        return view('admin.settings.create');
    }

    /**
     * Store school profile.
     */
    public function store(Request $request)
    {
        $validated = $this->validateSchool($request);

        /*
        |--------------------------------------------------------------------------
        | Check Existing School
        |--------------------------------------------------------------------------
        */

        $schoolSetting = SchoolSetting::first();

        /*
        |--------------------------------------------------------------------------
        | Upload Logo To Cloudinary
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->uploadCloudinaryImage(
                $request->file('logo')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create School If None Exists
        |--------------------------------------------------------------------------
        */

        if (!$schoolSetting) {

            SchoolSetting::create($validated);

            return redirect()
                ->route('admin.settings.index')
                ->with(
                    'success',
                    'School profile created successfully.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Update Existing School
        |--------------------------------------------------------------------------
        */

        $oldLogo = $schoolSetting->logo;

        $schoolSetting->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Delete Old Logo If New Logo Was Uploaded
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['logo']) &&
            !empty($oldLogo) &&
            $oldLogo !== $validated['logo']
        ) {
            $this->deleteCloudinaryImage($oldLogo);
        }

        return redirect()
            ->route('admin.settings.index')
            ->with(
                'success',
                'School profile updated successfully.'
            );
    }

    /**
     * Show edit school profile form.
     *
     * The application allows only one school profile,
     * so we retrieve the existing record directly.
     */
    public function edit()
    {
        $schoolSetting = SchoolSetting::first();

        if (!$schoolSetting) {
            return redirect()
                ->route('admin.settings.create')
                ->with(
                    'error',
                    'No school profile found. Please create one first.'
                );
        }

        return view(
            'admin.settings.edit',
            compact('schoolSetting')
        );
    }

    /**
     * Update school profile.
     *
     * The application allows only one school profile,
     * so we retrieve the existing record directly.
     */
    public function update(Request $request)
    {
        $schoolSetting = SchoolSetting::first();

        if (!$schoolSetting) {
            return redirect()
                ->route('admin.settings.create')
                ->with(
                    'error',
                    'No school profile found. Please create one first.'
                );
        }

        $validated = $this->validateSchool(
            $request,
            $schoolSetting
        );

        /*
        |--------------------------------------------------------------------------
        | Keep Old Logo
        |--------------------------------------------------------------------------
        */

        $oldLogo = $schoolSetting->logo;

        /*
        |--------------------------------------------------------------------------
        | Upload New Logo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->uploadCloudinaryImage(
                $request->file('logo')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Update School
        |--------------------------------------------------------------------------
        */

        $schoolSetting->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Delete Old Logo Only When New Logo Was Uploaded
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['logo']) &&
            !empty($oldLogo) &&
            $oldLogo !== $validated['logo']
        ) {
            $this->deleteCloudinaryImage($oldLogo);
        }

        return redirect()
            ->route('admin.settings.index')
            ->with(
                'success',
                'School profile updated successfully.'
            );
    }

    /**
     * Delete school profile.
     */
    public function destroy()
    {
        $schoolSetting = SchoolSetting::first();

        if (!$schoolSetting) {
            return redirect()
                ->route('admin.settings.index')
                ->with(
                    'error',
                    'No school profile found.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Cloudinary Logo
        |--------------------------------------------------------------------------
        */

        if (!empty($schoolSetting->logo)) {
            $this->deleteCloudinaryImage(
                $schoolSetting->logo
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Database Record
        |--------------------------------------------------------------------------
        */

        $schoolSetting->delete();

        return redirect()
            ->route('admin.settings.index')
            ->with(
                'success',
                'School profile deleted successfully. You can now add a new school.'
            );
    }

    /**
     * Validate school profile fields.
     *
     * Student ID settings are optional from the form.
     * Sensible defaults are applied when they are not supplied.
     */
    private function validateSchool(
        Request $request,
        ?SchoolSetting $schoolSetting = null
    ) {
        /*
        |--------------------------------------------------------------------------
        | Student ID Defaults
        |--------------------------------------------------------------------------
        |
        | These values are used when the School Profile form does not
        | contain the student ID configuration fields.
        |
        */

        $studentIdFormat = $request->input(
            'student_id_format',
            $schoolSetting?->student_id_format ?? 'STU{YYYY}{####}'
        );

        $studentIdStart = $request->input(
            'student_id_start',
            $schoolSetting?->student_id_start ?? 1
        );

        $studentIdLength = $request->input(
            'student_id_length',
            $schoolSetting?->student_id_length ?? 4
        );

        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        return $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Basic School Information
            |--------------------------------------------------------------------------
            */

            'school_name' => [
                'required',
                'string',
                'max:255',
            ],

            'school_code' => [
                'nullable',
                'string',
                'max:50',
            ],

            'udise_code' => [
                'nullable',
                'string',
                'max:50',
            ],

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            'address' => [
                'nullable',
                'string',
                'max:500',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'district' => [
                'nullable',
                'string',
                'max:100',
            ],

            'state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'pincode' => [
                'nullable',
                'string',
                'max:20',
            ],

            /*
            |--------------------------------------------------------------------------
            | Contact
            |--------------------------------------------------------------------------
            */

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'website' => [
                'nullable',
                'url',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | School Administration
            |--------------------------------------------------------------------------
            */

            'principal_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'established_year' => [
                'nullable',
                'integer',
                'min:1800',
                'max:' . date('Y'),
            ],

            /*
            |--------------------------------------------------------------------------
            | Student ID Settings
            |--------------------------------------------------------------------------
            */

            'student_id_format' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[A-Za-z0-9{}_\-\/\.]+$/',
            ],

            'student_id_start' => [
                'nullable',
                'integer',
                'min:1',
                'max:999999999',
            ],

            'student_id_length' => [
                'nullable',
                'integer',
                'min:1',
                'max:10',
            ],

            /*
            |--------------------------------------------------------------------------
            | Logo
            |--------------------------------------------------------------------------
            */

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]) + [

            /*
            |--------------------------------------------------------------------------
            | Apply Student ID Defaults
            |--------------------------------------------------------------------------
            */

            'student_id_format' => $studentIdFormat,

            'student_id_start' => $studentIdStart,

            'student_id_length' => $studentIdLength,
        ];
    }

    /**
     * Upload image to Cloudinary.
     */
    private function uploadCloudinaryImage($file)
    {
        try {

            $upload = Cloudinary::uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'school-management-db/school-settings',
                ]
            );

            return $upload['secure_url'];

        } catch (\Throwable $e) {

            Log::error(
                'Cloudinary school logo upload failed: ' .
                $e->getMessage()
            );

            throw $e;
        }
    }

    /**
     * Delete an image from Cloudinary.
     *
     * Database stores the complete secure Cloudinary URL.
     */
    private function deleteCloudinaryImage($url)
    {
        try {

            if (empty($url)) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Parse URL
            |--------------------------------------------------------------------------
            */

            $path = parse_url(
                $url,
                PHP_URL_PATH
            );

            if (!$path) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Remove Cloudinary Prefix
            |--------------------------------------------------------------------------
            */

            $path = preg_replace(
                '#^.*?/image/upload/#',
                '',
                $path
            );

            /*
            |--------------------------------------------------------------------------
            | Remove Version Number
            |--------------------------------------------------------------------------
            */

            $path = preg_replace(
                '#^v[0-9]+/#',
                '',
                $path
            );

            /*
            |--------------------------------------------------------------------------
            | Remove File Extension
            |--------------------------------------------------------------------------
            */

            $directory = pathinfo(
                $path,
                PATHINFO_DIRNAME
            );

            $filename = pathinfo(
                $path,
                PATHINFO_FILENAME
            );

            if ($directory === '.') {
                $directory = '';
            }

            /*
            |--------------------------------------------------------------------------
            | Build Public ID
            |--------------------------------------------------------------------------
            */

            $publicId = $directory
                ? $directory . '/' . $filename
                : $filename;

            if (empty($publicId)) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Delete From Cloudinary
            |--------------------------------------------------------------------------
            */

            Cloudinary::uploadApi()->destroy(
                $publicId
            );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Do Not Break School Update/Delete
            |--------------------------------------------------------------------------
            */

            Log::warning(
                'Cloudinary school logo deletion failed: ' .
                $e->getMessage()
            );
        }
    }
}