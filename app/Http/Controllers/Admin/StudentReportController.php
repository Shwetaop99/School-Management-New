<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\BookIssue;
use App\Models\StudentFee;
use App\Models\Transport\TransportRecord;
use App\Models\Scholarship\ScholarshipExamApplication;
use App\Models\Sports\Achievements\Achievement;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\StudentClassReportExport;
use App\Exports\StudentProfileReportExport;
use Maatwebsite\Excel\Facades\Excel;

class StudentReportController extends Controller
{
    /**
     * Student Reports - Class Overview
     */
    public function index(Request $request)
    {
        // Get unique class names and normalize their display.
        $classes = Student::query()
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->pluck('class')
            ->map(function ($class) {
                $class = trim($class);

                if (preg_match('/^class\s+(.+)$/i', $class, $matches)) {
                    return 'Class ' . trim($matches[1]);
                }

                if (is_numeric($class)) {
                    return 'Class ' . $class;
                }

                return $class;
            })
            ->unique()
            ->sort(function ($a, $b) {
                return strnatcasecmp($a, $b);
            })
            ->values();

        $schoolSetting = \App\Models\SchoolSetting::query()->first();

        return view('allReports.studentReport.index', compact(
            'classes',
            'schoolSetting'
        ));
    }

    /**
     * Student Reports - Class, Section and Academic Year Flow
     */
    public function classStudents(Request $request)
    {
        $selectedClass = trim($request->query('class', ''));

        if ($selectedClass === '') {
            return redirect()->route('admin.reports.students');
        }

        // Normalize class name:
        // "class 2" and "2" both become "2".
        $normalizedClass = strtolower(
            preg_replace('/^class\s*/i', '', $selectedClass)
        );

        // Match both class formats without changing database records.
        $studentQuery = Student::query()
            ->whereRaw(
                "LOWER(TRIM(REPLACE(class, 'class', ''))) = ?",
                [$normalizedClass]
            );

        // Get available sections from actual student records.
        $sections = (clone $studentQuery)
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        // Get academic years from actual student records.
        $academicYears = (clone $studentQuery)
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $selectedSection = $request->query('section');
        $selectedAcademicYear = $request->query('academic_year');

        $students = collect();

        // Load students only after valid section and academic year selection.
        if (
            $selectedSection &&
            $selectedAcademicYear &&
            $sections->contains($selectedSection) &&
            $academicYears->contains($selectedAcademicYear)
        ) {
            $students = (clone $studentQuery)
                ->where('section', $selectedSection)
                ->where('academic_year', $selectedAcademicYear)
                ->orderBy('roll_number')
                ->orderBy('first_name')
                ->get();
        }

        $schoolSetting = \App\Models\SchoolSetting::query()->first();

        return view('allReports.studentReport.class', compact(
            'selectedClass',
            'normalizedClass',
            'sections',
            'academicYears',
            'selectedSection',
            'selectedAcademicYear',
            'students',
            'schoolSetting'
        ));
    }

    /**
     * Download Class-wise Student Report Excel
     */
    public function classExcel(Request $request)
    {
        $class = trim($request->query('class', ''));
        $section = trim($request->query('section', ''));
        $academicYear = trim($request->query('academic_year', ''));

        if ($class === '' || $section === '' || $academicYear === '') {
            return redirect()->route('admin.reports.students.class', [
                'class' => $class,
            ])->with('error', 'Please select class, section and academic year.');
        }

        $fileName = 'student-report-' .
            str_replace(' ', '-', strtolower($class)) . '-' .
            str_replace(' ', '-', strtolower($section)) . '-' .
            str_replace('/', '-', $academicYear) . '.xlsx';

        return Excel::download(
            new StudentClassReportExport($class, $section, $academicYear),
            $fileName
        );
    }

    /**
     * Download Class-wise Student Report PDF
     */
    public function classPdf(Request $request)
    {
        $class = trim($request->query('class', ''));
        $section = trim($request->query('section', ''));
        $academicYear = trim($request->query('academic_year', ''));

        if ($class === '' || $section === '' || $academicYear === '') {
            return redirect()->route('admin.reports.students.class', [
                'class' => $class,
            ])->with('error', 'Please select class, section and academic year.');
        }

        $normalizedClass = strtolower(
            preg_replace('/^class\s*/i', '', $class)
        );

        $students = Student::query()
            ->whereRaw(
                "LOWER(TRIM(REPLACE(class, 'class', ''))) = ?",
                [$normalizedClass]
            )
            ->where('section', $section)
            ->where('academic_year', $academicYear)
            ->orderBy('roll_number')
            ->orderBy('first_name')
            ->get();

        $schoolSetting = \App\Models\SchoolSetting::query()->first();

        $fileName = 'student-report-' .
            str_replace(' ', '-', strtolower($class)) . '-' .
            str_replace(' ', '-', strtolower($section)) . '-' .
            str_replace('/', '-', $academicYear) . '.pdf';

        return Pdf::loadView(
            'allReports.studentReport.class-pdf',
            compact(
                'students',
                'class',
                'section',
                'academicYear',
                'schoolSetting'
            )
        )->setPaper('a4', 'landscape')->download($fileName);
    }

    /**
     * Individual Student Report Profile
     */
    public function profile(Student $student)
    {
        $student->load([
            'attendances' => function ($query) {
                $query->orderByDesc('attendance_date');
            },
        ]);

        // Attendance calculation: leave and holiday are excluded.
        $attendanceRecords = $student->attendances;

        $workingDays = $attendanceRecords->whereIn('status', [
            'present',
            'late',
            'half_day',
            'absent',
        ]);

        $presentDays = $workingDays->where('status', 'present')->count();
        $lateDays = $workingDays->where('status', 'late')->count();
        $halfDays = $workingDays->where('status', 'half_day')->count();
        $absentDays = $workingDays->where('status', 'absent')->count();

        $totalWorkingDays = $workingDays->count();
        $attendedDays = $presentDays + $lateDays + ($halfDays * 0.5);

        $attendancePercentage = $totalWorkingDays > 0
            ? round(($attendedDays / $totalWorkingDays) * 100, 2)
            : 0;

        // Transport records.
        $transportRecords = TransportRecord::where('student_id', $student->id)
            ->orderByDesc('start_date')
            ->get();

        // Scholarship exam applications.
        $scholarshipApplications = ScholarshipExamApplication::with('exam')
            ->where('student_id', $student->id)
            ->latest()
            ->get();

        // Fee records and their payment history.
        $feeRecords = StudentFee::with(['feeStructure', 'payments'])
            ->where('student_id', $student->id)
            ->orderByDesc('academic_year')
            ->get();

        // Sports achievements are matched using the existing name, class and section fields.
        $sportsAchievements = Achievement::query()
            ->where('student_name', $student->full_name)
            ->where('class', $student->class)
            ->where('section', $student->section)
            ->where('status', 'active')
            ->orderByDesc('achievement_date')
            ->get();

        // Library issue and return history.
        $libraryRecords = BookIssue::with('book')
            ->where('student_id', $student->id)
            ->orderByDesc('issue_date')
            ->get();

        $schoolSetting = \App\Models\SchoolSetting::query()->first();

        return view('allReports.studentReport.profile', compact(
            'student',
            'attendanceRecords',
            'presentDays',
            'lateDays',
            'halfDays',
            'absentDays',
            'totalWorkingDays',
            'attendedDays',
            'attendancePercentage',
            'transportRecords',
            'scholarshipApplications',
            'feeRecords',
            'sportsAchievements',
            'libraryRecords',
            'schoolSetting'
        ));
    }
    /**
     * Existing Student Report PDF
     */
    /**
     * Export Individual Student Profile to Excel
     */
    public function profileExcel(Student $student)
    {
        $student->load('attendances');

        $attendanceRecords = $student->attendances;

        $workingDays = $attendanceRecords->whereIn('status', [
            'present', 'late', 'half_day', 'absent'
        ]);

        $presentDays = $workingDays->where('status', 'present')->count();
        $lateDays = $workingDays->where('status', 'late')->count();
        $halfDays = $workingDays->where('status', 'half_day')->count();
        $absentDays = $workingDays->where('status', 'absent')->count();

        $totalWorkingDays = $workingDays->count();
        $attendedDays = $presentDays + $lateDays + ($halfDays * 0.5);

        $attendancePercentage = $totalWorkingDays > 0
            ? round(($attendedDays / $totalWorkingDays) * 100, 2)
            : 0;

        $transportRecords = TransportRecord::where('student_id', $student->id)
            ->orderByDesc('start_date')->get();

        $scholarshipApplications = ScholarshipExamApplication::with('exam')
            ->where('student_id', $student->id)->latest()->get();

        $feeRecords = StudentFee::with(['feeStructure', 'payments'])
            ->where('student_id', $student->id)
            ->orderByDesc('academic_year')->get();

        $sportsAchievements = Achievement::where('student_name', $student->full_name)
            ->where('class', $student->class)
            ->where('section', $student->section)
            ->where('status', 'active')
            ->orderByDesc('achievement_date')->get();

        $libraryRecords = BookIssue::with('book')
            ->where('student_id', $student->id)
            ->orderByDesc('issue_date')->get();

        $rows = [
            ['STUDENT PROFILE REPORT'],
            ['Student Name', $student->full_name],
            ['Student ID', $student->student_id],
            ['Roll Number', $student->roll_number],
            ['Class', $student->class],
            ['Section', $student->section],
            ['Academic Year', $student->academic_year],
            ['Gender', $student->gender],
            ['Date of Birth', $student->date_of_birth?->format('d-m-Y')],
            ['Phone', $student->phone],
            ['Email', $student->email],
            ['Aadhaar Number', $student->aadhar_card_no],
            ['Admission Class', $student->admission_class],
            ['Admission Date', $student->admission_date?->format('d-m-Y')],
            ['Medium', $student->medium],
            ['Mother Tongue', $student->mother_tongue],
            ['Nationality', $student->nationality],
            ['Religion', $student->religion],
            ['Caste', $student->caste],
            ['Sub Caste', $student->sub_caste],
            [],
            ['PARENT / GUARDIAN DETAILS'],
            ['Father Name', $student->father_name],
            ['Father Phone', $student->father_phone],
            ['Father Occupation', $student->father_occupation],
            ['Mother Name', $student->mother_name],
            ['Mother Phone', $student->mother_phone],
            ['Mother Occupation', $student->mother_occupation],
            ['Guardian Name', $student->guardian_name],
            ['Guardian Relation', $student->guardian_relation],
            ['Guardian Phone', $student->guardian_phone],
            [],
            ['ADDRESS DETAILS'],
            ['Address', $student->address],
            ['City / Village', $student->city_village],
            ['Taluka', $student->taluka],
            ['District', $student->district],
            ['State', $student->state],
            ['Country', $student->country],
            ['Pincode', $student->pincode],
            [],
            ['ATTENDANCE SUMMARY'],
            ['Present Days', $presentDays],
            ['Late Days', $lateDays],
            ['Half Days', $halfDays],
            ['Absent Days', $absentDays],
            ['Total Working Days', $totalWorkingDays],
            ['Attended Equivalent Days', $attendedDays],
            ['Attendance Percentage', $attendancePercentage . '%'],
            [],
            ['ATTENDANCE HISTORY'],
            ['Date', 'Status', 'Remarks'],
        ];

        foreach ($attendanceRecords->sortBy('attendance_date') as $record) {
            $rows[] = [
                $record->attendance_date?->format('d-m-Y'),
                $record->status,
                $record->remarks,
            ];
        }

        $rows[] = [];
        $rows[] = ['TRANSPORT DETAILS'];
        $rows[] = ['Route', 'Vehicle', 'Pickup Point', 'Drop Point', 'Status', 'Start Date', 'End Date', 'Transport Fee', 'Payment Status'];

        foreach ($transportRecords as $record) {
            $rows[] = [
                $record->route,
                $record->vehicle,
                $record->pickup_point,
                $record->drop_point,
                $record->transport_status,
                $record->start_date?->format('d-m-Y'),
                $record->end_date?->format('d-m-Y'),
                $record->transport_fee,
                $record->payment_status,
            ];
        }

        $rows[] = [];
        $rows[] = ['SCHOLARSHIP EXAM APPLICATIONS'];
        $rows[] = ['Exam Name', 'Exam Date', 'Academic Year', 'Application Status'];

        foreach ($scholarshipApplications as $application) {
            $rows[] = [
                $application->exam?->exam_name,
                $application->exam?->exam_date?->format('d-m-Y'),
                $application->exam?->academic_year,
                $application->status,
            ];
        }

        $rows[] = [];
        $rows[] = ['FEE DETAILS'];
        $rows[] = ['Academic Year', 'Fee Structure', 'Total Amount', 'Discount', 'Fine', 'Paid Amount', 'Balance', 'Status', 'Due Date'];

        foreach ($feeRecords as $fee) {
            $rows[] = [
                $fee->academic_year,
                $fee->feeStructure?->structure_name,
                $fee->total_amount,
                $fee->discount_amount,
                $fee->fine_amount,
                $fee->paid_amount,
                $fee->balance_amount,
                $fee->status,
                $fee->due_date?->format('d-m-Y'),
            ];

            $rows[] = ['Payment History', 'Receipt Number', 'Amount', 'Payment Date', 'Method', 'Reference'];

            foreach ($fee->payments as $payment) {
                $rows[] = [
                    '',
                    $payment->receipt_number,
                    $payment->amount,
                    $payment->payment_date?->format('d-m-Y'),
                    $payment->payment_method,
                    $payment->transaction_reference,
                ];
            }
        }

        $rows[] = [];
        $rows[] = ['SPORTS ACHIEVEMENTS'];
        $rows[] = ['Sport', 'Achievement', 'Competition', 'Position', 'Date', 'Venue', 'Academic Year'];

        foreach ($sportsAchievements as $achievement) {
            $rows[] = [
                $achievement->sport_name,
                $achievement->title,
                $achievement->competition_name,
                $achievement->position,
                $achievement->achievement_date?->format('d-m-Y'),
                $achievement->venue,
                $achievement->academic_year,
            ];
        }

        $rows[] = [];
        $rows[] = ['LIBRARY HISTORY'];
        $rows[] = ['Book', 'Issue Date', 'Due Date', 'Return Date', 'Status', 'Fine'];

        foreach ($libraryRecords as $record) {
            $rows[] = [
                $record->book?->title ?? $record->book?->book_name ?? '—',
                $record->issue_date?->format('d-m-Y'),
                $record->due_date?->format('d-m-Y'),
                $record->return_date?->format('d-m-Y'),
                $record->status,
                $record->fine,
            ];
        }

        return Excel::download(
            new StudentProfileReportExport($rows),
            'student-profile-' . $student->id . '.xlsx'
        );
    }
    /**
     * Download Individual Student Profile PDF
     */
    public function profilePdf(Student $student)
    {
        $student->load([
            'attendances' => function ($query) {
                $query->orderByDesc('attendance_date');
            },
        ]);

        $attendanceRecords = $student->attendances;

        $workingDays = $attendanceRecords->whereIn('status', [
            'present',
            'late',
            'half_day',
            'absent',
        ]);

        $presentDays = $workingDays->where('status', 'present')->count();
        $lateDays = $workingDays->where('status', 'late')->count();
        $halfDays = $workingDays->where('status', 'half_day')->count();
        $absentDays = $workingDays->where('status', 'absent')->count();

        $totalWorkingDays = $workingDays->count();
        $attendedDays = $presentDays + $lateDays + ($halfDays * 0.5);

        $attendancePercentage = $totalWorkingDays > 0
            ? round(($attendedDays / $totalWorkingDays) * 100, 2)
            : 0;

        $transportRecords = TransportRecord::where('student_id', $student->id)
            ->orderByDesc('start_date')
            ->get();

        $scholarshipApplications = ScholarshipExamApplication::with('exam')
            ->where('student_id', $student->id)
            ->latest()
            ->get();

        $feeRecords = StudentFee::with(['feeStructure', 'payments'])
            ->where('student_id', $student->id)
            ->orderByDesc('academic_year')
            ->get();

        $sportsAchievements = Achievement::query()
            ->where('student_name', $student->full_name)
            ->where('class', $student->class)
            ->where('section', $student->section)
            ->where('status', 'active')
            ->orderByDesc('achievement_date')
            ->get();

        $libraryRecords = BookIssue::with('book')
            ->where('student_id', $student->id)
            ->orderByDesc('issue_date')
            ->get();

        $schoolSetting = \App\Models\SchoolSetting::query()->first();

        $pdf = Pdf::loadView('allReports.studentReport.profile-pdf', compact(
            'student',
            'attendanceRecords',
            'presentDays',
            'lateDays',
            'halfDays',
            'absentDays',
            'totalWorkingDays',
            'attendedDays',
            'attendancePercentage',
            'transportRecords',
            'scholarshipApplications',
            'feeRecords',
            'sportsAchievements',
            'libraryRecords',
            'schoolSetting'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('student-profile-' . $student->id . '.pdf');
    }
    public function pdf(Request $request)
    {
        $query = Student::query();

        // Student Search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('student_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('aadhar_card_no', 'like', "%{$search}%");

            });
        }

        // Academic Year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // Class
        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        // Section
        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        // Gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query
            ->orderBy('class')
            ->orderBy('section')
            ->orderBy('roll_number')
            ->get();

        // Attendance and Library Records
        foreach ($students as $student) {

            $student->attendanceRecords = Attendance::where(
                'student_id',
                $student->id
            )
                ->orderBy('attendance_date')
                ->get();

            $student->libraryRecords = BookIssue::with('book')
                ->where('student_id', $student->id)
                ->orderBy('issue_date')
                ->get();
        }

        return Pdf::loadView(
            'allReports.studentReport.pdf',
            compact('students')
        )->download('student-report.pdf');
    }
}
