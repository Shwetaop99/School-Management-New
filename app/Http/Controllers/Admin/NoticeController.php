<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
<<<<<<< HEAD
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NoticeController extends Controller
{
    /**
     * Get the authenticated user's school ID.
     */
    private function schoolId(): int
    {
        $schoolId = auth()->user()?->school_id;

        abort_unless($schoolId, 403, 'No school is assigned to this account.');

        return (int) $schoolId;
    }

    /**
     * Ensure a notice belongs to the logged-in user's school.
     */
    private function authorizeSchoolNotice(Notice $notice): void
    {
        if ((int) $notice->school_id !== $this->schoolId()) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Display notices belonging only to the current school.
     */
    public function index(Request $request)
    {
        $schoolId = $this->schoolId();

        // Expire only this school's notices.
        Notice::where('school_id', $schoolId)
            ->where('status', 'Published')
=======
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
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', today())
            ->update(['status' => 'Expired']);

<<<<<<< HEAD
        $query = Notice::where('school_id', $schoolId);

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
=======
        $query = Notice::query();

        // Search
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
            });
        }

        // Category filter
        if ($request->filled('category')) {
<<<<<<< HEAD
            $query->where('category', $request->input('category'));
=======
            $query->where('category', $request->category);
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
        }

        // Status filter
        if ($request->filled('status')) {
<<<<<<< HEAD
            $query->where('status', $request->input('status'));
=======
            $query->where('status', $request->status);
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
        }

        // Date filter
        if ($request->filled('date_filter')) {
<<<<<<< HEAD
            if ($request->date_filter === 'today') {
                $query->whereDate('publish_date', today());
            } elseif ($request->date_filter === 'week') {
=======

            if ($request->date_filter === 'today') {
                $query->whereDate('publish_date', today());
            }

            if ($request->date_filter === 'week') {
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
                $query->whereBetween('publish_date', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ]);
<<<<<<< HEAD
            } elseif ($request->date_filter === 'month') {
=======
            }

            if ($request->date_filter === 'month') {
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
                $query->whereMonth('publish_date', now()->month)
                    ->whereYear('publish_date', now()->year);
            }
        }

        $notices = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

<<<<<<< HEAD
        // School-specific statistics
        $total = Notice::where('school_id', $schoolId)->count();

        $published = Notice::where('school_id', $schoolId)
            ->where('status', 'Published')
            ->count();

        $draft = Notice::where('school_id', $schoolId)
            ->where('status', 'Draft')
            ->count();

        $expired = Notice::where('school_id', $schoolId)
            ->where('status', 'Expired')
            ->count();
=======
        // Statistics
        $total     = Notice::count();
        $published = Notice::where('status', 'Published')->count();
        $draft     = Notice::where('status', 'Draft')->count();
        $expired   = Notice::where('status', 'Expired')->count();
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c

        return view('admin.notices.index', compact(
            'notices',
            'total',
            'published',
            'draft',
            'expired'
        ));
    }

<<<<<<< HEAD
    public function create()
    {
        $this->schoolId();

        return view('admin.notices.create');
    }

    /**
     * Create a notice for the logged-in user's school.
     */
    public function store(Request $request)
    {
        $schoolId = $this->schoolId();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'string', 'max:255'],
            'publish_date' => ['nullable', 'date'],
            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:publish_date',
            ],
            'status' => ['required', 'in:Draft,Published'],
            'priority' => ['required', 'in:Normal,Important,Urgent'],
            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

=======
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

>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request
                ->file('attachment')
                ->store('notices', 'public');
<<<<<<< HEAD
        }

        // Assign ownership on the server; never trust a submitted school_id.
        $notice = new Notice();
        $notice->fill($validated);
        $notice->school_id = $schoolId;
        $notice->created_by = auth()->id();
        $notice->save();
=======
        } else {
            // no file chosen: do not save an empty attachment value
            unset($validated['attachment']);
        }

        $validated['created_by'] = auth()->id();

        $notice = Notice::create($validated);
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c

        if ($notice->status === 'Published') {
            return redirect()->route('admin.notices.whatsapp', $notice);
        }

        return redirect()
            ->route('admin.notices.index')
            ->with('success', 'Notice saved as draft.');
    }

<<<<<<< HEAD
    public function show(Notice $notice)
    {
        $this->authorizeSchoolNotice($notice);

=======
    /*
    |--------------------------------------------------------------------------
    | SHOW / EDIT
    |--------------------------------------------------------------------------
    */

    public function show(Notice $notice)
    {
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
        return view('admin.notices.show', compact('notice'));
    }

    public function edit(Notice $notice)
    {
<<<<<<< HEAD
        $this->authorizeSchoolNotice($notice);

        return view('admin.notices.edit', compact('notice'));
    }

    public function update(Request $request, Notice $notice)
    {
        $this->authorizeSchoolNotice($notice);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'string', 'max:255'],
            'publish_date' => ['nullable', 'date'],
            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:publish_date',
            ],
            'status' => ['required', 'in:Draft,Published'],
            'priority' => ['required', 'in:Normal,Important,Urgent'],
            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        if ($request->hasFile('attachment')) {
            // Remove the previous attachment when replacing it.
            if ($notice->attachment) {
                Storage::disk('public')->delete($notice->attachment);
            }

            $validated['attachment'] = $request
                ->file('attachment')
                ->store('notices', 'public');
        }

        // Do not allow school_id or created_by to be changed by this request.
        $notice->fill($validated);
        $notice->save();

        return redirect()
            ->route('admin.notices.index')
            ->with('success', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice)
    {
        $this->authorizeSchoolNotice($notice);

        if ($notice->attachment) {
            Storage::disk('public')->delete($notice->attachment);
        }

        $notice->delete();

        return redirect()
            ->route('admin.notices.index')
            ->with('success', 'Notice deleted successfully.');
    }

    /**
     * Prepare a WhatsApp message for this school's notice.
     */
    public function whatsapp(Notice $notice)
    {
        $this->authorizeSchoolNotice($notice);

        $school = School::find($this->schoolId());
        $schoolName = $school?->name ?? 'School';

        $message =
            "*{$schoolName}*\n" .
            "*School Notice*\n\n" .
            "*{$notice->title}*\n\n" .
            $notice->content . "\n\n" .
            "*Priority:* {$notice->priority}\n";

        if ($notice->publish_date) {
            $message .=
                "*Published:* " .
                \Carbon\Carbon::parse($notice->publish_date)
                    ->format('d F Y') .
                "\n";
        }

        $message .= "\nThank you,\n{$schoolName}";

        $whatsappUrl =
            'https://wa.me/?text=' . rawurlencode($message);

        return redirect()->away($whatsappUrl);
    }
}
=======
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
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
