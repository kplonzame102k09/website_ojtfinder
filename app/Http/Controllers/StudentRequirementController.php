<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentRequirement;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class StudentRequirementController extends Controller
{

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'student') {
            abort(403);
        }

        // Custom validation with error messages
        $validator = \Validator::make($request->all(), [
            'type' => 'required|in:resume,school_id,endorsement,request_letter,application_letter',
            'file' => 'required|file|max:1000000|mimes:pdf,jpg,jpeg,png',
        ], [
            'file.required' => 'Please select a file to upload.',
            'file.mimes' => 'Invalid file type. Only PDF and image files (jpg, jpeg, png) are allowed.',
            'file.max' => 'File size too large. Maximum allowed is 1 MB.',
            'type.required' => 'Please select the type of requirement.',
            'type.in' => 'Invalid requirement type selected.',
        ]);

        if ($validator->fails()) {
            // Redirect back with custom error messages
            return back()->withErrors($validator)->withInput();
        }

        // Store the file
        $path = $request->file('file')->store('student_requirements', 'public');

        // Save to DB
        auth()->user()->requirements()->create([
            'type' => $request->type,
            'file_path' => $path,
            'original_name' => $request->file('file')->getClientOriginalName(),
        ]);

        return back()->with('success', 'Requirement uploaded successfully.');
    }

      // ✅ ADDED METHOD (FOR DOWNLOAD)
    public function download(StudentRequirement $requirement)
    {
        $user = auth()->user();

        // Allow only owner or admin
        if ($user->id !== $requirement->user_id && $user->role !== 'company') {
            abort(403);
        }

        // Ensure file exists
        if (!Storage::disk('public')->exists($requirement->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download(
            $requirement->file_path,
            $requirement->original_name
        );
    }
    public function view(StudentRequirement $requirement)
{
    $user = auth()->user();

    // Allow only owner or admin
    if ($user->id !== $requirement->user_id && $user->role !== 'company') {
        abort(403);
    }

    // Ensure file exists
    if (!Storage::disk('public')->exists($requirement->file_path)) {
        abort(404, 'File not found.');
    }

    $filePath = Storage::disk('public')->path($requirement->file_path);
    $mime = Storage::disk('public')->mimeType($requirement->file_path);

    // Return file inline (for preview in browser)
    return response()->file($filePath, [
        'Content-Type' => $mime
    ]);
}
}

