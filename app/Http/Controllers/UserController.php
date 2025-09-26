<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:view-users')->only(['index', 'show']);
        $this->middleware('can:create-users')->only(['create', 'store']);
        $this->middleware('can:edit-users')->only(['edit', 'update']);
        $this->middleware('can:delete-users')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->paginate(15);
        
        return view('users.index', [
            'users' => $users,
            'theme' => auth()->user()->getThemePreference(),
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('dashboard')],
                ['title' => 'Users']
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::active()->get();
        
        return view('users.create', [
            'roles' => $roles,
            'theme' => auth()->user()->getThemePreference(),
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('dashboard')],
                ['title' => 'Users', 'url' => route('users.index')],
                ['title' => 'Create User']
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'theme_preference' => 'light',
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles.permissions');
        
        return view('users.show', [
            'user' => $user,
            'theme' => auth()->user()->getThemePreference(),
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('dashboard')],
                ['title' => 'Users', 'url' => route('users.index')],
                ['title' => $user->display_name]
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::active()->get();
        $userRoles = $user->roles->pluck('id')->toArray();
        
        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles,
            'theme' => auth()->user()->getThemePreference(),
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('dashboard')],
                ['title' => 'Users', 'url' => route('users.index')],
                ['title' => 'Edit ' . $user->display_name]
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'mobile' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}