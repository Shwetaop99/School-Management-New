<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IdCardTemplate;
use App\Services\IdCardImageAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class IdCardTemplateController extends Controller
{
    /**
     * Display all templates.
     */
    public function index()
    {
        $templates = IdCardTemplate::latest()
            ->paginate(20);

        $totalTemplates = IdCardTemplate::count();

        $activeTemplates = IdCardTemplate::where(
            'status',
            'active'
        )->count();

        $inactiveTemplates = IdCardTemplate::where(
            'status',
            'inactive'
        )->count();

        return view(
            'admin.id-card.templates.index',
            compact(
                'templates',
                'totalTemplates',
                'activeTemplates',
                'inactiveTemplates'
            )
        );
    }

    /**
     * Show template upload page.
     */
    public function create()
    {
        $templates = IdCardTemplate::orderBy('name')
            ->get();

        $currentYear = (int) date('Y');

        $academicYears = [];

        for (
            $year = $currentYear - 1;
            $year <= $currentYear + 3;
            $year++
        ) {
            $academicYears[] =
                $year . '-' . ($year + 1);
        }

        return view(
            'admin.id-card.templates.create',
            compact(
                'templates',
                'academicYears'
            )
        );
    }

    /**
     * Store a new template.
     *
     * The uploaded image is automatically analyzed
     * using Tesseract OCR.
     */
    public function store(
        Request $request,
        IdCardImageAnalyzer $analyzer
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],

            'template_image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $imagePath = null;

        try {

            /*
            |--------------------------------------------------------------------------
            | 1. Store uploaded image
            |--------------------------------------------------------------------------
            */

            $imagePath = $request
                ->file('template_image')
                ->store(
                    'id-card-templates',
                    'public'
                );

            /*
            |--------------------------------------------------------------------------
            | 2. Generate unique slug
            |--------------------------------------------------------------------------
            */

            $slug = $this->generateUniqueSlug(
                $validated['name']
            );

            /*
            |--------------------------------------------------------------------------
            | 3. Create template
            |--------------------------------------------------------------------------
            */

            $template = IdCardTemplate::create([
                'name' => $validated['name'],

                'slug' => $slug,

                'template_image' => $imagePath,

                /*
                 * This will contain the automatic
                 * OCR analysis result.
                 */
                'field_positions' => null,

                'academic_year' =>
                    $validated['academic_year'],

                'status' =>
                    $validated['status'],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 4. Automatically analyze image
            |--------------------------------------------------------------------------
            */

            $fullImagePath = storage_path(
                'app/public/' . $imagePath
            );

            if (!file_exists($fullImagePath)) {
                throw new \RuntimeException(
                    'Uploaded image could not be found.'
                );
            }

            $analysis = $analyzer->analyze(
                $fullImagePath
            );

            /*
            |--------------------------------------------------------------------------
            | 5. Save automatic analysis
            |--------------------------------------------------------------------------
            */

            $template->update([
                'field_positions' => $analysis,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 6. Show automatic analysis result
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'admin.id-card.templates.analysis',
                    $template
                )
                ->with(
                    'success',
                    'ID card design uploaded and analyzed automatically.'
                );

        } catch (Throwable $e) {

            report($e);

            /*
             * If template/image creation failed,
             * remove uploaded image.
             */
            if (
                $imagePath &&
                Storage::disk('public')->exists(
                    $imagePath
                )
            ) {
                Storage::disk('public')->delete(
                    $imagePath
                );
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to analyze the ID card design: ' .
                    $e->getMessage()
                );
        }
    }

    /**
     * Show template information.
     *
     * This page is kept for editing basic
     * template information.
     *
     * It is NO LONGER the manual field designer.
     */
    public function edit(
        IdCardTemplate $template
    ) {
        $currentYear = (int) date('Y');

        $academicYears = [];

        for (
            $year = $currentYear - 1;
            $year <= $currentYear + 3;
            $year++
        ) {
            $academicYears[] =
                $year . '-' . ($year + 1);
        }

        $analysis =
            $template->field_positions ?? [];

        return view(
            'admin.id-card.templates.edit',
            compact(
                'template',
                'academicYears',
                'analysis'
            )
        );
    }

    /**
     * Update template information
     * and optionally replace image.
     *
     * If a new image is uploaded,
     * it is automatically analyzed again.
     */
    public function update(
        Request $request,
        IdCardTemplate $template,
        IdCardImageAnalyzer $analyzer
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],

            'template_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $oldImagePath = $template->template_image;

        try {

            $slug = $template->slug;

            /*
            |--------------------------------------------------------------------------
            | Update slug if name changed
            |--------------------------------------------------------------------------
            */

            if (
                $template->name !==
                $validated['name']
            ) {
                $slug =
                    $this->generateUniqueSlug(
                        $validated['name'],
                        $template->id
                    );
            }

            $data = [
                'name' =>
                    $validated['name'],

                'slug' =>
                    $slug,

                'academic_year' =>
                    $validated['academic_year'],

                'status' =>
                    $validated['status'],
            ];

            /*
            |--------------------------------------------------------------------------
            | Replace design image
            |--------------------------------------------------------------------------
            */

            if (
                $request->hasFile(
                    'template_image'
                )
            ) {

                $newImagePath =
                    $request
                        ->file('template_image')
                        ->store(
                            'id-card-templates',
                            'public'
                        );

                $data['template_image'] =
                    $newImagePath;

                /*
                |--------------------------------------------------------------------------
                | Save basic information first
                |--------------------------------------------------------------------------
                */

                $template->update($data);

                /*
                |--------------------------------------------------------------------------
                | Automatically analyze new image
                |--------------------------------------------------------------------------
                */

                $fullImagePath =
                    storage_path(
                        'app/public/' .
                        $newImagePath
                    );

                if (
                    !file_exists(
                        $fullImagePath
                    )
                ) {
                    throw new \RuntimeException(
                        'The new ID card image could not be found.'
                    );
                }

                $analysis =
                    $analyzer->analyze(
                        $fullImagePath
                    );

                /*
                |--------------------------------------------------------------------------
                | Save automatic analysis
                |--------------------------------------------------------------------------
                */

                $template->update([
                    'field_positions' =>
                        $analysis,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Delete old image only after
                | new image + analysis succeeded
                |--------------------------------------------------------------------------
                */

                if (
                    $oldImagePath &&
                    Storage::disk('public')->exists(
                        $oldImagePath
                    )
                ) {
                    Storage::disk('public')->delete(
                        $oldImagePath
                    );
                }

            } else {

                /*
                |--------------------------------------------------------------------------
                | No new image
                |--------------------------------------------------------------------------
                */

                $template->update(
                    $data
                );
            }

            return redirect()
                ->route(
                    'admin.id-card.templates.analysis',
                    $template
                )
                ->with(
                    'success',
                    'Template updated successfully.'
                );

        } catch (Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update the template: ' .
                    $e->getMessage()
                );
        }
    }

    /**
     * Re-run automatic analysis.
     *
     * Useful when OCR settings are improved
     * or the image needs to be analyzed again.
     */
    public function analyze(
        IdCardTemplate $template,
        IdCardImageAnalyzer $analyzer
    ) {
        try {

            $imagePath = storage_path(
                'app/public/' .
                $template->template_image
            );

            if (!file_exists($imagePath)) {

                return back()->with(
                    'error',
                    'The ID-card design image could not be found.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Automatic OCR analysis
            |--------------------------------------------------------------------------
            */

            $analysis =
                $analyzer->analyze(
                    $imagePath
                );

            /*
            |--------------------------------------------------------------------------
            | Save result
            |--------------------------------------------------------------------------
            */

            $template->update([
                'field_positions' =>
                    $analysis,
            ]);

            return redirect()
                ->route(
                    'admin.id-card.templates.analysis',
                    $template
                )
                ->with(
                    'success',
                    'ID-card design analyzed automatically.'
                );

        } catch (Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Automatic image analysis failed: ' .
                $e->getMessage()
            );
        }
    }

    /**
     * Show automatic analysis result.
     */
    public function analysis(
        IdCardTemplate $template
    ) {
        $analysis =
            $template->field_positions ?? [];

        return view(
            'admin.id-card.templates.analysis',
            compact(
                'template',
                'analysis'
            )
        );
    }

    /**
     * Legacy manual-position endpoint.
     *
     * Kept temporarily so existing routes/views
     * do not immediately break.
     *
     * DO NOT use this for the new automatic flow.
     */
    public function savePositions(
        Request $request,
        IdCardTemplate $template
    ) {
        $validated = $request->validate([
            'field_positions' => [
                'required',
                'array',
            ],

            'field_positions.*.key' => [
                'required',
                'string',
                'max:100',
            ],

            'field_positions.*.label' => [
                'nullable',
                'string',
                'max:150',
            ],

            'field_positions.*.x' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'field_positions.*.y' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'field_positions.*.width' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'field_positions.*.height' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'field_positions.*.font_size' => [
                'nullable',
                'numeric',
                'min:1',
                'max:100',
            ],

            'field_positions.*.font_weight' => [
                'nullable',
                'string',
                'max:50',
            ],

            'field_positions.*.text_align' => [
                'nullable',
                Rule::in([
                    'left',
                    'center',
                    'right',
                ]),
            ],
        ]);

        try {

            $template->update([
                'field_positions' =>
                    $validated[
                        'field_positions'
                    ],
            ]);

            return response()->json([
                'success' => true,

                'message' =>
                    'Template fields saved successfully.',
            ]);

        } catch (Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,

                'message' =>
                    'Unable to save template fields.',
            ], 500);
        }
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(
        IdCardTemplate $template
    ) {
        $template->update([
            'status' =>
                $template->status === 'active'
                    ? 'inactive'
                    : 'active',
        ]);

        return back()->with(
            'success',
            'Template status updated successfully.'
        );
    }

    /**
     * Delete template.
     */
    public function destroy(
        IdCardTemplate $template
    ) {
        try {

            if (
                $template->template_image &&
                Storage::disk('public')->exists(
                    $template->template_image
                )
            ) {
                Storage::disk('public')->delete(
                    $template->template_image
                );
            }

            $template->delete();

            return redirect()
                ->route(
                    'admin.id-card.templates.index'
                )
                ->with(
                    'success',
                    'Template deleted successfully.'
                );

        } catch (Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Unable to delete the template.'
            );
        }
    }

    /**
     * Generate unique slug.
     */
    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug =
            Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug =
                'id-card-template';
        }

        $slug =
            $baseSlug;

        $counter = 1;

        while (true) {

            $query =
                IdCardTemplate::where(
                    'slug',
                    $slug
                );

            if ($ignoreId !== null) {

                $query->where(
                    'id',
                    '!=',
                    $ignoreId
                );
            }

            if (!$query->exists()) {
                return $slug;
            }

            $counter++;

            $slug =
                $baseSlug .
                '-' .
                $counter;
        }
    }
}