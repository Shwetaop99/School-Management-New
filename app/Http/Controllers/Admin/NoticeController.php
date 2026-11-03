<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class NoticeController extends Controller
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
            'search'      => ['nullable', 'string', 'max:100'],
            'category'    => ['nullable', 'string', 'max:100'],
            'status'      => ['nullable', Rule::in(['Draft', 'Published', 'Expired'])],
            'date_filter' => ['nullable', Rule::in(['today', 'week', 'month'])],
        ]);

        // Mark old notices as expired
        Notice::where('status', 'Published')
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', today())
            ->update(['status' => 'Expired']);

        $query = Notice::query();

        // Search
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date filter
        if ($request->filled('date_filter')) {

            if ($request->date_filter === 'today') {
                $query->whereDate('publish_date', today());
            }

            if ($request->date_filter === 'week') {
                $query->whereBetween('publish_date', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
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
        $total     = Notice::count();
        $published = Notice::where('status', 'Published')->count();
        $draft     = Notice::where('status', 'Draft')->count();
        $expired   = Notice::where('status', 'Expired')->count();

        return view('admin.notices.index', compact(
            'notices',
            'total',
            'published',
            'draft',
            'expired'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('admin.notices.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate($this->noticeRules());

        // UPDATE 2: remove HTML tags from the text fields
        $validated = $this->cleanText($validated);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request
                ->file('attachment')
                ->store('notices', 'public');
        } else {
            // no file chosen: do not save an empty attachment value
            unset($validated['attachment']);
        }

        $validated['created_by'] = auth()->id();

        $notice = Notice::create($validated);

        if ($notice->status === 'Published') {
            return redirect()->route('admin.notices.whatsapp', $notice);
        }

        return redirect()
            ->route('admin.notices.index')
            ->with('success', 'Notice saved as draft.');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW / EDIT
    |--------------------------------------------------------------------------
    */

    public function show(Notice $notice)
    {
        return view('admin.notices.show', compact('notice'));
    }

    public function edit(Notice $notice)
    {
        return view('admin.notices.edit', compact('notice'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Notice $notice)
    {
        $validated = $request->validate($this->noticeRules());

        $validated = $this->cleanText($validated);

        // UPDATE 3: keep the old attachment unless a new file is chosen
        if ($request->hasFile('attachment')) {

            $newPath = $request
                ->file('attachment')
                ->store('notices', 'public');

            // delete the old file so it does not stay on the server
            $this->deleteAttachment($notice->attachment);

            $validated['attachment'] = $newPath;

        } else {
            unset($validated['attachment']);
        }

        $notice->update($validated);

        return redirect()
            ->route('admin.notices.index')
            ->with('success', 'Notice updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Notice $notice)
    {
        $attachment = $notice->attachment;

        $notice->delete();

        // UPDATE 4: also remove the file from the server
        $this->deleteAttachment($attachment);

        return redirect()
            ->route('admin.notices.index')
            ->with('success', 'Notice deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | WHATSAPP
    |--------------------------------------------------------------------------
    */

    public function whatsapp(Notice $notice)
    {
        // UPDATE 5: only published notices can be sent
        if ($notice->status !== 'Published') {
            return redirect()
                ->route('admin.notices.index')
                ->with('error', 'Only published notices can be shared on WhatsApp.');
        }

        // very long text makes the WhatsApp link too long to work
        $content = Str::limit((string) $notice->content, 1500);

        $message =
            "*GURUKUL VIDYALAYA*\n" .
            "*School Notice*\n\n" .
            "*" . $notice->title . "*\n\n" .
            $content . "\n\n" .
            "*Priority:* " . $notice->priority . "\n";

        if ($notice->publish_date) {
            $message .=
                "*Published:* " .
                Carbon::parse($notice->publish_date)->format('d F Y') .
                "\n";
        }

        $message .=
            "\nThank you,\n" .
            "Gurukul Vidyalaya";

        $whatsappUrl = 'https://wa.me/?text=' . rawurlencode($message);

        return redirect()->away($whatsappUrl);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function noticeRules(): array
    {
        return [
            'title'    => ['required', 'string', 'max:255'],
            'content'  => ['required', 'string', 'max:5000'],
            'category' => ['required', 'string', 'max:100'],

            'publish_date' => ['nullable', 'date'],
            'expiry_date'  => ['nullable', 'date', 'after_or_equal:publish_date'],

            'status'   => ['required', Rule::in(['Draft', 'Published'])],
            'priority' => ['required', Rule::in(['Normal', 'Important', 'Urgent'])],

            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:5120',
            ],
        ];
    }

    private function cleanText(array $data): array
    {
        foreach (['title', 'content', 'category'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = trim(strip_tags($data[$field]));
            }
        }

        return $data;
    }

    private function deleteAttachment(?string $path): void
    {
        // only delete files inside the notices folder
        if ($path && str_starts_with($path, 'notices/')) {
            Storage::disk('public')->delete($path);
        }
    }
}