<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentHealthRecord;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentHealthController extends Controller
{
    /**
     * Display health records.
     */
    public function index(Request $request)
{
    $query = Student::with([
        'healthRecords' => function ($query) {
            $query->latest('checkup_date');
        }
    ])
    ->where('status', 'active');

    /*
    |--------------------------------------------------------------------------
    | Academic Year Filter
    |--------------------------------------------------------------------------
    */
    if ($request->filled('academic_year')) {

        $query->whereHas('healthRecords', function ($q) use ($request) {
            $q->where(
                'academic_year',
                $request->academic_year
            );
        });

    }

    /*
    |--------------------------------------------------------------------------
    | Class Filter
    |--------------------------------------------------------------------------
    */
    if ($request->filled('class')) {

        $query->where(
            'class',
            $request->class
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Section Filter
    |--------------------------------------------------------------------------
    */
    if ($request->filled('section')) {

        $query->where(
            'section',
            $request->section
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Student Search
    |--------------------------------------------------------------------------
    */
    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            $q->where(
                'student_id',
                'like',
                '%' . $search . '%'
            )

            ->orWhere(
                'first_name',
                'like',
                '%' . $search . '%'
            )

            ->orWhere(
                'middle_name',
                'like',
                '%' . $search . '%'
            )

            ->orWhere(
                'last_name',
                'like',
                '%' . $search . '%'
            );

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    */
    $students = $query
        ->orderByRaw(
            'CAST(class AS UNSIGNED) ASC'
        )
        ->orderBy('section')
        ->orderBy('roll_number')
        ->paginate(25)
        ->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | Academic Years
    |--------------------------------------------------------------------------
    */
    $academicYears = StudentHealthRecord::query()
        ->select('academic_year')
        ->distinct()
        ->orderByDesc('academic_year')
        ->pluck('academic_year');

    /*
    |--------------------------------------------------------------------------
    | Classes
    |--------------------------------------------------------------------------
    */
    $classes = Student::query()
        ->where('status', 'active')
        ->whereNotNull('class')
        ->select('class')
        ->distinct()
        ->orderByRaw(
            'CAST(class AS UNSIGNED) ASC'
        )
        ->pluck('class');

    return view(
        'admin.student-health.index',
        compact(
            'students',
            'academicYears',
            'classes'
        )
    );
}

    /**
     * Show create health record form.
     */
    public function create(Student $student)
    {
        return view(
            'admin.student-health.create',
            compact('student')
        );
    }

    /**
     * Store a new annual health record.
     */
    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'academic_year' => [
                'required',
                'string',
                'max:20',
                Rule::unique('student_health_records')
                    ->where(function ($query) use ($student) {
                        return $query->where(
                            'student_id',
                            $student->id
                        );
                    }),
            ],

            'checkup_date' => [
                'required',
                'date',
            ],

            'checkup_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'doctor_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'health_center' => [
                'nullable',
                'string',
                'max:200',
            ],

            'conducted_by' => [
                'nullable',
                'string',
                'max:150',
            ],

            'height' => [
                'nullable',
                'numeric',
                'min:0',
                'max:300',
            ],

            'weight' => [
                'nullable',
                'numeric',
                'min:0',
                'max:500',
            ],

            'bmi' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'pulse_rate' => [
                'nullable',
                'integer',
                'min:0',
                'max:300',
            ],

            'blood_pressure' => [
                'nullable',
                'string',
                'max:30',
            ],

            'temperature' => [
                'nullable',
                'numeric',
                'min:0',
                'max:50',
            ],

            'vision_right' => [
                'nullable',
                'string',
                'max:50',
            ],

            'vision_left' => [
                'nullable',
                'string',
                'max:50',
            ],

            'near_vision_right' => [
                'nullable',
                'string',
                'max:50',
            ],

            'near_vision_left' => [
                'nullable',
                'string',
                'max:50',
            ],

            'uses_spectacles' => [
                'nullable',
                'boolean',
            ],

            'spectacle_power' => [
                'nullable',
                'string',
                'max:100',
            ],

            'dental_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'dental_caries' => [
                'nullable',
                'string',
                'max:100',
            ],

            'gum_problem' => [
                'nullable',
                'string',
                'max:100',
            ],

            'oral_hygiene' => [
                'nullable',
                'string',
                'max:100',
            ],

            'right_ear' => [
                'nullable',
                'string',
                'max:100',
            ],

            'left_ear' => [
                'nullable',
                'string',
                'max:100',
            ],

            'hearing_problem' => [
                'nullable',
                'string',
                'max:100',
            ],

            'nose_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'throat_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'general_health' => [
                'nullable',
                'string',
                'max:100',
            ],

            'skin_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'respiratory_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'heart_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'abdomen_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'musculoskeletal_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'nutritional_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'anemia_screening' => [
                'nullable',
                'string',
                'max:100',
            ],

            'known_health_condition' => [
                'nullable',
                'string',
                'max:500',
            ],

            'allergy' => [
                'nullable',
                'string',
                'max:500',
            ],

            'current_medication' => [
                'nullable',
                'string',
                'max:500',
            ],

            'medical_history' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'referral_required' => [
                'nullable',
                'boolean',
            ],

            'referral_to' => [
                'nullable',
                'string',
                'max:200',
            ],

            'referral_date' => [
                'nullable',
                'date',
            ],

            'treatment_advised' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'follow_up_date' => [
                'nullable',
                'date',
            ],

            'follow_up_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'follow_up_remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'overall_health_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'doctor_remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'teacher_remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'parent_remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $validated['student_id'] = $student->id;

        StudentHealthRecord::create($validated);

        return redirect()
            ->route(
                'admin.student-health.index'
            )
            ->with(
                'success',
                'Annual health record added successfully.'
            );
    }

    /**
     * Display one health record.
     */
    public function show(StudentHealthRecord $healthRecord)
    {
        $healthRecord->load('student');

        return view(
            'admin.student-health.show',
            compact('healthRecord')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(StudentHealthRecord $healthRecord)
    {
        $healthRecord->load('student');

        return view(
            'admin.student-health.edit',
            compact('healthRecord')
        );
    }

    /**
     * Update health record.
     */
    public function update(
        Request $request,
        StudentHealthRecord $healthRecord
    ) {
        $validated = $request->validate([
            'academic_year' => [
                'required',
                'string',
                'max:20',
                Rule::unique('student_health_records')
                    ->ignore($healthRecord->id)
                    ->where(function ($query) use ($healthRecord) {
                        return $query->where(
                            'student_id',
                            $healthRecord->student_id
                        );
                    }),
            ],

            'checkup_date' => [
                'required',
                'date',
            ],

            'checkup_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'doctor_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'health_center' => [
                'nullable',
                'string',
                'max:200',
            ],

            'conducted_by' => [
                'nullable',
                'string',
                'max:150',
            ],

            'height' => [
                'nullable',
                'numeric',
                'min:0',
                'max:300',
            ],

            'weight' => [
                'nullable',
                'numeric',
                'min:0',
                'max:500',
            ],

            'bmi' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'pulse_rate' => [
                'nullable',
                'integer',
                'min:0',
                'max:300',
            ],

            'blood_pressure' => [
                'nullable',
                'string',
                'max:30',
            ],

            'temperature' => [
                'nullable',
                'numeric',
                'min:0',
                'max:50',
            ],

            'vision_right' => [
                'nullable',
                'string',
                'max:50',
            ],

            'vision_left' => [
                'nullable',
                'string',
                'max:50',
            ],

            'near_vision_right' => [
                'nullable',
                'string',
                'max:50',
            ],

            'near_vision_left' => [
                'nullable',
                'string',
                'max:50',
            ],

            'uses_spectacles' => [
                'nullable',
                'boolean',
            ],

            'spectacle_power' => [
                'nullable',
                'string',
                'max:100',
            ],

            'dental_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'dental_caries' => [
                'nullable',
                'string',
                'max:100',
            ],

            'gum_problem' => [
                'nullable',
                'string',
                'max:100',
            ],

            'oral_hygiene' => [
                'nullable',
                'string',
                'max:100',
            ],

            'right_ear' => [
                'nullable',
                'string',
                'max:100',
            ],

            'left_ear' => [
                'nullable',
                'string',
                'max:100',
            ],

            'hearing_problem' => [
                'nullable',
                'string',
                'max:100',
            ],

            'nose_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'throat_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'general_health' => [
                'nullable',
                'string',
                'max:100',
            ],

            'skin_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'respiratory_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'heart_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'abdomen_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'musculoskeletal_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'nutritional_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'anemia_screening' => [
                'nullable',
                'string',
                'max:100',
            ],

            'known_health_condition' => [
                'nullable',
                'string',
                'max:500',
            ],

            'allergy' => [
                'nullable',
                'string',
                'max:500',
            ],

            'current_medication' => [
                'nullable',
                'string',
                'max:500',
            ],

            'medical_history' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'referral_required' => [
                'nullable',
                'boolean',
            ],

            'referral_to' => [
                'nullable',
                'string',
                'max:200',
            ],

            'referral_date' => [
                'nullable',
                'date',
            ],

            'treatment_advised' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'follow_up_date' => [
                'nullable',
                'date',
            ],

            'follow_up_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'follow_up_remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'overall_health_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'doctor_remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'teacher_remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'parent_remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $healthRecord->update($validated);

        return redirect()
            ->route(
                'admin.student-health.show',
                $healthRecord
            )
            ->with(
                'success',
                'Health record updated successfully.'
            );
    }

    /**
     * Delete health record.
     */
    public function destroy(StudentHealthRecord $healthRecord)
    {
        $healthRecord->delete();

        return redirect()
            ->route(
                'admin.student-health.index'
            )
            ->with(
                'success',
                'Health record deleted successfully.'
            );
    }

    /**
     * Print one health record.
     */
    public function print(StudentHealthRecord $healthRecord)
{
    $healthRecord->load('student');

    $schoolSetting = \App\Models\SchoolSetting::first();

    return view('admin.student-health.print', [
        'healthRecord' => $healthRecord,
        'schoolSetting' => $schoolSetting,
    ]);
}
}