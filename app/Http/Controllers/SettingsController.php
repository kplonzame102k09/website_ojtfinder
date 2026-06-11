<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\Activity;

class SettingsController extends Controller
{
    public function edit()
    {
        // Load user with activities to show them in the view
        return view('pages.settings', [
            'user' => auth()->user()->load(['activities' => function($query) {
                $query->latest()->limit(10); // Only get the 10 most recent
            }])
        ]);
    }

public function update(Request $request)
{
    $user = auth()->user();

    $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        'contact_number' => ['nullable', 'string', 'max:20'], 
        'address' => ['nullable', 'string', 'max:255'],        
        'bio' => ['nullable', 'string', 'max:10000'],
        'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
    ]);

    try {
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $user->update($data);

        Activity::create([
            'user_id' => $user->id,
            'type' => 'Profile',
            'description' => 'Updated personal details.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('settings.edit')->with('success', 'Profile Details updated.');

    } catch (\Exception $e) {
        // Redirect back to settings.edit with an error message if the DB update fails
        return redirect()->route('settings.edit')
            ->withInput() // Keeps the data in the fields
            ->with('error', 'Synchronization failed.');
    }
}
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = auth()->user();
        
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Log the Security Activity
        Activity::create([
            'user_id' => $user->id,
            'type' => 'Security',
            'description' => 'Updated the Password.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Security encryption updated.');
    }
}