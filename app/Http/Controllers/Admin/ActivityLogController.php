<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer')->latest('id');

        if ($request->filled('event')) {
            $query->where('event', $request->input('event'));
        }
        if ($request->filled('model')) {
            $query->where('subject_type', $request->input('model'));
        }
        if ($request->filled('user')) {
            $query->where('causer_id', $request->input('user'));
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->input('to'));
        }

        $logs   = $query->paginate(25)->withQueryString();
        $models = Activity::select('subject_type')->distinct()->pluck('subject_type');
        $users  = User::orderBy('name')->get(['id', 'name']);

        return view('admin.activity-log.index', compact('logs', 'models', 'users'));
    }
}