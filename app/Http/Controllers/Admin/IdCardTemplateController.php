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

        /*
        |--------------------------------------------------------------------------
        | Normalized so this view always sees the
        | { fields: [...], photo: {...} } shape it expects,
        | even after the template has been saved once via the
        | manual editor (which flattens photo into the list).
        |--------------------------------------------------------------------------
        */

        $analysis =
            $this->normalizeAnalysisForEditor($template);

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
        /*
        |--------------------------------------------------------------------------
        | Normalized the same way as edit(), so this page always
        | renders fields (and the photo box, with its shape)
        | whether the template still holds the raw OCR-analysis
        | shape or has already been flattened by "Save Template".
        |--------------------------------------------------------------------------
        */

        $analysis =
            $this->normalizeAnalysisForEditor($template);

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
            'field_positions.*.sample_text' => 'nullable|string',

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

            'field_positions.*.photo_shape' => [
                'nullable',
                Rule::in([
                    'circle',
                    'square',
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
     * Normalize a template's field_positions into the
     * { fields: [...], photo: {...} } shape the editor/analysis
     * Blade views expect, regardless of whether the template is
     * still holding the raw OCR-analysis result (which already
     * has "fields" and "photo" keys) or has been flattened by
     * the manual editor's "Save Template" button (a plain list
     * with the photo entry mixed in as key "profile_image").
     *
     * This is what keeps the photo box - and its selected shape -
     * from disappearing when the editor is reopened after a save.
     */
    private function normalizeAnalysisForEditor(IdCardTemplate $template): array
    {
        $raw = $template->field_positions ?? [];

        if (!is_array($raw)) {
            return [
                'fields' => [],
                'photo' => null,
                'words' => [],
                'image' => [],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Raw OCR-analysis shape: already has fields/photo (and
        | usually words/image) as top-level keys.
        |--------------------------------------------------------------------------
        */

        $looksLikeRawAnalysis =
            array_key_exists('fields', $raw) ||
            array_key_exists('photo', $raw) ||
            array_key_exists('words', $raw) ||
            array_key_exists('image', $raw);

        if ($looksLikeRawAnalysis) {

            return [
                'fields' => is_array($raw['fields'] ?? null)
                    ? $raw['fields']
                    : [],

                'photo' => is_array($raw['photo'] ?? null)
                    ? $raw['photo']
                    : null,

                'words' => is_array($raw['words'] ?? null)
                    ? $raw['words']
                    : [],

                'image' => is_array($raw['image'] ?? null)
                    ? $raw['image']
                    : [],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Flat, editor-saved list. Pull the photo entry (key
        | "profile_image") back out into its own "photo" key,
        | carrying "photo_shape" back over as "shape".
        |--------------------------------------------------------------------------
        */

        $fields = [];

        $photo = null;

        foreach ($raw as $item) {

            if (!is_array($item)) {
                continue;
            }

            if (($item['key'] ?? null) === 'profile_image') {

                $photo = [
                    'x' => $item['x'] ?? 35,
                    'y' => $item['y'] ?? 15,
                    'width' => $item['width'] ?? 25,
                    'height' => $item['height'] ?? 25,
                    'shape' => $item['photo_shape'] ?? 'circle',
                ];

                continue;
            }

            $fields[] = $item;
        }

        return [
            'fields' => $fields,
            'photo' => $photo,
            'words' => [],
            'image' => [],
        ];
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