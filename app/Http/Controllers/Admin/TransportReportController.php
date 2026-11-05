<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolSetting;
use App\Models\Class\SchoolClass;
use App\Models\Transport\TransportRecord;
use App\Models\Transport\TransportRoute;
use App\Models\Transport\TransportVehicle;
use App\Exports\TransportRouteReportExport;
use App\Exports\StudentTransportReportExport;
use App\Exports\StudentTravelReportExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class TransportReportController extends Controller
{
    /**
     * Main Transport Report
     */
    public function index(Request $request)
    {
        if ($request->filled('student_search')) {

            $search = trim((string) $request->student_search);

            $students = Student::query()
                ->where(function ($query) use ($search) {
                    $query
                        ->where('student_id', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereRaw(
                            "CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?",
                            ["%{$search}%"]
                        );
                })
                ->orderBy('first_name')
                ->orderBy('middle_name')
                ->orderBy('last_name')
                ->limit(10)
                ->get([
                    'id',
                    'student_id',
                    'first_name',
                    'middle_name',
                    'last_name',
                ]);

            return response()->json([
                'success' => true,
                'students' => $students->map(function ($student) {

                    $fullName = trim(
                        collect([
                            $student->first_name,
                            $student->middle_name,
                            $student->last_name,
                        ])
                            ->filter(fn ($value) =>
                                $value !== null &&
                                trim((string) $value) !== ''
                            )
                            ->implode(' ')
                    );

                    return [
                        'id' => $student->id,
                        'student_id' => $student->student_id ?? $student->id,
                        'name' => $fullName,
                    ];
                })->values(),
                'count' => $students->count(),
            ]);
        }

        $query = TransportRecord::with('student')->latest();

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('transport_status')) {
            $query->where('transport_status', $request->transport_status);
        }

        if ($request->filled('transport_type')) {
            $query->where('transport_type', $request->transport_type);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('start_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('start_date', '<=', $request->to_date);
        }

        $records = $query->get();

        $selectedStudent = null;

        if ($request->filled('student_id')) {
            $selectedStudent = Student::find($request->student_id);
        }

        $totalRecords = $records->count();

        $activeRecords = $records
            ->where('transport_status', 'active')
            ->count();

        $inactiveRecords = $records
            ->where('transport_status', 'inactive')
            ->count();

        $assignedStudents = $records
            ->whereNotNull('student_id')
            ->count();

        $totalTransportFees = $records->sum('transport_fee');

        $pendingPayments = $records
            ->whereIn('payment_status', ['pending', 'partially_paid'])
            ->count();

        return view(
            'allReports.transportReport.index',
            compact(
                'records',
                'selectedStudent',
                'totalRecords',
                'activeRecords',
                'inactiveRecords',
                'assignedStudents',
                'totalTransportFees',
                'pendingPayments'
            )
        );
    }

    /**
     * Student Travel Report
     */
    public function studentTravel()
    {
        $records = TransportRecord::with('student')
            ->latest()
            ->get();

        return view(
            'allReports.transportReport.student-travel',
            compact('records')
        );
    }

    /**
     * Student Travel PDF
     */
    public function studentTravelPdf()
    {
        $records = TransportRecord::with('student')
            ->latest()
            ->get();

        $pdf = Pdf::loadView(
            'allReports.transportReport.student-travel-pdf',
            compact('records')
        );

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('student-travel-report.pdf');
    }

    /**
     * Student Travel Excel
     */
    public function studentTravelExcel()
    {
        return Excel::download(
            new StudentTravelReportExport,
            'student-travel-report.xlsx'
        );
    }

    /**
     * Existing Vehicle Summary Report
     */
    public function vehicle()
    {
        $vehicles = TransportRecord::query()
            ->selectRaw('
                COALESCE(vehicle, "Not Assigned") as vehicle,
                COALESCE(transport_type, "other") as transport_type,
                COUNT(*) as assigned_students,
                SUM(
                    CASE
                        WHEN transport_status = "active"
                        THEN 1 ELSE 0
                    END
                ) as active_students,
                SUM(
                    CASE
                        WHEN transport_status = "inactive"
                        THEN 1 ELSE 0
                    END
                ) as inactive_students,
                COALESCE(SUM(transport_fee), 0) as total_fees
            ')
            ->groupBy('vehicle', 'transport_type')
            ->orderBy('vehicle')
            ->get();

        return view(
            'allReports.transportReport.vehicle',
            compact('vehicles')
        );
    }

    /**
     * Existing Vehicle Summary PDF
     */
    public function vehiclePdf()
    {
        $vehicles = TransportRecord::query()
            ->selectRaw('
                COALESCE(vehicle, "Not Assigned") as vehicle,
                COALESCE(transport_type, "other") as transport_type,
                COUNT(*) as assigned_students,
                SUM(
                    CASE
                        WHEN transport_status = "active"
                        THEN 1 ELSE 0
                    END
                ) as active_students,
                SUM(
                    CASE
                        WHEN transport_status = "inactive"
                        THEN 1 ELSE 0
                    END
                ) as inactive_students,
                COALESCE(SUM(transport_fee), 0) as total_fees
            ')
            ->groupBy('vehicle', 'transport_type')
            ->orderBy('vehicle')
            ->get();

        $pdf = Pdf::loadView(
            'allReports.transportReport.vehicle-pdf',
            compact('vehicles')
        );

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('vehicle-report.pdf');
    }

    /**
     * Existing Vehicle Summary Excel
     */
    public function vehicleExcel()
    {
        return Excel::download(
            new \App\Exports\VehicleReportExport,
            'vehicle-report.xlsx'
        );
    }

    /**
     * Registered Routes Report
     */
    public function routes()
    {
        $routes = TransportRoute::with(['vehicle.driver', 'driver'])
            ->orderBy('route_name')
            ->get();

        foreach ($routes as $route) {

            $vehicleNumber = $route->vehicle?->vehicle_number
                ?? $route->assigned_vehicle;

            $route->students_count = $vehicleNumber
                ? TransportRecord::where('transport_status', 'active')
                    ->whereRaw(
                        'LOWER(TRIM(vehicle)) = ?',
                        [mb_strtolower(trim($vehicleNumber))]
                    )
                    ->distinct('student_id')
                    ->count('student_id')
                : 0;
        }

        return view(
            'allReports.transportReport.routes',
            compact('routes')
        );
    }

    /**
     * Individual Registered Route Report
     */
    public function routeShow($id)
    {
        $route = TransportRoute::with([
            'vehicle.driver',
            'driver',
        ])->findOrFail($id);

        $vehicleNumber = $route->vehicle?->vehicle_number
            ?? $route->assigned_vehicle;

        $students = collect();

        if ($vehicleNumber) {
            $students = TransportRecord::with('student')
                ->where('transport_status', 'active')
                ->whereRaw(
                    'LOWER(TRIM(vehicle)) = ?',
                    [mb_strtolower(trim($vehicleNumber))]
                )
                ->latest()
                ->get();
        }

        $stops = collect(
            preg_split('/[\r\n,]+/', $route->stops ?? '')
        )
            ->map(fn ($stop) => trim($stop))
            ->filter()
            ->values();

        $school = SchoolSetting::first();

        return view(
            'allReports.transportReport.routes.show',
            compact('route', 'students', 'stops', 'school')
        );
    }

    /**
     * Individual Route PDF Report
     */
    public function routePdf($id)
    {
        $route = TransportRoute::with([
            'vehicle.driver',
            'driver',
        ])->findOrFail($id);

        $vehicleNumber = $route->vehicle?->vehicle_number
            ?? $route->assigned_vehicle;

        $students = collect();

        if ($vehicleNumber) {
            $students = TransportRecord::with('student')
                ->where('transport_status', 'active')
                ->whereRaw(
                    'LOWER(TRIM(vehicle)) = ?',
                    [mb_strtolower(trim($vehicleNumber))]
                )
                ->latest()
                ->get();
        }

        $stops = collect(
            preg_split('/[\r\n,]+/', $route->stops ?? '')
        )
            ->map(fn ($stop) => trim($stop))
            ->filter()
            ->values();

        $school = SchoolSetting::first();

        return Pdf::loadView(
            'allReports.transportReport.routes.pdf',
            compact('route', 'students', 'stops', 'school')
        )
            ->setPaper('a4', 'landscape')
            ->download('route-report-' . $route->route_number . '.pdf');
    }

    /**
     * Individual Route Excel Report
     */
    public function routeExcel($id)
    {
        $route = TransportRoute::findOrFail($id);

        return Excel::download(
            new TransportRouteReportExport($id),
            'route-report-' . $route->route_number . '.xlsx'
        );
    }

    // =========================================================
    // REGISTERED VEHICLES REPORT
    // =========================================================

    /**
     * Registered Vehicles List
     */
    public function vehicles()
    {
        $vehicles = TransportVehicle::with('driver')
            ->withCount('routes')
            ->orderBy('vehicle_number')
            ->get();

        foreach ($vehicles as $vehicle) {

            $number = trim((string) $vehicle->vehicle_number);

            $records = $number !== ''
                ? TransportRecord::whereRaw(
                    'LOWER(TRIM(vehicle)) = ?',
                    [mb_strtolower($number)]
                )->get()
                : collect();

            $vehicle->assigned_students = $records
                ->whereNotNull('student_id')
                ->unique('student_id')
                ->count();

            $vehicle->active_students = $records
                ->where('transport_status', 'active')
                ->whereNotNull('student_id')
                ->unique('student_id')
                ->count();

            $vehicle->inactive_students = $records
                ->where('transport_status', 'inactive')
                ->whereNotNull('student_id')
                ->unique('student_id')
                ->count();
        }

        return view(
            'allReports.transportReport.vehicles.index',
            compact('vehicles')
        );
    }

    /**
     * Individual Registered Vehicle Report
     */
    public function vehicleShow($id)
    {
        $vehicle = TransportVehicle::with('driver')
            ->findOrFail($id);

        $routes = TransportRoute::with([
            'vehicle.driver',
            'driver',
        ])
            ->where(function ($query) use ($vehicle) {
                $query->where('vehicle_id', $vehicle->id)
                    ->orWhere(
                        'assigned_vehicle',
                        $vehicle->vehicle_number
                    );
            })
            ->orderBy('route_name')
            ->get();

        $number = trim((string) $vehicle->vehicle_number);

        $students = $number !== ''
            ? TransportRecord::with('student')
                ->whereRaw(
                    'LOWER(TRIM(vehicle)) = ?',
                    [mb_strtolower($number)]
                )
                ->latest()
                ->get()
            : collect();

        $activeStudents = $students
            ->where('transport_status', 'active')
            ->whereNotNull('student_id')
            ->unique('student_id')
            ->count();

        $inactiveStudents = $students
            ->where('transport_status', 'inactive')
            ->whereNotNull('student_id')
            ->unique('student_id')
            ->count();

        $assignedStudents = $students
            ->whereNotNull('student_id')
            ->unique('student_id')
            ->count();

        $totalTransportFees = $students->sum('transport_fee');

        $school = SchoolSetting::first();

        return view(
            'allReports.transportReport.vehicles.show',
            compact(
                'vehicle',
                'routes',
                'students',
                'activeStudents',
                'inactiveStudents',
                'assignedStudents',
                'totalTransportFees',
                'school'
            )
        );
    }

    /**
     * Individual Registered Vehicle PDF Report
     */
    public function vehicleIndividualPdf($id)
    {
        $vehicle = TransportVehicle::with('driver')
            ->findOrFail($id);

        $routes = TransportRoute::with([
            'vehicle.driver',
            'driver',
        ])
            ->where(function ($query) use ($vehicle) {
                $query->where('vehicle_id', $vehicle->id)
                    ->orWhere(
                        'assigned_vehicle',
                        $vehicle->vehicle_number
                    );
            })
            ->orderBy('route_name')
            ->get();

        $number = trim((string) $vehicle->vehicle_number);

        $students = $number !== ''
            ? TransportRecord::with('student')
                ->whereRaw(
                    'LOWER(TRIM(vehicle)) = ?',
                    [mb_strtolower($number)]
                )
                ->latest()
                ->get()
            : collect();

        $activeStudents = $students
            ->where('transport_status', 'active')
            ->whereNotNull('student_id')
            ->unique('student_id')
            ->count();

        $inactiveStudents = $students
            ->where('transport_status', 'inactive')
            ->whereNotNull('student_id')
            ->unique('student_id')
            ->count();

        $assignedStudents = $students
            ->whereNotNull('student_id')
            ->unique('student_id')
            ->count();

        $totalTransportFees = $students->sum('transport_fee');

        $school = SchoolSetting::first();

        return Pdf::loadView(
            'allReports.transportReport.vehicles.pdf',
            compact(
                'vehicle',
                'routes',
                'students',
                'activeStudents',
                'inactiveStudents',
                'assignedStudents',
                'totalTransportFees',
                'school'
            )
        )
            ->setPaper('a4', 'landscape')
            ->download(
                'vehicle-report-' . $vehicle->vehicle_number . '.pdf'
            );
    }

    /**
     * Individual Registered Vehicle Excel Report
     */
    public function vehicleIndividualExcel($id)
    {
        $vehicle = TransportVehicle::findOrFail($id);

        return Excel::download(
            new \App\Exports\VehicleIndividualReportExport($id),
            'vehicle-report-' . $vehicle->vehicle_number . '.xlsx'
        );
    }

    // =========================================================
    // STUDENT TRANSPORT REPORT
    // =========================================================

    /**
     * Student Transport Report - Class-wise Dashboard
     */
    public function studentTransportIndex()
    {
        $transportStudentIds = TransportRecord::query()
            ->whereNotNull('student_id')
            ->select('student_id')
            ->distinct();

        $students = Student::query()
            ->whereIn('id', $transportStudentIds)
            ->get([
                'id',
                'student_id',
                'first_name',
                'middle_name',
                'last_name',
                'class',
                'section',
                'academic_year',
            ]);

        $normalizeClass = static function ($value) {
            $value = mb_strtolower(trim((string) $value));

            return trim(preg_replace('/^class\s*/i', '', $value));
        };

        // Group by normalized class only, not academic year.
        $classCards = $students
            ->groupBy(fn ($student) => $normalizeClass($student->class))
            ->map(function ($group) use ($normalizeClass) {
                $first = $group->first();
                $className = $normalizeClass($first->class);

                return (object) [
                    'class_value' => $className,
                    'class_name' => $className !== ''
                        ? 'Class ' . $className
                        : 'Class Not Assigned',
                    'student_count' => $group->unique('id')->count(),
                    'section_count' => $group->pluck('section')
                        ->filter()
                        ->unique()
                        ->count(),
                    'academic_years' => $group->pluck('academic_year')
                        ->filter()
                        ->unique()
                        ->values(),
                ];
            })
            ->sortBy('class_name')
            ->values();

        $totalTransportStudents = $students->unique('id')->count();

        $totalTransportRecords = TransportRecord::count();

        $activeTransportStudents = TransportRecord::query()
            ->where('transport_status', 'active')
            ->whereNotNull('student_id')
            ->distinct()
            ->count('student_id');

        $school = SchoolSetting::first();

        return view(
            'allReports.transportReport.students.index',
            compact(
                'classCards',
                'totalTransportStudents',
                'totalTransportRecords',
                'activeTransportStudents',
                'school'
            )
        );
    }

    /**
     * Student Transport Report - Class, Section and Academic Year Filter
     */
    public function studentTransportClass(Request $request)
    {
        $classValue = mb_strtolower(trim((string) $request->query('class', '')));
        $classValue = trim(preg_replace('/^class\s*/i', '', $classValue));

        abort_if($classValue === '', 404);

        $baseQuery = Student::query()
            ->whereHas('transportRecords')
            ->where(function ($query) use ($classValue) {
                $query->whereRaw('TRIM(LOWER(class)) = ?', [$classValue])
                    ->orWhereRaw('TRIM(LOWER(class)) = ?', ['class ' . $classValue]);
            });

        $filterStudents = (clone $baseQuery)->get([
            'id',
            'student_id',
            'first_name',
            'middle_name',
            'last_name',
            'class',
            'section',
            'academic_year'
        ]);

        $sections = $filterStudents->pluck('section')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $academicYears = $filterStudents->pluck('academic_year')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $section = trim((string) $request->query('section', ''));
        $academicYear = trim((string) $request->query('academic_year', ''));

        $students = collect();

        if ($section !== '' || $academicYear !== '') {
            $query = clone $baseQuery;

            if ($section !== '') {
                $query->where('section', $section);
            }

            if ($academicYear !== '') {
                $query->where('academic_year', $academicYear);
            }

            $students = $query->with('transportRecords')
                ->orderBy('first_name')
                ->get();
        }

        $school = SchoolSetting::first();
        $className = 'Class ' . $classValue;

        return view(
            'allReports.transportReport.students.class-students',
            compact(
                'classValue',
                'className',
                'sections',
                'academicYears',
                'section',
                'academicYear',
                'students',
                'school'
            )
        );
    }

    /**
     * Individual Student Transport Profile
     */
    public function studentTransportShow($id)
    {
        $student = Student::with([
            'transportRecords' => function ($query) {
                $query->orderByDesc('start_date')
                    ->orderByDesc('id');
            }
        ])->findOrFail($id);

        $school = SchoolSetting::first();

        return view(
            'allReports.transportReport.students.show',
            compact('student', 'school')
        );
    }

    /**
     * Individual Student Transport PDF Report
     */
    public function studentTransportPdf($id)
    {
        $student = Student::with([
            'transportRecords' => function ($query) {
                $query->orderByDesc('start_date')
                    ->orderByDesc('id');
            }
        ])->findOrFail($id);

        $school = SchoolSetting::first();

        $pdf = Pdf::loadView(
            'allReports.transportReport.students.pdf',
            compact('student', 'school')
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'student-transport-' . ($student->student_id ?: $student->id) . '.pdf'
        );
    }

    /**
     * Individual Student Transport Excel Report
     */
    public function studentTransportExcel($id)
    {
        $student = Student::findOrFail($id);

        return Excel::download(
            new StudentTransportReportExport((int) $student->id),
            'student-transport-' . ($student->student_id ?: $student->id) . '.xlsx'
        );
    }
}