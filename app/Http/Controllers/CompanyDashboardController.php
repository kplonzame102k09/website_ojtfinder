<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Activity;

class CompanyDashboardController extends Controller
{
    //========================== Show Company Dashboard ==========================//
    public function index()
    {
        $user = Auth::user();
        
        //========================== SECURITY GATE: If user has no registered company, kick them out ==========================//
        if (!$user->company) {
            return redirect()->route('newsfeed')->with('error', 'Access denied. You must be a registered company to view analytics.');
        }

        $company = $user->company;

        $applications = \App\Models\Application::whereHas('post', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['student', 'post']) 
            ->latest()
            ->get();

        return view('company.company_dashboard', [
            'company' => $company,
            'applications' => $applications
        ]);
    }
    //========================== Update Company Logo ==========================//
    public function updateLogo(Request $request)
    {
        $request->validate([
            'company_logo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $company = Auth::user()->company;

        //========================== Delete old logo if it exists ==========================//
        if ($company && $company->company_logo && Storage::disk('public')->exists($company->company_logo)) {
            Storage::disk('public')->delete($company->company_logo);
        }

        //========================== Store new logo in 'company_logos' folder ==========================//
        $path = $request->file('company_logo')->store('company_logos', 'public');

        //========================== Update database using the 'company_logo' column ==========================//
        $company->update([
            'company_logo' => $path,
        ]);

        //========================== LOG ACTIVITY ==========================//
        Activity::create([
            'user_id' => Auth::id(),
            'type' => 'Business',
            'description' => 'Updated the official company logo for ' . $company->company_name . '.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Company logo updated!');
    }

     //========================== Update company details ==========================//
    public function updateDetails(Request $request)
    {
        $company = Auth::user()->company;

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'address'      => 'nullable|string|max:255',
            'about'        => 'nullable|string|max:1000',
        ]);

        $company->update($validated);

        //========================== LOG ACTIVITY ==========================//
        Activity::create([
            'user_id' => Auth::id(),
            'type' => 'Business',
            'description' => 'Modified company profile details (name, email, or address).',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Company details updated successfully!');
    }
    public function updateStatus(Request $request, \App\Models\Application $application)
    {
        //========================== Security check: Ensure the company owns the post this application belongs to ==========================//
        if ($application->post->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected,pending'
        ]);

        $application->update([
            'status' => $validated['status']
        ]);

        //========================== Get the Company/User Name for the notification ==========================//
        $companyName = auth()->user()->company->company_name ?? auth()->user()->name;

        //========================== Ensure your Application model has a 'student' relationship pointing to the User model ==========================//
        $application->student->notify(new \App\Notifications\ApplicationStatusNotification(
            $validated['status'], 
            $companyName
        ));

        //========================== Optional: Log Activity ==========================//
        \App\Models\Activity::create([
            'user_id' => auth()->id(),
            'type' => 'Recruitment',
            'description' => "Changed status of {$application->student->name} to {$validated['status']}.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        //========================== Update the success message to be more specific ==========================//
        $msg = $validated['status'] === 'accepted' ? 'Applicant accepted!' : 'Applicant rejected.';
        
        return back()->with('success', $msg);
    }
}