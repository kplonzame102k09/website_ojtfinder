<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SignupController extends Controller
{
    public function showSignupForm()
    {
        return view('auth.signup');
    }

    public function signup(Request $request)
    {
        //========================== Combine Address fields into one string ==========================//
        $fullAddress = trim(implode(', ', array_filter([
            $request->barangay,
            $request->city,
            $request->province,
            $request->region
        ])));

        $birthday = $request->year . '-' . 
                    date('m', strtotime($request->month)) . '-' . 
                    str_pad($request->day, 2, '0', STR_PAD_LEFT);

        $request->merge([
            'address' => $fullAddress,
            'birthday' => $birthday
        ]);
        $request->validate([
            'first_name' => 'required|string|max:255',
            'surname'    => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:8',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:500', // Validating the joined string
            'gender'     => 'required|string|in:male,female,other',
        ]);

        //========================== Create User ==========================//
        $user = User::create([
            'name'     => $request->first_name . ' ' . $request->surname,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'contact_number' => $request->contact_number,
            'address'  => $request->address,
            'gender'   => $request->gender,
            'birthday' => $birthday,
        ]);

        Auth::login($user);

        //========================== Redirecting to newsfeed (or home) since they are already logged in ==========================//
        return redirect()->route('home')->with('success', 'Account created successfully!');
    }
}