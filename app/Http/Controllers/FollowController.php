<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserFollowed;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggle(Request $request, User $user)
    {
        $me = auth()->user();

        if ($me->id === $user->id) {
        return back()->with('error', 'You cannot follow yourself.');
    }
        
        if ($me->isFollowing($user)) {
            $me->following()->detach($user->id);

            // LOG UNFOLLOW ACTIVITY
            Activity::create([
                'user_id' => $me->id,
                'type' => 'Social',
                'description' => 'Stopped following ' . $user->name . '.',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back();
        } else {
            $me->following()->attach($user->id);
            
            // LOG FOLLOW ACTIVITY
            Activity::create([
                'user_id' => $me->id,
                'type' => 'Social',
                'description' => 'Started following ' . $user->name . '.',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Shoot Notification only once
            $user->notify(new UserFollowed($me));
            
            return back()->with('success', 'You are now following ' . $user->name);
        }
    }
}