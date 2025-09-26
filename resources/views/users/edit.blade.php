@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <!-- Header -->
    <div class="mb-8 animate-fade-in">
        <div class="bg-card rounded-lg border border-border p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-foreground mb-2">Edit User</h1>
                    <p class="text-muted-foreground">Update user information and roles</p>
                </div>
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-secondary text-secondary-foreground font-semibold text-xs uppercase tracking-widest rounded-md hover:bg-secondary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Users
                </a>
            </div>
        </div>
    </div>

    <!-- Edit User Form -->
    <div class="bg-card rounded-lg border border-border shadow-sm">
        <div class="p-6 border-b border-border">
            <h3 class="text-lg font-semibold text-foreground">User Information</h3>
            <p class="text-muted-foreground text-sm mt-1">Update the user's personal information and roles.</p>
        </div>
        
        <form method="POST" action="{{ route('users.update', $user) }}" class="p-6" data-validate>
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-foreground mb-2">First Name *</label>
                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required autofocus class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('first_name') border-destructive @enderror" placeholder="Enter first name">
                    @error('first_name')
                        <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-foreground mb-2">Last Name *</label>
                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('last_name') border-destructive @enderror" placeholder="Enter last name">
                    @error('last_name')
                        <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div class="mt-6">
                <label for="email" class="block text-sm font-medium text-foreground mb-2">Email Address *</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('email') border-destructive @enderror" placeholder="Enter email address">
                @error('email')
                    <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mobile -->
            <div class="mt-6">
                <label for="mobile" class="block text-sm font-medium text-foreground mb-2">Mobile Number</label>
                <input id="mobile" type="tel" name="mobile" value="{{ old('mobile', $user->mobile) }}" class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('mobile') border-destructive @enderror" placeholder="Enter mobile number">
                @error('mobile')
                    <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mt-6">
                <label for="password" class="block text-sm font-medium text-foreground mb-2">New Password</label>
                <input id="password" type="password" name="password" class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('password') border-destructive @enderror" placeholder="Leave blank to keep current password">
                <p class="mt-1 text-xs text-muted-foreground">Leave blank to keep the current password</p>
                @error('password')
                    <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mt-6">
                <label for="password_confirmation" class="block text-sm font-medium text-foreground mb-2">Confirm New Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="w-full px-3 py-2 border border-input rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring bg-background text-foreground @error('password_confirmation') border-destructive @enderror" placeholder="Confirm new password">
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <!-- Roles -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-foreground mb-3">Roles *</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($roles as $role)
                        <label class="flex items-center space-x-3 p-3 border border-input rounded-md hover:bg-accent/50 cursor-pointer transition-colors">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ in_array($role->id, old('roles', $userRoles)) ? 'checked' : '' }} class="rounded border-input text-primary focus:ring-ring">
                            <div class="flex-1">
                                <div class="text-sm font-medium text-foreground">{{ $role->name }}</div>
                                @if($role->description)
                                    <div class="text-xs text-muted-foreground">{{ $role->description }}</div>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('roles')
                    <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end mt-8 space-x-4">
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-secondary text-secondary-foreground font-semibold text-xs uppercase tracking-widest rounded-md hover:bg-secondary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 transition ease-in-out duration-150">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary text-primary-foreground font-semibold text-xs uppercase tracking-widest rounded-md hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 transition ease-in-out duration-150">
                    Update User
                </button>
            </div>
        </form>
    </div>
@endsection
