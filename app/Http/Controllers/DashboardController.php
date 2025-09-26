<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        return view('dashboard', [
            'user' => $user,
            'theme' => $user->getThemePreference(),
            'breadcrumbs' => [
                ['title' => 'Dashboard']
            ]
        ]);
    }
}
