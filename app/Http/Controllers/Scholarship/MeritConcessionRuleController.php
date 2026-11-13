<?php

namespace App\Http\Controllers\Scholarship;

use App\Http\Controllers\Controller;
use App\Models\Scholarship\MeritConcession;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MeritConcessionRuleController extends Controller
{
    public function index()
    {
        $rules = MeritConcession::with('slabs')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.merit-concession.rules-index', compact('rules'));
    }

    public function edit(MeritConcession $rule)
    {
        $rule->load('slabs');

        return view('admin.merit-concession.rules-edit', compact('rule'));
    }

    public function update(Request $request, MeritConcession $rule)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:20',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'slabs' => 'required|array|min:1',
            'slabs.*.minimum_percentage' => 'required|numeric|min:0|max:100',
            'slabs.*.maximum_percentage' => 'nullable|numeric|min:0|max:100',
            'slabs.*.scholarship_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $slabs = collect($validated['slabs'])
            ->map(function ($slab) {
                return [
                    'minimum_percentage' => (float) $slab['minimum_percentage'],
                    'maximum_percentage' =>
                        ($slab['maximum_percentage'] ?? '') === ''
                            ? null
                            : (float) $slab['maximum_percentage'],
                    'scholarship_percentage' =>
                        (float) $slab['scholarship_percentage'],
                ];
            })
            ->sortBy('minimum_percentage')
            ->values();

        $this->validateSlabRanges($slabs);

        DB::transaction(function () use ($validated, $slabs, $rule) {
            $firstSlab = $slabs->first();

            $rule->update([
                'academic_year' => $validated['academic_year'],
                'minimum_percentage' => $firstSlab['minimum_percentage'],
                'scholarship_percentage' => $firstSlab['scholarship_percentage'],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
            ]);

            $rule->slabs()->delete();

            foreach ($slabs as $index => $slab) {
                $rule->slabs()->create([
                    'minimum_percentage' => $slab['minimum_percentage'],
                    'maximum_percentage' => $slab['maximum_percentage'],
                    'scholarship_percentage' => $slab['scholarship_percentage'],
                    'sort_order' => $index,
                ]);
            }
        });

        return redirect()
            ->route('admin.scholarship.merit.concession.rules')
            ->with('success', 'Scholarship rule and slabs updated successfully.');
    }

    public function destroy(MeritConcession $rule)
    {
        $rule->delete();

        return redirect()
            ->route('admin.scholarship.merit.concession.rules')
            ->with('success', 'Scholarship rule and its slabs deleted successfully.');
    }

    private function validateSlabRanges(Collection $slabs): void
    {
        $previousMaximum = null;

        foreach ($slabs as $index => $slab) {
            $minimum = $slab['minimum_percentage'];
            $maximum = $slab['maximum_percentage'];

            if ($maximum !== null && $maximum < $minimum) {
                throw ValidationException::withMessages([
                    "slabs.$index.maximum_percentage" =>
                        'The maximum percentage must be at least the minimum percentage.',
                ]);
            }

            if ($index > 0 && $previousMaximum === null) {
                throw ValidationException::withMessages([
                    'slabs' =>
                        'An open-ended slab must be the final percentage range.',
                ]);
            }

            if ($previousMaximum !== null && $minimum <= $previousMaximum) {
                throw ValidationException::withMessages([
                    'slabs' =>
                        'Scholarship percentage ranges must not overlap.',
                ]);
            }

            $previousMaximum = $maximum;
        }
    }
}