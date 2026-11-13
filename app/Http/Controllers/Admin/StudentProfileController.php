<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    /**
     * Display the Student Profile page.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Student Statistics
        |--------------------------------------------------------------------------
        */

        // Total number of students
        $totalStudents = Student::count();

        // Total male students
        $totalMaleStudents = Student::where('gender', 'Male')->count();

        // Total female students
        $totalFemaleStudents = Student::where('gender', 'Female')->count();

        // Total active students
        $totalActiveStudents = Student::where('status', 'active')->count();

        // Total inactive students
        $totalInactiveStudents = Student::where('status', 'inactive')->count();


        /*
        |--------------------------------------------------------------------------
        | Classes
        |--------------------------------------------------------------------------
        |
        | The Student Profile Blade uses:
        |
        |     count($classes)
        |
        | Therefore we make sure $classes is always an array.
        |
        */

        $classes = Student::query()
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->select('class')
            ->distinct()
            ->orderBy('class')
            ->pluck('class')
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Return Student Profile View
        |--------------------------------------------------------------------------
        */

        return view('admin.student-profile.index', compact(
            'totalStudents',
            'totalMaleStudents',
            'totalFemaleStudents',
            'totalActiveStudents',
            'totalInactiveStudents',
            'classes'
        ));
    }


    /**
     * Search student by Student ID.
     *
     * This method is used by the JavaScript
     * on the Student Profile page.
     */
    public function search(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Student ID
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'student_id' => [
                'required',
                'string',
                'max:100',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Find Student
        |--------------------------------------------------------------------------
        */

        $student = Student::where(
            'student_id',
            $validated['student_id']
        )->first();


        /*
        |--------------------------------------------------------------------------
        | Student Not Found
        |--------------------------------------------------------------------------
        */

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.',
            ], 404);
        }


        /*
        |--------------------------------------------------------------------------
        | Student Found
        |--------------------------------------------------------------------------
        |
        | Return the complete Student model.
        |
        | This allows the profile JavaScript to access fields such as:
        |
        | profile_image
        | first_name
        | middle_name
        | last_name
        | full_name
        | marathi_name
        | date_of_birth
        | gender
        | aadhar_card_no
        | phone
        | email
        | academic_year
        | class
        | section
        | roll_number
        | admission_class
        | admission_date
        | register_no
        | book_no
        | appar_id
        | pen_no
        | medium
        | mother_tongue
        | nationality
        | religion
        | caste
        | sub_caste
        | etc.
        |
        */

        return response()->json([
            'success' => true,
            'student' => $student,
        ]);
    }
}
