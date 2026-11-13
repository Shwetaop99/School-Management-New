<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupportTicketController extends Controller
{
    /**
     * Display tickets submitted by the logged-in school's users.
     */
    public function index()
{
    $templates = SupportTicketTemplate::where('is_active', true)
        ->orderBy('category')
        ->orderBy('title')
        ->get()
        ->groupBy('category');

    return view('admin.support-tickets.create', compact('templates'));
}

    /**
     * Show the ticket submission form.
     */
    public function create()
{
    $templates = SupportTicketTemplate::where('is_active', true)
        ->orderBy('category')
        ->orderBy('title')
        ->get()
        ->groupBy('category');

    return view('admin.support-tickets.create', compact('templates'));
}

    /**
     * Store a new support ticket.
     */
   
public function store(Request $request)
{
    $validated = $request->validate([
        'template_id' => 'nullable|integer|exists:support_ticket_templates,id',
        'subject' => 'required|string|max:255',
        'category' => 'required|in:technical,login,billing,student_management,backup,general,complaint,other',
        'priority' => 'required|in:low,medium,high,urgent',
        'description' => 'required|string|min:10|max:10000',
    ]);

    $user = auth()->user();

    abort_unless(
        $user && $user->school_id,
        403,
        'Your account is not linked to a school.'
    );

    // Verify that the selected template is active
    if (!empty($validated['template_id'])) {
        $template = SupportTicketTemplate::where('is_active', true)
            ->findOrFail($validated['template_id']);

        // Ensure the selected category matches the template
        abort_unless(
            $template->category === $validated['category'],
            422,
            'Invalid support ticket template category.'
        );
    }

    $ticket = SupportTicket::create([
        'school_id' => $user->school_id,
        'user_id' => $user->id,
        'template_id' => $validated['template_id'] ?? null,
        'ticket_number' => 'TKT-' . now()->format('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6)),
        'subject' => $validated['subject'],
        'category' => $validated['category'],
        'priority' => $validated['priority'],
        'status' => 'open',
        'description' => $validated['description'],
    ]);

    return redirect()
        ->route('admin.support-tickets.show', $ticket->id)
        ->with('success', 'Your support ticket has been submitted successfully.');
}


    /**
     * Display one ticket belonging to the logged-in school.
     */
    public function show($id)
{
    $schoolId = auth()->user()->school_id;

    $ticket = SupportTicket::with('replies')
        ->where('school_id', $schoolId)
        ->findOrFail($id);

    // Mark unread Super Admin replies as read
    $ticket->replies()
        ->where('sender_type', 'super_admin')
        ->whereNull('read_at')
        ->update([
            'read_at' => now(),
        ]);

    // Refresh replies so the page has the updated read status
    $ticket->load('replies');

    return view('admin.support-tickets.show', compact('ticket'));
}

    public function selectTemplate($id)
{
    $template = SupportTicketTemplate::where('is_active', true)
        ->findOrFail($id);

    return view('admin.support-tickets.template', compact('template'));
}

public function custom()
{
    return view('admin.support-tickets.custom');
}

public function myTickets()
{
    $schoolId = auth()->user()->school_id;

    $tickets = SupportTicket::where('school_id', $schoolId)
        ->withCount([
            'replies as unread_replies_count' => function ($query) {
                $query->where('sender_type', 'super_admin')
                    ->whereNull('read_at');
            }
        ])
        ->latest()
        ->paginate(10);

    return view('admin.support-tickets.index', compact('tickets'));
}


/**
 * Send a reply to a support request.
 */
public function reply(Request $request, $id)
{
    $validated = $request->validate([
        'message' => 'required|string|min:2|max:10000',
    ]);

    $user = auth()->user();

    abort_unless(
        $user && $user->school_id,
        403,
        'Your account is not linked to a school.'
    );

    // Ensure the request belongs to the logged-in school.
    $ticket = SupportTicket::where('school_id', $user->school_id)
        ->findOrFail($id);

    // Do not allow replies to closed requests.
    abort_if(
        $ticket->status === 'closed',
        403,
        'This request is closed.'
    );

    $ticket->replies()->create([
        'sender_type' => 'school_admin',
        'sender_id' => $user->id,
        'message' => $validated['message'],
    ]);

    return redirect()
        ->route('admin.support-tickets.show', $ticket->id)
        ->with('success', 'Your reply has been sent successfully.');
}

}