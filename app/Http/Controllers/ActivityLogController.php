<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Filter notifications to only show document downloads, views of certificates, awards, and files
        $logs = ActivityLog::where(function($query) {
            $query->where('type', 'Download')
                  ->orWhere('title', 'like', '%download%')
                  ->orWhere('title', 'like', '%document%')
                  ->orWhere('title', 'like', '%certificate%')
                  ->orWhere('title', 'like', '%award%')
                  ->orWhere('title', 'like', '%file%')
                  ->orWhere('link', 'like', '%download%')
                  ->orWhere('link', 'like', '%documents%')
                  ->orWhere('link', 'like', '%edu&certs%')
                  ->orWhere('link', 'like', '%certificate%')
                  ->orWhere('link', 'like', '%award%')
                  ->orWhere('link', 'like', '%gallery%')
                  ->orWhere('link', 'like', '%my-files%');
        })->latest()->paginate(50);
        
        return view('admin.pages.document-activity', compact('logs'));
    }

    public function markAsRead(ActivityLog $activityLog)
    {
        $activityLog->markAsRead();
        return back()->with('success', 'Marked as read.');
    }

    public function markAllAsRead()
    {
        ActivityLog::unread()->update(['read_at' => now()]);
        return back()->with('success', 'All marked as read.');
    }

    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();
        return back()->with('success', 'Notification deleted.');
    }
}
