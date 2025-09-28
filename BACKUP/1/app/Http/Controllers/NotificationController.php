<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead()
    {
        // Mark all notifications as read for the currently authenticated user
        Notification::where('is_read', false)->update(['is_read' => true]);

        return redirect()->back()->with('status', 'All notifications marked as read.');
    }

    public function markNotificationAsRead(Notification $notification)
{
    $notification->update(['is_read' => true]);
    return redirect()->back()->with('success', 'Notification marked as read.');
}

}
