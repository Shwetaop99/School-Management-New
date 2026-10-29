<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
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
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', today())
            ->update(['status' => 'Expired']);

        $query = Notice::where('school_id', $schoolId);

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Date filter
        if ($request->filled('date_filter')) {
            if ($request->date_filter === 'today') {
                $query->whereDate('publish_date', today());
            } elseif ($request->date_filter === 'week') {
                $query->whereBetween('publish_date', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ]);
            } elseif ($request->date_filter === 'month') {
                $query->whereMonth('publish_date', now()->month)
                    ->whereYear('publish_date', now()->year);
            }
        }

        $notices = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

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

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request
                ->file('attachment')
                ->store('notices', 'public');
        }

        // Assign ownership on the server; never trust a submitted school_id.
        $notice = new Notice();
        $notice->fill($validated);
        $notice->school_id = $schoolId;
        $notice->created_by = auth()->id();
        $notice->save();

        if ($notice->status === 'Published') {
            return redirect()->route('admin.notices.whatsapp', $notice);
        }

        return redirect()
            ->route('admin.notices.index')
            ->with('success', 'Notice saved as draft.');
    }

    public function show(Notice $notice)
    {
        $this->authorizeSchoolNotice($notice);

        return view('admin.notices.show', compact('notice'));
    }

    public function edit(Notice $notice)
    {
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
