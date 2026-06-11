<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->latest()
            ->paginate(10);

        $trainingCategories = \App\Models\Post::whereNotNull('training_category')
            ->distinct()
            ->pluck('training_category');

        return view('pages.notification', compact('notifications', 'trainingCategories'));
    }
    public function readAndRedirect($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        $data = $notification->data;

        // SYSTEM REDIRECT: Redirect to own profile if it's a system update
        if (!isset($data['sender_id']) || ($data['sender_name'] ?? '') === 'From System:') {
            return redirect()->route('profile.show', Auth::user()->slug);
        }

        // 1. Post-related notifications
        if (isset($data['post_id'])) {
            return redirect()->route('newsfeed', ['open_comment' => $data['post_id']])
                            ->withFragment('post-' . $data['post_id']);
        }
        
        // 2. Follower/Profile-related notifications
        if ((isset($data['type']) && $data['type'] === 'follow') || isset($data['sender_id'])) {
            // Find the user by the ID stored in notification data
            $sender = \App\Models\User::find($data['sender_id']);

            if ($sender) {
                // Redirect using the slug (assuming your route is set up for it)
                return redirect()->route('profile.show', $sender->slug);
            }
        }

        return redirect()->back();
    }
    public function markAsRead($id)
        {
            $notification = Auth::user()->notifications()->findOrFail($id);
            $notification->markAsRead();

            if (request()->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back();
        }

    public function markAllAsRead()
    {
        $user = Auth::user();
        
        if ($user->unreadNotifications->count() > 0) {
            $user->unreadNotifications->markAsRead();
            $message = 'Your inbox is now up to date.';
        } else {
            $message = 'All notifications were already read.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function clearAll()
    {
        Auth::user()->notifications()->delete();
        return redirect()->back()->with('success', 'Notification history cleared.');
    }
}