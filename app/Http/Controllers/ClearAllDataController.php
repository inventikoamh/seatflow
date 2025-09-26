<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sabeel;
use App\Models\Mumin;
use Illuminate\Support\Facades\DB;

class ClearAllDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Add additional middleware for admin-only access if needed
        // $this->middleware('can:manage-system');
    }

    /**
     * Show the clear all data confirmation page.
     */
    public function index()
    {
        // Get counts for display
        $sabeelCount = Sabeel::count();
        $muminCount = Mumin::count();
        
        return view('admin.clear-all-data', compact('sabeelCount', 'muminCount'));
    }

    /**
     * Force delete all sabeels and mumineen.
     */
    public function clearAll(Request $request)
    {
        $request->validate([
            'confirmation_text' => 'required|in:DELETE ALL DATA',
            'confirm_checkbox' => 'required|accepted'
        ]);

        try {
            DB::transaction(function () {
                // Delete all mumineen first (due to foreign key constraints)
                Mumin::query()->delete();
                
                // Delete all sabeels
                Sabeel::query()->delete();
            });

            return redirect()->route('dashboard')
                ->with('success', 'All sabeels and mumineen have been permanently deleted.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error clearing data: ' . $e->getMessage());
        }
    }

    /**
     * Clear only mumineen (keep sabeels).
     */
    public function clearMumineen(Request $request)
    {
        $request->validate([
            'confirmation_text' => 'required|in:DELETE ALL MUMINEEN',
            'confirm_checkbox' => 'required|accepted'
        ]);

        try {
            Mumin::query()->delete();

            return redirect()->route('dashboard')
                ->with('success', 'All mumineen have been permanently deleted.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error clearing mumineen: ' . $e->getMessage());
        }
    }

    /**
     * Clear only sabeels (and their mumineen).
     */
    public function clearSabeels(Request $request)
    {
        $request->validate([
            'confirmation_text' => 'required|in:DELETE ALL SABEELS',
            'confirm_checkbox' => 'required|accepted'
        ]);

        try {
            DB::transaction(function () {
                // Delete all mumineen first (due to foreign key constraints)
                Mumin::query()->delete();
                
                // Delete all sabeels
                Sabeel::query()->delete();
            });

            return redirect()->route('dashboard')
                ->with('success', 'All sabeels and their mumineen have been permanently deleted.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error clearing sabeels: ' . $e->getMessage());
        }
    }
}
