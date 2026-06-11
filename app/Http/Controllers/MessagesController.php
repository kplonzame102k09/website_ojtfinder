<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageReceived;
use App\Models\User;
use App\Models\Message;
use App\Models\Activity;
use Illuminate\Support\Facades\Storage;
use App\Events\MessageSent;

class MessagesController extends Controller
{
    //Show messaging page
    public function index()
    {
        $user = Auth::user();

        // Get everyone the user follows
        $followingIds = $user->following()->pluck('following_id');

        // Get IDs of people who have messaged the user (so they don't disappear from sidebar)
        $messagedIds = Message::where('receiver_id', $user->id)
            ->pluck('sender_id')
            ->unique();

        // Merge both: People I follow OR people who messaged me
        $contactIds = $followingIds->merge($messagedIds)->unique();

        $users = User::with('company')
            ->whereIn('id', $contactIds)
            ->get();

        foreach ($users as $contact) {
            $this->attachContactMetadata($user, $contact);
        }

        return view('pages.messages', compact('users', 'user'));
    }
    // Show specific conversation
    public function show(User $user) 
    {
        $authenticatedUser = Auth::user();
        $chatWith = $user; 

        // Re-use logic for sidebar to ensure consistency
        $followingIds = $authenticatedUser->following()->pluck('following_id');
        $messagedIds = Message::where('receiver_id', $authenticatedUser->id)->pluck('sender_id')->unique();
        $contactIds = $followingIds->merge($messagedIds)->unique();

        $users = User::with('company')->whereIn('id', $contactIds)->get();

        foreach ($users as $contact) {
            $this->attachContactMetadata($authenticatedUser, $contact);
        }

        Message::where('sender_id', $chatWith->id)
            ->where('receiver_id', $authenticatedUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::where(function ($q) use ($authenticatedUser, $chatWith) {
                $q->where('sender_id', $authenticatedUser->id)->where('receiver_id', $chatWith->id);
            })->orWhere(function ($q) use ($authenticatedUser, $chatWith) {
                $q->where('sender_id', $chatWith->id)->where('receiver_id', $authenticatedUser->id);
            })->orderBy('created_at', 'asc')->get();

        return view('pages.messages', [
            'users' => $users,
            'user' => $authenticatedUser,
            'chatWith' => $chatWith,
            'messages' => $messages
        ]);
    }

    public function send(Request $request, User $user)
    {
        // 1. SECURITY CHECK
        $isFollowing = Auth::user()->following()->where('following_id', $user->id)->exists();

        if (!$isFollowing) {
            return redirect()->back()->with('error', 'You must follow ' . $user->name . ' before you can message them.');
        }

        $request->validate([
            'content' => 'nullable|string|max:1000',
            'image'   => 'nullable|image|max:2048',
            'file'    => 'nullable|file|max:5120',
        ]);

        $messageData = [
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'content' => $request->content,
        ];

        // UPDATED FOLDERS: Using underscores for better compatibility
        if ($request->hasFile('image')) {
            // This stores as: messages_images/filename.jpg
            $messageData['image'] = $request->file('image')->store('messages_images', 'public');
        }

        if ($request->hasFile('file')) {
            // This stores as: messages_files/filename.pdf
            $messageData['file'] = $request->file('file')->store('messages_files', 'public');
        }

        $message = Message::create($messageData);

        // BROADCASTING NOTE: InfinityFree usually doesn't support Real-time WebSockets (Pusher/Reverb)
        // If your messages don't pop up instantly, it's because of hosting limitations.
        broadcast(new MessageSent($message))->toOthers();
        broadcast(new MessageReceived($user->id))->toOthers();

        Activity::create([
            'user_id' => Auth::id(),
            'type' => 'Communication',
            'description' => 'Sent a message to ' . $user->name . '.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('messages.show', $user->slug);
    }

private function attachContactMetadata($user, $contact)
{
    $lastMessage = Message::where(function ($q) use ($user, $contact) {
        $q->where('sender_id', $user->id)->where('receiver_id', $contact->id);
    })->orWhere(function ($q) use ($user, $contact) {
        $q->where('sender_id', $contact->id)->where('receiver_id', $user->id);
    })->latest()->first();

    // 1. Logic for the text preview
    if ($lastMessage) {
        if ($lastMessage->content) {
            // If there is text, show the text
            $contact->last_message = $lastMessage->content;
        } elseif ($lastMessage->image) {
            // If no text but there is an image, show the photo label
            $contact->last_message = 'Sent a photo';
        } elseif ($lastMessage->file) {
            // Optional: Handle if they sent a file instead
            $contact->last_message = 'Sent a file';
        }
    } else {
        $contact->last_message = null; // This will trigger "Start a conversation"
    }

    $contact->last_message_time = $lastMessage?->created_at->diffForHumans();
    $contact->last_message_sender = ($lastMessage && $lastMessage->sender_id === $user->id) ? 'You: ' : '';
    
    $contact->unread_count = Message::where('sender_id', $contact->id)
        ->where('receiver_id', $user->id)
        ->whereNull('read_at')
        ->count();
}
}