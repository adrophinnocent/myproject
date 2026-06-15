<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    // Display list of admin notifications
    public function index()
    {
        $notifications = AdminNotification::latest()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    // Mark all notifications as read
    public function markAllRead(Request $request)
    {
        AdminNotification::where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'All notifications marked as read.');
    }
}
