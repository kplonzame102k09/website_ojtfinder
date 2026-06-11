<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;

class UserSearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = $request->get('query');

            if (empty($query)) {
                return response()->json([]);
            }

            $users = User::with('company') 
                ->where('id', '!=', auth()->id())
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhereHas('company', function($c) use ($query) {
                          $c->where('company_name', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%");
                      });
                })
                ->limit(5)
                ->get();

            if ($users->isNotEmpty()) {
                Activity::create([
                    'user_id' => auth()->id(),
                    'type' => 'Discovery',
                    'description' => 'Searched for: "' . $query . '"',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            $results = $users->map(function($user) {
                // FIXED: Handle the profile picture path correctly
                $profilePicUrl = null;

                if ($user->profile_picture) {
                    // We pass the raw path (e.g., 'profile_pictures/image.jpg') to our route
                    $profilePicUrl = route('image.display', ['path' => $user->profile_picture]);
                } else {
                    // Fallback to UI Avatars if no picture is set
                    $profilePicUrl = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=1e293b&color=fff';
                }

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'slug' => $user->slug,
                    'profile_picture' => $profilePicUrl, 
                    'company_name' => $user->company->company_name ?? null,
                    'is_company' => $user->company ? true : false,
                ];
            });

            return response()->json($results);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCounts()
    {
        return response()->json([
            'unreadNotifications' => Auth::user()->unreadNotifications->count(),
            'unreadMessages' => auth()->user()->receivedMessages()->whereNull('read_at')->count(), 
        ]);
    }
}