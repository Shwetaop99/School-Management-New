<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportRequestController extends Controller
{
    /**
     * Display all support requests from every school.
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with(['school', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhereHas('school', function ($schoolQuery) use ($search) {
                        $schoolQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $requests = $query->latest()->paginate(15)->withQueryString();

        $totalRequests = SupportTicket::count();
        $openRequests = SupportTicket::where('status', 'open')->count();
        $inProgressRequests = SupportTicket::where('status', 'in_progress')->count();
        $resolvedRequests = SupportTicket::where('status', 'resolved')->count();
        $closedRequests = SupportTicket::where('status', 'closed')->count();

        return view('super-admin.support-requests.index', compact(
            'requests',
            'totalRequests',
            'openRequests',
            'inProgressRequests',
            'resolvedRequests',
            'closedRequests'
        ));
    }

    /**
     * Display an individual support request and its conversation.
     */
    public function show($id)
{
    $supportRequest = SupportTicket::with(['school', 'user', 'replies'])
        ->findOrFail($id);

    // Mark unread School Admin replies as read
    $supportRequest->replies()
        ->where('sender_type', 'school_admin')
        ->whereNull('read_at')
        ->update([
            'read_at' => now(),
        ]);

    // Reload replies with updated read status
    $supportRequest->load('replies');

    return view('super-admin.support-requests.show', compact('supportRequest'));
}

    /**
     * Send a reply as Super Admin.
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|min:2|max:10000',
        ]);

        $supportRequest = SupportTicket::findOrFail($id);

        $supportRequest->replies()->create([
            'sender_type' => 'super_admin',
            'sender_id' => auth('super_admin')->id(),
            'message' => $request->message,
        ]);

        return redirect()
            ->route('super-admin.support-requests.show', $id)
            ->with('success', 'Your reply has been sent successfully.');
    }

    /**
     * Update support request status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $supportRequest = SupportTicket::findOrFail($id);

        $supportRequest->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->route('super-admin.support-requests.show', $id)
            ->with('success', 'Request status updated successfully.');
    }
}
