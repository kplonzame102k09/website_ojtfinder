<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Activity; 
use App\Models\Post;     
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function store(Request $request)
    {
        //========================== Security Check ==========================//
        if (Auth::user()->company) {
            return back()->with('error', 'Action denied. Company accounts cannot apply for OJT positions.');
        }

        //========================== Validate basic inputs ==========================//
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'message' => 'required|string|min:10|max:1000',
        ]);

        //========================== Prevent duplicate applications ==========================//
        $alreadyApplied = Application::where('post_id', $request->post_id)
            ->where('student_id', Auth::id())
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'You have already applied for this position.');
        }

        //========================== Create the application ==========================//
        $application = Application::create([
            'post_id'    => $request->post_id,
            'student_id' => Auth::id(),
            'message'    => $request->message,
            'status'     => 'pending',
        ]);

        $post = Post::with('user.company')->find($request->post_id);
        $companyName = $post->user->company->company_name ?? $post->user->name;
        //========================== Log the activity ==========================//
        Activity::create([
            'user_id' => Auth::id(),
            'type' => 'Interaction',
            'description' => 'You applied for an OJT position at ' . $companyName . '.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('newsfeed')->with('success', 'Application submitted successfully!');
    }
}