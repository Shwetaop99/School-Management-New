<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeStructure;
use App\Models\FeeStructureItem;
use App\Models\FeeType;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeeStructureController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */


public function index(Request $request)
{
    $query = FeeStructure::with([
        'schoolClass',
        'section',
        'items.feeType',
    ]);

    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('structure_name', 'like', "%{$search}%")
                ->orWhere('academic_year', 'like', "%{$search}%")
                ->orWhereHas('schoolClass', function ($classQuery) use ($search) {

                    $classQuery->where(
                        'class_name',
                        'like',
                        "%{$search}%"
                    );

                })
                ->orWhereHas('section', function ($sectionQuery) use ($search) {

                    $sectionQuery->where(
                        'section_name',
                        'like',
                        "%{$search}%"
                    );

                });

        });
    }


    /*
    |--------------------------------------------------------------------------
    | ACADEMIC YEAR FILTER
    |--------------------------------------------------------------------------
    */

    if ($request->filled('academic_year')) {

        $query->where(
            'academic_year',
            $request->academic_year
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CLASS FILTER
    |--------------------------------------------------------------------------
    */

    if ($request->filled('class_id')) {

        $query->where(
            'class_id',
            $request->class_id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS FILTER
    |--------------------------------------------------------------------------
    */

    if ($request->filled('status')) {

        $query->where(
            'status',
            $request->status
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

    $feeStructures = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | FILTER DATA
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD COUNTS
    |--------------------------------------------------------------------------
    */

    $totalStructures = FeeStructure::count();

    $activeStructures = FeeStructure::where(
        'status',
        'Active'
    )->count();

    $inactiveStructures = FeeStructure::where(
        'status',
        'Inactive'
    )->count();


    /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

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
            ->where(
                'academic_year',
                '!=',
                ''
            )
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
            compact(
                'academicYears',
                'classes',
                'feeTypes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => [
                'required',
                'string',
                'max:50',
            ],

            'class_id' => [
                'required',
                'exists:classes,id',
            ],

            'section_id' => [
                'nullable',
                'exists:sections,id',
            ],

            'structure_name' => [
                'required',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],

            'fee_type_id' => [
                'required',
                'array',
                'min:1',
            ],

            'fee_type_id.*' => [
                'required',
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
            ],

            'amount.*' => [
                'required',
                'numeric',
                'min:0',
            ],

            'due_date' => [
                'nullable',
                'array',
            ],

            'due_date.*' => [
                'nullable',
                'date',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Section Belongs To Selected Class
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['section_id'])) {

            $sectionExists = Section::query()
                ->where('id', $validated['section_id'])
                ->where(
                    'class_id',
                    $validated['class_id']
                )
                ->exists();

            if (!$sectionExists) {

                return back()
                    ->withErrors([
                        'section_id' =>
                            'The selected section does not belong to the selected class.',
                    ])
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Save Everything In Transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated) {

            $feeStructure = FeeStructure::create([
                'academic_year' =>
                    $validated['academic_year'],

                'class_id' =>
                    $validated['class_id'],

                'section_id' =>
                    $validated['section_id'] ?? null,

                'structure_name' =>
                    $validated['structure_name'],

                'status' =>
                    $validated['status'],
            ]);


            $feeTypeIds =
                $validated['fee_type_id'];

            $manualFeeNames =
                $validated['manual_fee_name'] ?? [];

            $amounts =
                $validated['amount'];

            $dueDates =
                $validated['due_date'] ?? [];


            foreach ($feeTypeIds as $index => $feeTypeId) {

                $amount =
                    (float) ($amounts[$index] ?? 0);

                $dueDate =
                    $dueDates[$index] ?? null;


                /*
                |--------------------------------------------------------------------------
                | MANUAL / CUSTOM FEE
                |--------------------------------------------------------------------------
                */

                if ($feeTypeId === 'manual') {

                    $manualName =
                        trim(
                            $manualFeeNames[$index] ?? ''
                        );


                    if ($manualName === '') {

                        throw new \Exception(
                            'Manual fee name is required.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Create a Fee Type automatically
                    |--------------------------------------------------------------------------
                    */

                    $feeType = FeeType::create([
                        'name' => $manualName,
                        'category' => 'Other',
                        'frequency' => 'One Time',
                        'amount' => $amount,
                        'description' =>
                            'Manually created fee type.',
                        'status' => 'Active',
                    ]);

                    $feeTypeId =
                        $feeType->id;
                }


                /*
                |--------------------------------------------------------------------------
                | EXISTING FEE TYPE
                |--------------------------------------------------------------------------
                */

                else {

                    $feeTypeExists =
                        FeeType::where(
                            'id',
                            $feeTypeId
                        )->exists();

                    if (!$feeTypeExists) {

                        throw new \Exception(
                            'Selected fee type does not exist.'
                        );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Save Fee Structure Item
                |--------------------------------------------------------------------------
                */

                FeeStructureItem::create([
                    'fee_structure_id' =>
                        $feeStructure->id,

                    'fee_type_id' =>
                        $feeTypeId,

                    'amount' =>
                        $amount,

                    'due_date' =>
                        $dueDate,
                ]);
            }
        });


        return redirect()
            ->route(
                'admin.fees.fee-structures.index'
            )
            ->with(
                'success',
                'Fee structure created successfully.'
            );
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
            ->where(
                'academic_year',
                '!=',
                ''
            )
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
            compact(
                'feeStructure',
                'academicYears',
                'classes',
                'feeTypes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        FeeStructure $feeStructure
    ) {
        $validated = $request->validate([
            'academic_year' => [
                'required',
                'string',
                'max:50',
            ],

            'class_id' => [
                'required',
                'exists:classes,id',
            ],

            'section_id' => [
                'nullable',
                'exists:sections,id',
            ],

            'structure_name' => [
                'required',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],

            'fee_type_id' => [
                'required',
                'array',
                'min:1',
            ],

            'fee_type_id.*' => [
                'required',
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
            ],

            'amount.*' => [
                'required',
                'numeric',
                'min:0',
            ],

            'due_date' => [
                'nullable',
                'array',
            ],

            'due_date.*' => [
                'nullable',
                'date',
            ],
        ]);


        if (!empty($validated['section_id'])) {

            $sectionExists = Section::query()
                ->where(
                    'id',
                    $validated['section_id']
                )
                ->where(
                    'class_id',
                    $validated['class_id']
                )
                ->exists();

            if (!$sectionExists) {

                return back()
                    ->withErrors([
                        'section_id' =>
                            'The selected section does not belong to the selected class.',
                    ])
                    ->withInput();
            }
        }


        DB::transaction(function () use (
            $validated,
            $feeStructure
        ) {

            $feeStructure->update([
                'academic_year' =>
                    $validated['academic_year'],

                'class_id' =>
                    $validated['class_id'],

                'section_id' =>
                    $validated['section_id'] ?? null,

                'structure_name' =>
                    $validated['structure_name'],

                'status' =>
                    $validated['status'],
            ]);


            /*
            |--------------------------------------------------------------------------
            | Remove Old Items
            |--------------------------------------------------------------------------
            */

            $feeStructure
                ->items()
                ->delete();


            $feeTypeIds =
                $validated['fee_type_id'];

            $manualFeeNames =
                $validated['manual_fee_name'] ?? [];

            $amounts =
                $validated['amount'];

            $dueDates =
                $validated['due_date'] ?? [];


            foreach ($feeTypeIds as $index => $feeTypeId) {

                $amount =
                    (float) ($amounts[$index] ?? 0);

                $dueDate =
                    $dueDates[$index] ?? null;


                if ($feeTypeId === 'manual') {

                    $manualName =
                        trim(
                            $manualFeeNames[$index] ?? ''
                        );

                    if ($manualName === '') {

                        throw new \Exception(
                            'Manual fee name is required.'
                        );
                    }


                    $feeType = FeeType::create([
                        'name' => $manualName,
                        'category' => 'Other',
                        'frequency' => 'One Time',
                        'amount' => $amount,
                        'description' =>
                            'Manually created fee type.',
                        'status' => 'Active',
                    ]);

                    $feeTypeId =
                        $feeType->id;
                }


                FeeStructureItem::create([
                    'fee_structure_id' =>
                        $feeStructure->id,

                    'fee_type_id' =>
                        $feeTypeId,

                    'amount' =>
                        $amount,

                    'due_date' =>
                        $dueDate,
                ]);
            }
        });


        return redirect()
            ->route(
                'admin.fees.fee-structures.show',
                $feeStructure
            )
            ->with(
                'success',
                'Fee structure updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        FeeStructure $feeStructure
    ) {
        $feeStructure->delete();

        return redirect()
            ->route(
                'admin.fees.fee-structures.index'
            )
            ->with(
                'success',
                'Fee structure deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD SECTIONS
    |--------------------------------------------------------------------------
    */

    public function sections($classId)
    {
        $sections = Section::query()
            ->where(
                'class_id',
                $classId
            )
            ->orderBy('section_name')
            ->get([
                'id',
                'section_name',
            ]);

        return response()->json($sections);
    }
}
