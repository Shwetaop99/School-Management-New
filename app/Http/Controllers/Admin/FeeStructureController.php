<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Class\SchoolClass;
use App\Models\FeeStructure;
use App\Models\FeeStructureItem;
use App\Models\FeeType;
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

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('structure_name', 'like', "%{$search}%")
                    ->orWhere('academic_year', 'like', "%{$search}%")
                    ->orWhereHas('schoolClass', function ($classQuery) use ($search) {
                        $classQuery->where('class_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('section', function ($sectionQuery) use ($search) {
                        $sectionQuery->where('section', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $feeStructures = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.fees.fee-structures.index', [
            'feeStructures'      => $feeStructures,
            'academicYears'      => $this->getAcademicYears(),
            'classes'            => $this->getClasses(),
            'totalStructures'    => FeeStructure::count(),
            'activeStructures'   => FeeStructure::where('status', 'Active')->count(),
            'inactiveStructures' => FeeStructure::where('status', 'Inactive')->count(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('admin.fees.fee-structures.create', [
            'academicYears' => $this->getAcademicYears(),
            'classes'       => $this->getClasses(),
            'classSections' => $this->getClassSectionMap(),
            'feeTypes'      => $this->getActiveFeeTypes(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate($this->structureRules());

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

        return view('admin.fees.fee-structures.show', compact('feeStructure'));
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

        return view('admin.fees.fee-structures.edit', [
            'feeStructure'  => $feeStructure,
            'academicYears' => $this->getAcademicYears(),
            'classes'       => $this->getClasses(),
            'classSections' => $this->getClassSectionMap(),
            'feeTypes'      => $this->getActiveFeeTypes(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, FeeStructure $feeStructure)
    {
        $alreadyAssigned = StudentFee::where(
            'fee_structure_id',
            $feeStructure->id
        )->exists();

        if ($alreadyAssigned) {
            $data = $request->validate([
                'structure_name' => ['required', 'string', 'max:255'],
                'status'         => ['required', Rule::in(['Active', 'Inactive'])],
            ]);

            $feeStructure->update($data);

            $this->logChange(
                $feeStructure,
                'Fee structure name/status updated',
                $data
            );

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
        if (
            StudentFee::where('fee_structure_id', $feeStructure->id)->exists()
        ) {
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
    | AJAX: LOAD SECTIONS BY CLASS
    |--------------------------------------------------------------------------
    | Uses school_classes table.
    | One class can have multiple rows, one for each section.
    */
 public function sections($classId)
{
    $class = SchoolClass::query()
        ->where('status', 1)
        ->findOrFail((int) $classId);

    $sections = SchoolClass::query()
        ->where('status', 1)
        ->where('class_name', $class->class_name)
        ->whereNotNull('section')
        ->where('section', '!=', '')
        ->orderBy('section')
        ->get(['id', 'section'])
        ->map(function ($row) {
            return [
                'id' => $row->id,
                'section_name' => $row->section,
            ];
        })
        ->values();

    return response()->json($sections);
}
    /*
    |--------------------------------------------------------------------------
    | VALIDATION RULES
    |--------------------------------------------------------------------------
    */
    private function structureRules(): array
    {
        $classTable = (new SchoolClass())->getTable();

        return [
            'academic_year' => [
                'required',
                'string',
                'max:50',
            ],

            'class_id' => [
                'required',
                'integer',
                Rule::exists($classTable, 'id')->where('status', 1),
            ],

            'section_id' => [
                'nullable',
                'integer',
                Rule::exists($classTable, 'id')->where('status', 1),
            ],

            'structure_name' => [
                'required',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                Rule::in(['Active', 'Inactive']),
            ],

            'fee_type_id' => [
                'required',
                'array',
                'min:1',
                'max:30',
            ],

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

            'manual_fee_name' => [
                'nullable',
                'array',
            ],

            'manual_fee_name.*' => [
                'nullable',
                'string',
                'max:255',
            ],

            'amount' => [
                'required',
                'array',
                'min:1',
                'max:30',
            ],

            'amount.*' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999',
                'decimal:0,2',
            ],

            'due_date' => [
                'nullable',
                'array',
            ],

            'due_date.*' => [
                'nullable',
                'date_format:Y-m-d',
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | EXTRA VALIDATION
    |--------------------------------------------------------------------------
    */
    private function extraChecks(array $validated, ?int $ignoreId): void
    {
        $class = SchoolClass::query()
            ->where('status', 1)
            ->find($validated['class_id']);

        if (!$class) {
            throw ValidationException::withMessages([
                'class_id' => 'Selected class is not available.',
            ]);
        }

        if (!empty($validated['section_id'])) {
            $section = SchoolClass::query()
                ->where('status', 1)
                ->find($validated['section_id']);

            if (
                !$section ||
                $section->class_name !== $class->class_name
            ) {
                throw ValidationException::withMessages([
                    'section_id' => 'The selected section does not belong to the selected class.',
                ]);
            }
        }

        if (
            count($validated['fee_type_id']) !==
            count($validated['amount'])
        ) {
            throw ValidationException::withMessages([
                'amount' => 'Every fee row needs an amount.',
            ]);
        }

        $ids = array_filter(
            $validated['fee_type_id'],
            fn ($value) => $value !== 'manual'
        );

        if (count($ids) !== count(array_unique($ids))) {
            throw ValidationException::withMessages([
                'fee_type_id' => 'The same fee type is added more than once.',
            ]);
        }

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

        $duplicate = FeeStructure::query()
            ->where('academic_year', $validated['academic_year'])
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'] ?? null)
            ->when(
                $ignoreId,
                fn ($q) => $q->where('id', '!=', $ignoreId)
            )
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'structure_name' => 'A fee structure already exists for this year, class and section.',
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE FEE ITEMS
    |--------------------------------------------------------------------------
    */
    private function saveItems(
        FeeStructure $feeStructure,
        array $validated
    ): void {
        $manualNames = $validated['manual_fee_name'] ?? [];
        $dueDates = $validated['due_date'] ?? [];

        foreach ($validated['fee_type_id'] as $index => $feeTypeId) {
            $amount = round((float) $validated['amount'][$index], 2);
            $dueDate = $dueDates[$index] ?? null;

            if ($feeTypeId === 'manual') {
                $manualName = trim($manualNames[$index]);

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

    /*
    |--------------------------------------------------------------------------
    | ACTIVITY LOG
    |--------------------------------------------------------------------------
    */
    private function logChange(
        FeeStructure $feeStructure,
        string $message,
        array $data
    ): void {
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

    /*
    |--------------------------------------------------------------------------
    | PRIVATE HELPERS
    |--------------------------------------------------------------------------
    */

    private function getAcademicYears()
    {
        return Student::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->select('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');
    }

    /*
     * Get only active manually created classes.
     * Return one representative row per class name.
     */
   private function getClasses()
{
    return SchoolClass::query()
        ->where('status', 1)
        ->whereNotNull('class_name')
        ->where('class_name', '!=', '')
        ->orderBy('class_name')
        ->orderBy('id')
        ->get()
        ->unique(fn ($class) => strtolower(trim($class->class_name)))
        ->values();
}

    /*
     * [class_id => [
     *     ['id' => 1, 'section_name' => 'A']
     * ]]
     */
    private function getClassSectionMap(): array
    {
        $map = [];

        $groups = SchoolClass::query()
            ->where('status', 1)
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->orderBy('class_name')
            ->orderBy('id')
            ->get()
            ->groupBy(fn ($row) => strtolower(trim($row->class_name)));

        foreach ($groups as $rows) {
            $first = $rows->first();

            $map[$first->id] = $rows
                ->filter(fn ($row) => filled($row->section))
                ->sortBy('section')
                ->map(fn ($row) => [
                    'id'           => $row->id,
                    'section_name' => $row->section,
                ])
                ->values()
                ->all();
        }

        return $map;
    }

    private function getActiveFeeTypes()
    {
        return FeeType::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();
    }
}