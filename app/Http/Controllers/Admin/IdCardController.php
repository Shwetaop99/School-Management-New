<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IdCard;
use App\Models\IdCardTemplate;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class IdCardController extends Controller
{
    /**
     * ID card list.
     */
    public function index(Request $request)
    {
        $query = IdCard::with([
            'student',
            'idCardTemplate',
        ])->latest();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->where(function ($q) use ($search) {

                $q->where(
                    'card_number',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhereHas(
                    'student',
                    function ($studentQuery) use ($search) {

                        $studentQuery
                            ->where(
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
                    }
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        $idCards = $query
            ->paginate(15)
            ->withQueryString();


        return view(
            'admin.id-card.index',
            compact('idCards')
        );
    }


    /**
     * Generate ID card page.
     */
    public function create()
    {
        $templates = IdCardTemplate::where(
            'status',
            'active'
        )
            ->orderBy('name')
            ->get();

        return view(
            'admin.id-card.create',
            compact('templates')
        );
    }


    /**
     * Search students for ID-card generation.
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => [
                'required',
                'string',
                'min:1',
                'max:100',
            ],
        ]);

        $search = trim($request->q);

        $students = Student::query()
            ->where(function ($query) use ($search) {

                $query
                    ->where(
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
                    )

                    ->orWhere(
                        'register_no',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'pen_no',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'appar_id',
                        'like',
                        '%' . $search . '%'
                    );
            })
            ->selectRaw("
                students.*,
                TRIM(
                    CONCAT_WS(
                        ' ',
                        first_name,
                        middle_name,
                        last_name
                    )
                ) AS full_name
            ")
            ->orderBy('first_name')
            ->limit(20)
            ->get();

        return response()->json(
            $students
        );
    }


    /**
     * Generate/store ID card.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            'template' => [
                'required',
                'string',
                'exists:id_card_templates,slug',
            ],

            'academic_year' => [
                'nullable',
                'string',
                'max:20',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Student
        |--------------------------------------------------------------------------
        */

        $student = Student::findOrFail(
            $validated['student_id']
        );


        /*
        |--------------------------------------------------------------------------
        | Template
        |--------------------------------------------------------------------------
        |
        | Only active templates can be used.
        |
        */

        $template = IdCardTemplate::where(
            'slug',
            $validated['template']
        )
            ->where(
                'status',
                'active'
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Make sure the template has configuration
        |--------------------------------------------------------------------------
        |
        | Uses the shared normalizer so this works whether the
        | template is still holding the raw OCR-analysis shape
        | (fields/photo/words/image) or has been flattened by the
        | manual editor's "Save Template" button. Either way, the
        | photo box (and its shape) is folded into the flat list.
        |
        */

        $fieldPositions =
            $this->normalizeFieldPositions($template);

        if (
            !is_array($fieldPositions) ||
            count($fieldPositions) === 0
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'This template has no configured fields. Please configure the uploaded design first.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Academic year
        |--------------------------------------------------------------------------
        */

        $academicYear =
            $validated['academic_year']
            ?? $template->academic_year
            ?? $this->getAcademicYear();


        try {

            DB::beginTransaction();


            /*
            |--------------------------------------------------------------------------
            | Card Number
            |--------------------------------------------------------------------------
            */

            $cardNumber =
                $this->generateCardNumber();


            /*
            |--------------------------------------------------------------------------
            | Create ID card reference
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | We DO NOT copy student name/photo/class/etc.
            | into id_cards.
            |
            | The card only remembers:
            |
            | student
            | template
            | academic year
            |
            */

            $idCard = IdCard::create([

                'student_id' =>
                    $student->id,

                'card_number' =>
                    $cardNumber,

                'template' =>
                    $template->slug,

                'academic_year' =>
                    $academicYear,

                'issued_date' =>
                    now()->toDateString(),

                'status' =>
                    'active',

            ]);


            DB::commit();


            return redirect()
                ->route(
                    'admin.id-card.show',
                    $idCard->id
                )
                ->with(
                    'success',
                    'ID card generated successfully.'
                );

        } catch (Throwable $e) {

            DB::rollBack();

            report($e);


            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to generate the ID card. Please try again.'
                );
        }
    }


    /**
     * Show generated ID card.
     */
    public function show($id)
    {
        $idCard = IdCard::with([
            'student',
            'idCardTemplate',
        ])->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Get exact template used
        |--------------------------------------------------------------------------
        */

        $template =
            $idCard->idCardTemplate;


        if (!$template) {

            abort(
                404,
                'The template used for this ID card no longer exists.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Template configuration
        |--------------------------------------------------------------------------
        |
        | Normalized the same way as store(), so the photo shape
        | (circle/square) is preserved here too, whether or not the
        | template was ever manually re-saved via the editor.
        |
        */

        $fieldPositions =
            $this->normalizeFieldPositions($template);


        return view(
            'admin.id-card.show',
            compact(
                'idCard',
                'template',
                'fieldPositions'
            )
        );
    }


    /**
     * Print generated ID card.
     */
    public function print($id)
    {
        $idCard = IdCard::with([
            'student',
            'idCardTemplate',
        ])->findOrFail($id);


        $template =
            $idCard->idCardTemplate;


        if (!$template) {

            abort(
                404,
                'The template used for this ID card no longer exists.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Same normalization as show()/store() so print matches
        | what was actually configured, including photo shape.
        |--------------------------------------------------------------------------
        */

        $fieldPositions =
            $this->normalizeFieldPositions($template);


        return view(
            'admin.id-card.print',
            compact(
                'idCard',
                'template',
                'fieldPositions'
            )
        );
    }


    /**
     * Delete generated ID card.
     */
    public function destroy($id)
    {
        try {

            $idCard =
                IdCard::findOrFail($id);

            $idCard->delete();


            return redirect()
                ->route(
                    'admin.id-card.index'
                )
                ->with(
                    'success',
                    'ID card deleted successfully.'
                );

        } catch (Throwable $e) {

            report($e);


            return back()->with(
                'error',
                'Unable to delete the ID card.'
            );
        }
    }


    /**
     * Normalize a template's field_positions into a flat array
     * of field entries, regardless of whether the template is
     * still holding the raw automatic-analysis shape
     * ({ fields: [...], photo: {...}, words: [...], image: {...} })
     * or has already been flattened by the manual editor's
     * "Save Template" button (a plain list of field entries,
     * with the photo box already included as one of them).
     *
     * This is what fixes the photo shape (circle/square) getting
     * lost: the raw-analysis "photo" key is folded into the flat
     * list here, carrying its "shape" over as "photo_shape", so
     * every consumer of field_positions (store/show/print) sees
     * the photo entry the same way the Blade views expect it.
     */
    private function normalizeFieldPositions(IdCardTemplate $template): array
    {
        $raw = $template->field_positions ?? [];

        if (!is_array($raw)) {
            return [];
        }

        /*
        |--------------------------------------------------------------------------
        | Already a flat, editor-saved array of field entries.
        |--------------------------------------------------------------------------
        |
        | array_is_list() requires PHP 8.1+. If running on an older
        | version, replace with: array_values($raw) === $raw
        |
        */

        if (array_is_list($raw)) {
            return $raw;
        }

        $fields = [];

        if (
            isset($raw['fields']) &&
            is_array($raw['fields'])
        ) {
            $fields = $raw['fields'];
        }

        /*
        |--------------------------------------------------------------------------
        | Fold the photo box into the flat list too, so its shape
        | survives even if the editor was never manually re-saved.
        |--------------------------------------------------------------------------
        */

        if (
            isset($raw['photo']) &&
            is_array($raw['photo'])
        ) {

            $photo = $raw['photo'];

            $fields[] = [
                'key'         => 'profile_image',
                'label'       => 'Student Photo',
                'x'           => $photo['x'] ?? 35,
                'y'           => $photo['y'] ?? 15,
                'width'       => $photo['width'] ?? 25,
                'height'      => $photo['height'] ?? 25,
                'font_size'   => 0,
                'font_weight' => 'normal',
                'text_align'  => 'center',
                'photo_shape' => $photo['shape'] ?? 'circle',
            ];
        }

        return $fields;
    }


    /**
     * Generate unique card number.
     */
    private function generateCardNumber(): string
    {
        $year =
            date('Y');


        do {

            $number =
                'IDC-' .
                $year .
                '-' .
                strtoupper(
                    Str::random(6)
                );


            $exists =
                IdCard::where(
                    'card_number',
                    $number
                )->exists();

        } while ($exists);


        return $number;
    }


    /**
     * Current academic year.
     */
    private function getAcademicYear(): string
    {
        $year =
            (int) date('Y');

        return
            $year .
            '-' .
            ($year + 1);
    }
}