<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index(Request $request)
{
    Notice::where('status', 'Published')
    ->whereNotNull('expiry_date')
    ->whereDate('expiry_date', '<', today())
    ->update(['status' => 'Expired']);
    
    $query = Notice::query();

    // Search
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('content', 'like', '%' . $request->search . '%');
        });
    }

    // Category Filter
    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    // Status Filter
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Date Filter
    if ($request->filled('date_filter')) {
        if ($request->date_filter === 'today') {
            $query->whereDate('publish_date', today());
        }

        if ($request->date_filter === 'week') {
            $query->whereBetween('publish_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        }

        if ($request->date_filter === 'month') {
            $query->whereMonth('publish_date', now()->month)
                  ->whereYear('publish_date', now()->year);
        }
    }

    $notices = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    // Statistics
    $total = Notice::count();

    $published = Notice::where('status', 'Published')->count();

    $draft = Notice::where('status', 'Draft')->count();

    $expired = Notice::where('status', 'Expired')->count();

    return view('admin.notices.index', compact(
        'notices',
        'total',
        'published',
        'draft',
        'expired'
    ));
}

    public function create()
    {
        return view('admin.notices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],

            'content' => ['required', 'string'],

            'category' => [
                'required',
                'string',
                'max:100'
            ],

            'publish_date' => [
                'nullable',
                'date'
            ],

            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:publish_date'
            ],

            'status' => [
                'required',
                'in:Draft,Published'
            ],

            'priority' => [
                'required',
                'in:Normal,Important,Urgent'
            ],

            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:5120'
            ],
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request
                ->file('attachment')
                ->store('notices', 'public');
        }

        $validated['created_by'] = auth()->id();

        Notice::create($validated);

        return redirect()
            ->route('admin.notices.index')
            ->with('success', 'Notice created successfully.');
    }

    public function show(Notice $notice)
{
    return view('admin.notices.show', compact('notice'));
}

public function edit(Notice $notice)
{
    return view('admin.notices.edit', compact('notice'));
}

public function update(Request $request, Notice $notice)
{
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],

        'content' => ['required', 'string'],

        'category' => [
            'required',
            'string',
            'max:100'
        ],

        'publish_date' => [
            'nullable',
            'date'
        ],

        'expiry_date' => [
            'nullable',
            'date',
            'after_or_equal:publish_date'
        ],

        'status' => [
            'required',
            'in:Draft,Published'
        ],

        'priority' => [
            'required',
            'in:Normal,Important,Urgent'
        ],

        'attachment' => [
            'nullable',
            'file',
            'mimes:pdf,doc,docx,jpg,jpeg,png',
            'max:5120'
        ],
    ]);

    if ($request->hasFile('attachment')) {
        $validated['attachment'] = $request
            ->file('attachment')
            ->store('notices', 'public');
    }

    $notice->update($validated);

    return redirect()
        ->route('admin.notices.index')
        ->with('success', 'Notice updated successfully.');
}

public function destroy(Notice $notice)
{
    $notice->delete();

    return redirect()
        ->route('admin.notices.index')
        ->with('success', 'Notice deleted successfully.');
}
}