<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Show Home Page
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('pages.home');
    }
}