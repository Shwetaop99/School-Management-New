<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class TeacherController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DISPLAY ALL TEACHERS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $teachers = Teacher::latest()->get();

        return view('admin.teachers.index', compact('teachers'));
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW CREATE TEACHER FORM
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('admin.teachers.create');
    }


    /*
    |--------------------------------------------------------------------------
    | STORE TEACHER
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',

            'email'         => [
                'required',
                'email',
                'unique:teachers,email',
            ],

            'phone'         => [
                'nullable',
                'string',
                'max:10',
            ],

            'date_of_birth' => 'nullable|date',

            'gender'        => 'nullable|in:Male,Female,Other',

            'qualification' => 'nullable|string|max:255',

            'joining_date'  => 'nullable|date',

            'address'       => 'nullable|string|max:1000',

            'subject'       => 'nullable|string|max:255',

            'status'        => 'required|in:Active,Inactive',

            'profile_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | CREATE TEACHER
        |--------------------------------------------------------------------------
        */

        $teacher = new Teacher();

        $teacher->first_name = $validated['first_name'];
        $teacher->last_name = $validated['last_name'];
        $teacher->email = $validated['email'];
        $teacher->phone = $validated['phone'] ?? null;
        $teacher->date_of_birth = $validated['date_of_birth'] ?? null;
        $teacher->gender = $validated['gender'] ?? null;
        $teacher->qualification = $validated['qualification'] ?? null;
        $teacher->joining_date = $validated['joining_date'] ?? null;
        $teacher->address = $validated['address'] ?? null;
        $teacher->subject = $validated['subject'] ?? null;
        $teacher->status = $validated['status'];


        /*
        |--------------------------------------------------------------------------
        | PROFILE IMAGE - CLOUDINARY
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('profile_image')) {

            $image = $request->file('profile_image');

            $uploadedFile = Cloudinary::uploadApi()->upload(
                $image->getRealPath(),
                [
                    'folder' => 'school-management/teachers',
                ]
            );

            $teacher->profile_image =
                $uploadedFile['secure_url'] ?? null;
        }


        /*
        |--------------------------------------------------------------------------
        | SAVE TEACHER
        |--------------------------------------------------------------------------
        |
        | First save creates the database primary ID.
        |
        */

        $teacher->save();


        /*
        |--------------------------------------------------------------------------
        | AUTOMATIC TEACHER ID
        |--------------------------------------------------------------------------
        |
        | Database ID 1  -> TCH-00001
        | Database ID 2  -> TCH-00002
        | Database ID 25 -> TCH-00025
        |
        */

        $teacher->teacher_id =
            'TCH-' . str_pad($teacher->id, 5, '0', STR_PAD_LEFT);

        $teacher->save();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher added successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW SINGLE TEACHER
    |--------------------------------------------------------------------------
    */

    public function show(Teacher $teacher)
    {
        return view('admin.teachers.show', compact('teacher'));
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW EDIT TEACHER FORM
    |--------------------------------------------------------------------------
    */

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE TEACHER
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([

            'first_name' => 'required|string|max:100',

            'last_name' => 'required|string|max:100',

            'email' => [
                'required',
                'string',
                'email',
                'max:255',

                Rule::unique('teachers', 'email')
                    ->ignore($teacher->id),
            ],

            'phone' => [
                'required',
                'string',
                'regex:/^[6-9][0-9]{9}$/',

                Rule::unique('teachers', 'phone')
                    ->ignore($teacher->id),
            ],

            'date_of_birth' => 'nullable|date',

            'gender' => 'nullable|in:Male,Female,Other',

            'qualification' => 'nullable|string|max:255',

            'subject' => 'nullable|string|max:255',

            'joining_date' => 'nullable|date',

            'address' => 'nullable|string',

            'status' => 'required|in:Active,Inactive',

            'profile_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPLOAD NEW PROFILE IMAGE TO CLOUDINARY
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('profile_image')) {

            $upload = Cloudinary::uploadApi()->upload(
                $request->file('profile_image')->getRealPath(),
                [
                    'folder' => 'school-management/teachers',
                ]
            );

            $validated['profile_image'] =
                $upload['secure_url'] ?? $teacher->profile_image;
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE TEACHER
        |--------------------------------------------------------------------------
        */

        $teacher->update($validated);


        /*
        |--------------------------------------------------------------------------
        | MAKE SURE AUTOMATIC TEACHER ID REMAINS
        |--------------------------------------------------------------------------
        */

        if (empty($teacher->teacher_id)) {

            $teacher->teacher_id =
                'TCH-' . str_pad($teacher->id, 5, '0', STR_PAD_LEFT);

            $teacher->save();
        }


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE TEACHER
    |--------------------------------------------------------------------------
    */

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}

