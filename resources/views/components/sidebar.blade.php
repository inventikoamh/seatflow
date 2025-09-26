<!-- Sidebar -->
<div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-card border-r border-border transform transition-transform duration-300 ease-in-out lg:translate-x-0 {{ $sidebarOpen ?? false ? 'translate-x-0' : '-translate-x-full' }} flex flex-col">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-6 border-b border-border flex-shrink-0">
        <div class="flex items-center space-x-3">
            <div class="h-8 w-8 rounded-md bg-primary flex items-center justify-center">
                <svg class="h-5 w-5 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </div>
            <span class="text-lg font-semibold text-foreground">{{ config('app.name', 'SeatFlow') }}</span>
        </div>
        
        <!-- Close button for mobile -->
        <button 
            id="sidebar-close"
            class="lg:hidden p-2 rounded-md text-muted-foreground hover:text-foreground hover:bg-accent transition-colors"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto min-h-0">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" 
           class="flex items-center space-x-3 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:text-foreground hover:bg-accent' }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- User Management -->
        @if(auth()->user()->hasPermission('view-users') || auth()->user()->hasPermission('create-users') || auth()->user()->hasPermission('edit-users') || auth()->user()->hasPermission('delete-users') || auth()->user()->hasPermission('manage-users'))
        <div class="space-y-1">
            <button 
                id="users-menu-toggle"
                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors"
            >
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    <span>Users</span>
                </div>
                <svg class="h-4 w-4 transition-transform" id="users-menu-arrow">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div id="users-menu" class="hidden ml-6 space-y-1">
                @if(auth()->user()->hasPermission('view-users'))
                <a href="{{ route('users.index') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    All Users
                </a>
                @endif
                @if(auth()->user()->hasPermission('create-users'))
                <a href="{{ route('users.create') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    Add User
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Role Management -->
        @if(auth()->user()->hasPermission('view-roles') || auth()->user()->hasPermission('create-roles') || auth()->user()->hasPermission('edit-roles') || auth()->user()->hasPermission('delete-roles') || auth()->user()->hasPermission('manage-roles'))
        <div class="space-y-1">
            <button 
                id="roles-menu-toggle"
                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors"
            >
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Roles</span>
                </div>
                <svg class="h-4 w-4 transition-transform" id="roles-menu-arrow">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div id="roles-menu" class="hidden ml-6 space-y-1">
                @if(auth()->user()->hasPermission('view-roles'))
                <a href="{{ route('roles.index') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    All Roles
                </a>
                @endif
                @if(auth()->user()->hasPermission('create-roles'))
                <a href="{{ route('roles.create') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    Add Role
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Sabeel Management -->
        @if(auth()->user()->hasPermission('view-sabeels') || auth()->user()->hasPermission('create-sabeels') || auth()->user()->hasPermission('edit-sabeels') || auth()->user()->hasPermission('delete-sabeels') || auth()->user()->hasPermission('manage-sabeels'))
        <div class="space-y-1">
            <button 
                id="sabeels-menu-toggle"
                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors"
            >
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                    </svg>
                    <span>Sabeels</span>
                </div>
                <svg class="h-4 w-4 transition-transform" id="sabeels-menu-arrow">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div id="sabeels-menu" class="hidden ml-6 space-y-1">
                @if(auth()->user()->hasPermission('view-sabeels'))
                <a href="{{ route('sabeels.index') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    All Sabeels
                </a>
                @endif
                @if(auth()->user()->hasPermission('create-sabeels'))
                <a href="{{ route('sabeels.create') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    Add Sabeel
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Mumin Management -->
        @if(auth()->user()->hasPermission('view-mumineen') || auth()->user()->hasPermission('create-mumineen') || auth()->user()->hasPermission('edit-mumineen') || auth()->user()->hasPermission('delete-mumineen') || auth()->user()->hasPermission('manage-mumineen'))
        <div class="space-y-1">
            <button 
                id="mumineen-menu-toggle"
                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors"
            >
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Mumineen</span>
                </div>
                <svg class="h-4 w-4 transition-transform" id="mumineen-menu-arrow">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div id="mumineen-menu" class="hidden ml-6 space-y-1">
                @if(auth()->user()->hasPermission('view-mumineen'))
                <a href="{{ route('mumineen.index') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    All Mumineen
                </a>
                @endif
                @if(auth()->user()->hasPermission('create-mumineen'))
                <a href="{{ route('mumineen.create') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    Add Mumin
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Location Management -->
        @if(auth()->user()->hasPermission('view-locations') || auth()->user()->hasPermission('create-locations') || auth()->user()->hasPermission('edit-locations') || auth()->user()->hasPermission('delete-locations') || auth()->user()->hasPermission('manage-locations'))
        <div class="space-y-1">
            <button 
                id="locations-menu-toggle"
                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors"
            >
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Locations</span>
                </div>
                <svg class="h-4 w-4 transition-transform" id="locations-menu-arrow">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div id="locations-menu" class="hidden ml-6 space-y-1">
                @if(auth()->user()->hasPermission('view-locations'))
                <a href="{{ route('locations.index') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    All Locations
                </a>
                @endif
                @if(auth()->user()->hasPermission('create-locations'))
                <a href="{{ route('locations.create') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    Add Location
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Event Management -->
        @if(auth()->user()->hasPermission('view-events') || auth()->user()->hasPermission('create-events') || auth()->user()->hasPermission('edit-events') || auth()->user()->hasPermission('delete-events') || auth()->user()->hasPermission('manage-events'))
        <div class="space-y-1">
            <button 
                id="events-menu-toggle"
                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors"
            >
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Events</span>
                </div>
                <svg class="h-4 w-4 transition-transform" id="events-menu-arrow">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div id="events-menu" class="hidden ml-6 space-y-1">
                @if(auth()->user()->hasPermission('view-events'))
                <a href="{{ route('events.index') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    All Events
                </a>
                @endif
                @if(auth()->user()->hasPermission('create-events'))
                <a href="{{ route('events.create') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    Add Event
                </a>
                @endif
            </div>
        </div>
        @endif

        
        
        <!-- Takhmeen Management -->
        @if(auth()->user()->hasPermission('view-takhmeen') || auth()->user()->hasPermission('create-takhmeen') || auth()->user()->hasPermission('edit-takhmeen') || auth()->user()->hasPermission('delete-takhmeen') || auth()->user()->hasPermission('manage-takhmeen'))
        <div class="space-y-1">
            <button 
                id="takhmeen-menu-toggle"
                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors"
            >
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                    <span>Takhmeen</span>
                </div>
                <svg class="h-4 w-4 transition-transform" id="takhmeen-menu-arrow">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div id="takhmeen-menu" class="hidden ml-6 space-y-1">
                @if(auth()->user()->hasPermission('view-takhmeen'))
                <a href="{{ route('takhmeen.index') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    All Takhmeen
                </a>
                @endif
                @if(auth()->user()->hasPermission('create-takhmeen'))
                <a href="{{ route('takhmeen.create') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    Add Takhmeen
                </a>
                @endif
                @if(auth()->user()->hasPermission('create-takhmeen'))
                <a href="{{ route('takhmeen.import') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    Import Takhmeen
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- NOC Management -->
        @if(auth()->user()->hasPermission('view-noc') || auth()->user()->hasPermission('create-noc') || auth()->user()->hasPermission('edit-noc') || auth()->user()->hasPermission('delete-noc') || auth()->user()->hasPermission('manage-noc'))
        <div class="space-y-1">
            <button 
                id="noc-menu-toggle"
                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors"
            >
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>NOC</span>
                </div>
                <svg class="h-4 w-4 transition-transform" id="noc-menu-arrow">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div id="noc-menu" class="hidden ml-6 space-y-1">
                @if(auth()->user()->hasPermission('view-noc'))
                <a href="{{ route('noc.index') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    All NOC
                </a>
                @endif
                @if(auth()->user()->hasPermission('create-noc'))
                <a href="{{ route('noc.create') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    Add NOC
                </a>
                @endif
                @if(auth()->user()->hasPermission('create-noc'))
                <a href="{{ route('noc.import') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    Import NOC
                </a>
                @endif
            </div>
        </div>
        @endif


        <!-- Reports -->
        <a href="#" 
           class="flex items-center space-x-3 px-3 py-2 rounded-md text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent transition-colors">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span>Reports</span>
        </a>

        <!-- Settings Management (Admin Only) -->
        @if(auth()->user()->hasRole('administrator'))
        <div class="space-y-1">
            <button 
                id="settings-menu-toggle"
                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors"
            >
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Settings</span>
                </div>
                <svg class="h-4 w-4 transition-transform" id="settings-menu-arrow">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div id="settings-menu" class="hidden ml-6 space-y-1">
                <a href="{{ route('settings.index') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    Dashboard
                </a>
                <a href="{{ route('settings.migrations') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    Migrations
                </a>
                <a href="{{ route('settings.seeders') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    Seeders
                </a>
                <a href="{{ route('settings.storage') }}" class="block px-3 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors">
                    Storage
                </a>
            </div>
        </div>
        @endif
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-border flex-shrink-0">
        <div class="flex items-center space-x-3 px-3 py-2">
            <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-primary-foreground text-sm font-medium">
                {{ substr(auth()->user()->display_name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-foreground truncate">{{ auth()->user()->display_name }}</p>
                <p class="text-xs text-muted-foreground truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Sidebar Overlay (Mobile) -->
<div id="sidebar-overlay" class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden {{ $sidebarOpen ?? false ? 'block' : 'hidden' }}"></div>

<script>
// Fallback JavaScript for sidebar menu toggles
document.addEventListener('DOMContentLoaded', function() {
    console.log('Fallback sidebar menu initialization...');
    
    // Debug: Check what menu elements exist
    console.log('=== MENU ELEMENTS DEBUG ===');
    const menuElements = [
        'users-menu-toggle', 'users-menu',
        'roles-menu-toggle', 'roles-menu', 
        'sabeels-menu-toggle', 'sabeels-menu',
        'mumineen-menu-toggle', 'mumineen-menu',
        'locations-menu-toggle', 'locations-menu',
        'events-menu-toggle', 'events-menu',
        'takhmeen-menu-toggle', 'takhmeen-menu',
        'noc-menu-toggle', 'noc-menu',
        'settings-menu-toggle', 'settings-menu'
    ];
    
    menuElements.forEach(id => {
        const element = document.getElementById(id);
        console.log(`${id}:`, element ? 'FOUND' : 'NOT FOUND');
    });
    console.log('=== END MENU DEBUG ===');
    
    // Generic menu toggle function
    function setupMenuToggle(toggleId, menuId, arrowId) {
        const toggle = document.getElementById(toggleId);
        const menu = document.getElementById(menuId);
        const arrow = document.getElementById(arrowId);
        
        console.log(`Setting up ${toggleId}:`, { toggle, menu, arrow });
        
        if (toggle && menu && arrow) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                console.log(`Toggling ${menuId}`);
                menu.classList.toggle('hidden');
                
                const isHidden = menu.classList.contains('hidden');
                arrow.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(180deg)';
                
                console.log(`${menuId} is now ${isHidden ? 'hidden' : 'visible'}`);
            });
        } else {
            console.warn(`Could not find elements for ${toggleId}`);
        }
    }

    // Setup all menu toggles
    setupMenuToggle('users-menu-toggle', 'users-menu', 'users-menu-arrow');
    setupMenuToggle('roles-menu-toggle', 'roles-menu', 'roles-menu-arrow');
    setupMenuToggle('sabeels-menu-toggle', 'sabeels-menu', 'sabeels-menu-arrow');
    setupMenuToggle('mumineen-menu-toggle', 'mumineen-menu', 'mumineen-menu-arrow');
    setupMenuToggle('locations-menu-toggle', 'locations-menu', 'locations-menu-arrow');
    setupMenuToggle('events-menu-toggle', 'events-menu', 'events-menu-arrow');
    setupMenuToggle('takhmeen-menu-toggle', 'takhmeen-menu', 'takhmeen-menu-arrow');
    setupMenuToggle('noc-menu-toggle', 'noc-menu', 'noc-menu-arrow');
    setupMenuToggle('settings-menu-toggle', 'settings-menu', 'settings-menu-arrow');
    
    // Additional debug for Takhmeen menu
    const takhmeenToggle = document.getElementById('takhmeen-menu-toggle');
    const takhmeenMenu = document.getElementById('takhmeen-menu');
    if (takhmeenToggle && takhmeenMenu) {
        console.log('Takhmeen menu elements found:', { takhmeenToggle, takhmeenMenu });
        takhmeenToggle.addEventListener('click', function(e) {
            console.log('Takhmeen menu clicked!');
            e.preventDefault();
            takhmeenMenu.classList.toggle('hidden');
            console.log('Takhmeen menu hidden:', takhmeenMenu.classList.contains('hidden'));
        });
    } else {
        console.log('Takhmeen menu elements NOT found:', { takhmeenToggle, takhmeenMenu });
    }

    // Additional debug for NOC menu
    const nocToggle = document.getElementById('noc-menu-toggle');
    const nocMenu = document.getElementById('noc-menu');
    if (nocToggle && nocMenu) {
        console.log('NOC menu elements found:', { nocToggle, nocMenu });
        nocToggle.addEventListener('click', function(e) {
            console.log('NOC menu clicked!');
            e.preventDefault();
            nocMenu.classList.toggle('hidden');
            console.log('NOC menu hidden:', nocMenu.classList.contains('hidden'));
        });
    } else {
        console.log('NOC menu elements NOT found:', { nocToggle, nocMenu });
    }

    // Additional debug for Settings menu
    const settingsToggle = document.getElementById('settings-menu-toggle');
    const settingsMenu = document.getElementById('settings-menu');
    if (settingsToggle && settingsMenu) {
        console.log('Settings menu elements found:', { settingsToggle, settingsMenu });
        settingsToggle.addEventListener('click', function(e) {
            console.log('Settings menu clicked!');
            e.preventDefault();
            settingsMenu.classList.toggle('hidden');
            console.log('Settings menu hidden:', settingsMenu.classList.contains('hidden'));
        });
    } else {
        console.log('Settings menu elements NOT found:', { settingsToggle, settingsMenu });
    }

    // Sidebar close functionality
    const sidebarClose = document.getElementById('sidebar-close');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    
    if (sidebarClose && sidebar && sidebarOverlay) {
        sidebarClose.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });
    }

    if (sidebarOverlay && sidebar) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });
    }
});
</script>
