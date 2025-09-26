@extends('layouts.app')

@section('title', 'User Details')

@section('content')
    <!-- Header -->
    <div class="mb-8 animate-fade-in">
        <div class="bg-card rounded-lg border border-border p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-foreground mb-2">{{ $user->display_name }}</h1>
                    <p class="text-muted-foreground">{{ $user->email }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-primary text-primary-foreground font-semibold text-xs uppercase tracking-widest rounded-md hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit User
                    </a>
                    <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-secondary text-secondary-foreground font-semibold text-xs uppercase tracking-widest rounded-md hover:bg-secondary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- User Information -->
        <div class="bg-card rounded-lg border border-border shadow-sm">
            <div class="p-6 border-b border-border">
                <h3 class="text-lg font-semibold text-foreground">User Information</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-muted-foreground">Full Name:</span>
                    <span class="text-sm text-foreground">{{ $user->full_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-muted-foreground">First Name:</span>
                    <span class="text-sm text-foreground">{{ $user->first_name ?: 'Not provided' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-muted-foreground">Last Name:</span>
                    <span class="text-sm text-foreground">{{ $user->last_name ?: 'Not provided' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-muted-foreground">Email:</span>
                    <span class="text-sm text-foreground">{{ $user->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-muted-foreground">Mobile:</span>
                    <span class="text-sm text-foreground">{{ $user->mobile ?: 'Not provided' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-muted-foreground">Theme Preference:</span>
                    <span class="text-sm text-foreground capitalize">{{ $user->theme_preference ?: 'light' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-muted-foreground">Email Verified:</span>
                    @if($user->email_verified_at)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                            Verified
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                            Not Verified
                        </span>
                    @endif
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-muted-foreground">Created:</span>
                    <span class="text-sm text-foreground">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-muted-foreground">Last Updated:</span>
                    <span class="text-sm text-foreground">{{ $user->updated_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- User Roles -->
        <div class="bg-card rounded-lg border border-border shadow-sm">
            <div class="p-6 border-b border-border">
                <h3 class="text-lg font-semibold text-foreground">User Roles</h3>
                <p class="text-sm text-muted-foreground mt-1">{{ $user->roles->count() }} roles assigned</p>
            </div>
            <div class="p-6">
                @if($user->roles->count() > 0)
                    <div class="space-y-3">
                        @foreach($user->roles as $role)
                            <div class="flex items-center justify-between p-3 border border-border rounded-md hover:bg-accent/50 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-primary-foreground font-medium text-sm">
                                        {{ substr($role->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-foreground">{{ $role->name }}</div>
                                        <div class="text-xs text-muted-foreground">{{ $role->slug }}</div>
                                    </div>
                                </div>
                                <a href="{{ route('roles.show', $role) }}" class="text-primary hover:text-primary/80 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-muted-foreground mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <p class="text-muted-foreground">No roles assigned to this user</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- User Permissions -->
    <div class="mt-8 bg-card rounded-lg border border-border shadow-sm">
        <div class="p-6 border-b border-border">
            <h3 class="text-lg font-semibold text-foreground">User Permissions</h3>
            <p class="text-sm text-muted-foreground mt-1">Permissions inherited from assigned roles</p>
        </div>
        <div class="p-6">
            @php
                $allPermissions = $user->roles->flatMap->permissions->unique('id');
            @endphp
            
            @if($allPermissions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($allPermissions->groupBy('module') as $module => $permissions)
                        <div class="border border-border rounded-md p-4">
                            <h4 class="text-sm font-semibold text-foreground mb-3 capitalize">{{ $module }} Permissions</h4>
                            <div class="space-y-2">
                                @foreach($permissions as $permission)
                                    <div class="flex items-center space-x-2">
                                        <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-sm text-foreground">{{ $permission->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-muted-foreground mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <p class="text-muted-foreground">No permissions assigned to this user</p>
                </div>
            @endif
        </div>
    </div>
@endsection
