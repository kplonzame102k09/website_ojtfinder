<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    public function show($type)
    {
        $documents = [
            'terms' => [
                'title' => 'Terms of Service',
                'color' => 'indigo-500',
                'content' => '
                    <h3>01. General Usage</h3>
                    <p>By accessing <strong>ojtFinder</strong>, you agree to comply with all local laws and regulations. This platform facilitates the connection between students and industry partners.</p>
                    <h3>02. Account Integrity</h3>
                    <p>Users must provide accurate, current, and complete information during the registration process. Use of "burner" emails or falsified corporate documents is grounds for immediate termination.</p>
                    <h3>03. Limitation of Liability</h3>
                    <p>ojtFinder is a matching platform. We are not responsible for the specific outcomes of internships or the internal conduct of participating companies.</p>'
            ],
            'privacy' => [
                'title' => 'Privacy Policy',
                'color' => 'emerald-500',
                'content' => '
                    <h3>01. Information Collection</h3>
                    <p>We collect corporate data (Business Permits, SEC/DTI registration) and personal student data (Resumes, contact info) to facilitate the OJT matching process.</p>
                    <h3>02. Data Usage</h3>
                    <p>Your data is never sold to third-party advertisers. It is only shared between students and the specific companies they apply to.</p>
                    <h3>03. Encryption</h3>
                    <p>All sensitive documents are stored using AES-256 encryption. Access is restricted to authorized platform administrators only.</p>'
            ],
            'cookies' => [
                'title' => 'Cookies Policy',
                'color' => 'blue-500',
                'content' => '
                    <h3>01. Essential Cookies</h3>
                    <p>These cookies are required for security and session management. Without these, the "Remember Me" and secure login features will not function.</p>
                    <h3>02. Experience Cookies</h3>
                    <p>We use local storage to remember your theme preferences and recent search queries to improve your workflow speed.</p>'
            ]
        ];

        // If the type doesn't exist in our array, show a 404
        if (!array_key_exists($type, $documents)) {
            abort(404);
        }

        return view('legal', ['doc' => $documents[$type]]);
    }
}