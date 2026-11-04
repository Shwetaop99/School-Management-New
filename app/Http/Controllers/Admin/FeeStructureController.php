<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeStructure;
use App\Models\FeeStructureItem;
use App\Models\FeeType;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FeeStructureController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        // UPDATE 1: check the filters first
        $request->validate([
            'search'        => ['nullable', 'string', 'max:100'],
            'academic_year' => ['nullable', 'string', 'max:50'],
            'class_id'      => ['nullable', 'integer'],
            'status'        => ['nullable', Rule::in(['Active', 'Inactive'])],
        ]);

        $query = FeeStructure::with([
            'schoolClass',
            'section',
            'items.feeType',
        ]);

        // SEARCH
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('structure_name', 'like', "%{$search}%")
                    ->orWhere('academic_year', 'like', "%{$search}%")
                    ->orWhereHas('schoolClass', function ($classQuery) use ($search) {
                        $classQuery->where('class_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('section', function ($sectionQuery) use ($search) {
                        $sectionQuery->where('section_name', 'like', "%{$search}%");
                    });
            });
        }

        // ACADEMIC YEAR FILTER
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // CLASS FILTER
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // STATUS FILTER
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $feeStructures = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // FILTER DATA
        $academicYears = Student::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->select('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $classes = DB::table('classes')
            ->orderBy('class_name')
            ->get();

        // DASHBOARD COUNTS
        $totalStructures    = FeeStructure::count();
        $activeStructures   = FeeStructure::where('status', 'Active')->count();
        $inactiveStructures = FeeStructure::where('status', 'Inactive')->count();

        return view(
            'admin.fees.fee-structures.index',
            compact(
                'feeStructures',
                'academicYears',
                'classes',
                'totalStructures',
                'activeStructures',
                'inactiveStructures'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $academicYears = Student::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->select('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $classes = DB::table('classes')
            ->orderBy('class_name')
            ->get();

        $feeTypes = FeeType::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        return view(
            'admin.fees.fee-structures.create',
            compact('academicYears', 'classes', 'feeTypes')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate($this->structureRules());

        // UPDATE 2: extra checks (section, list sizes, duplicates)
        $this->extraChecks($validated, null);

        $feeStructure = DB::transaction(function () use ($validated) {

            $feeStructure = FeeStructure::create([
                'academic_year'  => $validated['academic_year'],
                'class_id'       => $validated['class_id'],
                'section_id'     => $validated['section_id'] ?? null,
                'structure_name' => $validated['structure_name'],
                'status'         => $validated['status'],
            ]);

            $this->saveItems($feeStructure, $validated);

            return $feeStructure;
        });

        // UPDATE 3: write a log entry for fee amounts
        $this->logChange($feeStructure, 'Fee structure created', $validated);

        return redirect()
            ->route('admin.fees.fee-structures.index')
            ->with('success', 'Fee structure created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(FeeStructure $feeStructure)
    {
        $feeStructure->load([
            'schoolClass',
            'section',
            'items.feeType',
        ]);

        return view(
            'admin.fees.fee-structures.show',
            compact('feeStructure')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(FeeStructure $feeStructure)
    {
        $feeStructure->load([
            'schoolClass',
            'section',
            'items.feeType',
        ]);

        $academicYears = Student::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->select('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $classes = DB::table('classes')
            ->orderBy('class_name')
            ->get();

        $feeTypes = FeeType::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        return view(
            'admin.fees.fee-structures.edit',
            compact('feeStructure', 'academicYears', 'classes', 'feeTypes')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, FeeStructure $feeStructure)
    {
        // UPDATE 4: already assigned to students? Only name + status can change.
        $alreadyAssigned = StudentFee::where('fee_structure_id', $feeStructure->id)
            ->exists();

        if ($alreadyAssigned) {

            $data = $request->validate([
                'structure_name' => ['required', 'string', 'max:255'],
                'status'         => ['required', Rule::in(['Active', 'Inactive'])],
            ]);

            $feeStructure->update($data);

            $this->logChange($feeStructure, 'Fee structure name/status updated', $data);

            return redirect()
                ->route('admin.fees.fee-structures.show', $feeStructure)
                ->with(
                    'success',
                    'This structure is already assigned to students, so only the name and status were updated. To change amounts, create a new fee structure.'
                );
        }

        $validated = $request->validate($this->structureRules());

        $this->extraChecks($validated, $feeStructure->id);

        DB::transaction(function () use ($validated, $feeStructure) {

            $feeStructure->update([
                'academic_year'  => $validated['academic_year'],
                'class_id'       => $validated['class_id'],
                'section_id'     => $validated['section_id'] ?? null,
                'structure_name' => $validated['structure_name'],
                'status'         => $validated['status'],
            ]);

            // Remove old items, then save the new ones
            $feeStructure->items()->delete();

            $this->saveItems($feeStructure, $validated);
        });

        $this->logChange($feeStructure, 'Fee structure updated', $validated);

        return redirect()
            ->route('admin.fees.fee-structures.show', $feeStructure)
            ->with('success', 'Fee structure updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(FeeStructure $feeStructure)
    {
        // UPDATE 5: a structure used by students must never be deleted
        if (StudentFee::where('fee_structure_id', $feeStructure->id)->exists()) {
            return back()->with(
                'error',
                'This fee structure is assigned to students, so it cannot be deleted. You can set it to Inactive instead.'
            );
        }

        $this->logChange($feeStructure, 'Fee structure deleted', [
            'structure_name' => $feeStructure->structure_name,
        ]);

        DB::transaction(function () use ($feeStructure) {
            $feeStructure->items()->delete();
            $feeStructure->delete();
        });

        return redirect()
            ->route('admin.fees.fee-structures.index')
            ->with('success', 'Fee structure deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD SECTIONS
    |--------------------------------------------------------------------------
    */

    public function sections($classId)
    {
        $classId = (int) $classId;

        $sections = Section::query()
            ->where('class_id', $classId)
            ->orderBy('section_name')
            ->get(['id', 'section_name']);

        return response()->json($sections);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS (used by store + update)
    |--------------------------------------------------------------------------
    */

    private function structureRules(): array
    {
        return [
            'academic_year'  => ['required', 'string', 'max:50'],
            'class_id'       => ['required', 'integer', 'exists:classes,id'],
            'section_id'     => ['nullable', 'integer', 'exists:sections,id'],
            'structure_name' => ['required', 'string', 'max:255'],
            'status'         => ['required', Rule::in(['Active', 'Inactive'])],

            'fee_type_id'   => ['required', 'array', 'min:1', 'max:30'],

            // must be "manual" or a real fee type
            'fee_type_id.*' => [
                'required',
                function ($attribute, $value, $fail) {

                    if ($value === 'manual') {
                        return;
                    }

                    if (
                        !is_scalar($value) ||
                        !ctype_digit((string) $value) ||
                        !FeeType::whereKey((int) $value)->exists()
                    ) {
                        $fail('Selected fee type is not valid.');
                    }
                },
            ],

            'manual_fee_name'   => ['nullable', 'array'],
            'manual_fee_name.*' => ['nullable', 'string', 'max:255'],

            'amount'   => ['required', 'array', 'min:1', 'max:30'],
            'amount.*' => ['required', 'numeric', 'min:0', 'max:9999999', 'decimal:0,2'],

            'due_date'   => ['nullable', 'array'],
            'due_date.*' => ['nullable', 'date_format:Y-m-d'],
        ];
    }

    private function extraChecks(array $validated, ?int $ignoreId): void
    {
        // Section must belong to the selected class
        if (!empty($validated['section_id'])) {

            $sectionOk = Section::query()
                ->where('id', $validated['section_id'])
                ->where('class_id', $validated['class_id'])
                ->exists();

            if (!$sectionOk) {
                throw ValidationException::withMessages([
                    'section_id' => 'The selected section does not belong to the selected class.',
                ]);
            }
        }

        // Fee types and amounts must have the same number of rows
        if (count($validated['fee_type_id']) !== count($validated['amount'])) {
            throw ValidationException::withMessages([
                'amount' => 'Every fee row needs an amount.',
            ]);
        }

        // Same fee type cannot be added twice
        $ids = array_filter(
            $validated['fee_type_id'],
            fn ($v) => $v !== 'manual'
        );

        if (count($ids) !== count(array_unique($ids))) {
            throw ValidationException::withMessages([
                'fee_type_id' => 'The same fee type is added more than once.',
            ]);
        }

        // Manual rows need a name
        foreach ($validated['fee_type_id'] as $index => $feeTypeId) {
            if (
                $feeTypeId === 'manual' &&
                trim($validated['manual_fee_name'][$index] ?? '') === ''
            ) {
                throw ValidationException::withMessages([
                    'manual_fee_name' => 'Manual fee name is required.',
                ]);
            }
        }

        // Same structure (year + class + section) cannot exist twice
        $duplicate = FeeStructure::query()
            ->where('academic_year', $validated['academic_year'])
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'] ?? null)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'structure_name' => 'A fee structure already exists for this year, class and section.',
            ]);
        }
    }

    private function saveItems(FeeStructure $feeStructure, array $validated): void
    {
        $manualNames = $validated['manual_fee_name'] ?? [];
        $dueDates    = $validated['due_date'] ?? [];

        foreach ($validated['fee_type_id'] as $index => $feeTypeId) {

            $amount  = round((float) $validated['amount'][$index], 2);
            $dueDate = $dueDates[$index] ?? null;

            if ($feeTypeId === 'manual') {

                $manualName = trim($manualNames[$index]);

                // reuse an existing fee type with the same name
                $feeType = FeeType::firstOrCreate(
                    ['name' => $manualName],
                    [
                        'category'    => 'Other',
                        'frequency'   => 'One Time',
                        'amount'      => $amount,
                        'description' => 'Manually created fee type.',
                        'status'      => 'Active',
                    ]
                );

                $feeTypeId = $feeType->id;
            }

            FeeStructureItem::create([
                'fee_structure_id' => $feeStructure->id,
                'fee_type_id'      => (int) $feeTypeId,
                'amount'           => $amount,
                'due_date'         => $dueDate ?: null,
            ]);
        }
    }

    private function logChange(FeeStructure $feeStructure, string $message, array $data): void
    {
        activity()
            ->performedOn($feeStructure)
            ->causedBy(auth()->user())
            ->withProperties([
                'structure_name' => $data['structure_name'] ?? $feeStructure->structure_name,
                'academic_year'  => $data['academic_year'] ?? $feeStructure->academic_year,
                'amounts'        => $data['amount'] ?? null,
                'status'         => $data['status'] ?? $feeStructure->status,
            ])
            ->log($message);
    }
    /**
 * Bulk delete multiple records.
 */
public function bulkDestroy(Request $request)
{
    $request->validate([
        'ids'   => 'required|array|min:1',
        'ids.*' => 'integer|exists:teachers,id',   // ← change table name
    ]);

    $count = Teacher::whereIn('id', $request->ids)->delete();  // ← change Model

    return redirect()
        ->route('admin.teachers.index')                          // ← change route
        ->with('success', $count . ' teacher(s) deleted successfully.');
}

    public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logAll()
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
}

}