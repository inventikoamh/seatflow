<!-- Top Navigation -->
<nav class="bg-card border-b border-border lg:ml-64">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left side - Mobile menu button and breadcrumb -->
            <div class="flex items-center">
                <!-- Mobile sidebar toggle -->
                <button 
                    id="sidebar-toggle"
                    class="lg:hidden p-2 rounded-md text-muted-foreground hover:text-foreground hover:bg-accent transition-colors"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Breadcrumb -->
                <nav class="hidden sm:flex items-center space-x-2 text-sm text-muted-foreground ml-4">
                    <a href="{{ route('dashboard') }}" class="hover:text-foreground transition-colors">Dashboard</a>
                    @if(isset($breadcrumbs))
                        @foreach($breadcrumbs as $breadcrumb)
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            @if(isset($breadcrumb['url']))
                                <a href="{{ $breadcrumb['url'] }}" class="hover:text-foreground transition-colors">{{ $breadcrumb['title'] }}</a>
                            @else
                                <span class="text-foreground">{{ $breadcrumb['title'] }}</span>
                            @endif
                        @endforeach
                    @endif
                </nav>
            </div>

            <!-- Right side - Search, notifications, user menu -->
            <div class="flex items-center space-x-4">
                <!-- Search -->
                <div class="hidden md:block">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            placeholder="Search..." 
                            class="block w-full pl-10 pr-3 py-2 border border-input rounded-md leading-5 bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring sm:text-sm"
                        >
                    </div>
                </div>

                <!-- Notifications -->
                <button class="p-2 rounded-md text-muted-foreground hover:text-foreground hover:bg-accent transition-colors relative">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828z" />
                    </svg>
                    <!-- Notification badge -->
                    <span class="absolute top-1 right-1 h-2 w-2 bg-red-500 rounded-full"></span>
                </button>

                <!-- Theme Toggle -->
                <button 
                    data-theme-toggle
                    class="p-2 rounded-md text-muted-foreground hover:text-foreground hover:bg-accent transition-colors"
                    title="Toggle theme"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                </button>

                <!-- User Menu -->
                <div class="relative">
                    <button 
                        class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ring"
                        onclick="toggleUserMenu()"
                    >
                        <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-primary-foreground font-medium">
                            {{ substr(auth()->user()->display_name, 0, 1) }}
                        </div>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-card rounded-md shadow-lg py-1 z-50 border border-border">
                        <div class="px-4 py-2 text-sm text-muted-foreground border-b border-border">
                            {{ auth()->user()->display_name }}
                        </div>
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-foreground hover:bg-accent">Profile</a>
                        <a href="#" class="block px-4 py-2 text-sm text-foreground hover:bg-accent">Settings</a>
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-foreground hover:bg-accent">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
