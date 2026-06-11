<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{

 // ProfileController.php

    public function index()
    {
        $user = Auth::user();
        $posts = $user->posts()->with(['comments.user', 'likes'])->latest()->get();

        $user->load('requirements');
        
        // Fetch applications for the student's own feed
        $appliedPosts = [];
        if (!$user->company) {
            $appliedPosts = \App\Models\Application::where('student_id', $user->id)
                ->with(['post.user.company', 'post.likes', 'post.comments'])
                ->latest()
                ->get();
        }
        
        return view('pages.profile', [
            'user' => $user,
            'posts' => $posts,
            'company' => $user->company,
            'appliedPosts' => $appliedPosts // Pass to view
        ]);
    }

    public function updateRole(Request $request)
    {
        $request->validate([
            'role' => 'required|in:student,company',
        ]);

        $user = auth()->user();
        
        if ($user) {
            // IMPORTANT: Only use this for instant toggles, 
            // not for forms that can be cancelled!
            $user->role = $request->role;
            $user->save();
            
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }
    public function show(User $user)
    {
        if (auth()->check() && auth()->id() === $user->id) {
            return redirect()->route('profile'); 
        }
        $user->load(['requirements', 'company', 'posts' => function($query) {
            // Only the owner of the post should see who applied
            $query->with(['comments.user', 'likes', 'applications.student'])->latest();
            
        }]);

        $isOwnProfile = false; // Always false because of the redirect above
        $isNearby = false;

        if (auth()->check() && !auth()->user()->company) {
            $visitorAddress = strtolower(trim(auth()->user()->address ?? ''));
            $profileAddress = strtolower(trim($user->address ?? ''));
            if ($visitorAddress !== '' && $visitorAddress === $profileAddress) {
                $isNearby = true;
            }
        }

        // This handles the edge case where you might disable the redirect
        $appliedPosts = \App\Models\Application::where('student_id', $user->id)
            ->with(['post.user.company'])
            ->latest()
            ->get();
        
        return view('profile.show', compact('user', 'isOwnProfile', 'isNearby', 'appliedPosts'));
    }
}