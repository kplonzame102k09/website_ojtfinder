<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class CompanyRegisterController extends Controller
{
    // Show the registration form
    public function create()
    {
        $user = Auth::user();

        // Redirect if user already registered a company
        if ($user->company) {
            return redirect()->route('home')
                ->with('error', 'You have already registered a company.');
        }

        return view('company.company_register');
    }

    // Store company data
    public function store(Request $request)
    {
        $user = Auth::user();

        // Prevent duplicate registration
        if ($user->company) {
            return redirect()->route('home')
                ->with('error', 'You have already registered a company.');
        }

        // Validate input
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_logo' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'email' => 'required|email|unique:companies,email',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'about' => 'required|string|max:1000',
            'certificate_of_corporation' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'certificate_of_registration' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'mayors_permit' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'barangay_clearance' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        // 1. Specifically store the Company Logo in the folder the ImageController expects
        if ($request->hasFile('company_logo')) {
            $validated['company_logo'] = $request->file('company_logo')
                ->store('company_logos', 'public'); 
        }

        // 2. Store other legal documents in a separate folder
        $documentFields = [
            'certificate_of_corporation',
            'certificate_of_registration',
            'mayors_permit',
            'barangay_clearance'
        ];

        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                // Using underscores 'company_documents' instead of dashes
                $validated[$field] = $request->file($field)
                    ->store('company_documents/' . $user->id, 'public'); 
            }
        }

        // Associate with current user
        $validated['user_id'] = $user->id;

        // Create company record
        Company::create($validated);

        $user->role = 'company';
        $user->save();
        
        return redirect()->route('home') 
            ->with('success', 'Company registered successfully! You can now post and find OJT trainees.');
    }
}