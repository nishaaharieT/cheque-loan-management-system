<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    // Display activity logs
    public function index(Request $request)
    {
        $query = ActivityLog::with('employee')
            ->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $activityLogs = $query->get();

        $employees = \App\Models\Employee::orderBy('name')->get();

        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('activity_logs.index', compact(
            'activityLogs',
            'employees',
            'actions'
        ));
    }
}