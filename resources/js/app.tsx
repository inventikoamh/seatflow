import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import SeatMap from './components/SeatMap';

// Make SeatMap available globally
(window as any).SeatMap = SeatMap;
(window as any).React = React;
(window as any).ReactDOM = { createRoot };

// Theme switching functionality
class ThemeManager {
    private currentTheme: string = 'light';
    private isInitialized: boolean = false;

    constructor() {
        this.init();
    }

    init() {
        if (this.isInitialized) return;
        
        // Check for saved theme preference or default to light
        this.currentTheme = localStorage.getItem('theme') || 'light';
        this.setTheme(this.currentTheme);
        
        // Use more specific event delegation
        document.addEventListener('click', (e) => {
            const target = e.target as HTMLElement;
            const toggleButton = target.closest('[data-theme-toggle]');
            
            if (toggleButton) {
                e.preventDefault();
                e.stopPropagation();
                this.toggleTheme();
            }
        });

        // Also listen for direct button clicks
        document.addEventListener('DOMContentLoaded', () => {
            const toggleButtons = document.querySelectorAll('[data-theme-toggle]');
            toggleButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.toggleTheme();
                });
            });
        });

        this.isInitialized = true;
    }

    setTheme(theme: string) {
        this.currentTheme = theme;
        const html = document.documentElement;
        const body = document.body;
        
        // Remove all theme classes first
        html.classList.remove('dark', 'light');
        body.classList.remove('dark', 'light');
        
        // Add the new theme class
        html.classList.add(theme);
        body.classList.add(theme);
        
        // Update data attribute for CSS
        html.setAttribute('data-theme', theme);
        
        localStorage.setItem('theme', theme);
        
        // Update all theme toggle buttons
        this.updateThemeButtons();
        
        // Send theme update to server if user is authenticated
        this.updateServerTheme(theme);
    }

    updateThemeButtons() {
        const toggleButtons = document.querySelectorAll('[data-theme-toggle]');
        
        toggleButtons.forEach(button => {
            const icon = button.querySelector('svg');
            if (icon) {
                // Clear existing paths
                icon.innerHTML = '';
                
                // Add the appropriate icon
                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('stroke-linecap', 'round');
                path.setAttribute('stroke-linejoin', 'round');
                
                if (this.currentTheme === 'dark') {
                    // Sun icon for switching to light
                    path.setAttribute('d', 'M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z');
                } else {
                    // Moon icon for switching to dark
                    path.setAttribute('d', 'M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z');
                }
                
                icon.appendChild(path);
            }
        });
    }

    toggleTheme() {
        const newTheme = this.currentTheme === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
    }

    async updateServerTheme(theme: string) {
        try {
            // Only update server theme if user is authenticated
            if (document.querySelector('meta[name="csrf-token"]')) {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                await fetch('/theme', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token || '',
                    },
                    body: JSON.stringify({ theme }),
                });
            }
        } catch (error) {
            console.log('Theme update failed:', error);
        }
    }
}

// Initialize theme manager immediately and on DOM ready
const themeManager = new ThemeManager();

// Also initialize when DOM is loaded as backup
document.addEventListener('DOMContentLoaded', () => {
    if (!themeManager) {
        new ThemeManager();
    }
});

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', () => {
    const mobileMenuButton = document.querySelector('[data-mobile-menu-toggle]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
});

// Sidebar menu toggles
document.addEventListener('DOMContentLoaded', () => {
    console.log('Initializing sidebar menu toggles...');
    
    // Generic menu toggle function
    function setupMenuToggle(toggleId: string, menuId: string, arrowId: string) {
        const toggle = document.getElementById(toggleId);
        const menu = document.getElementById(menuId);
        const arrow = document.getElementById(arrowId);
        
        console.log(`Setting up ${toggleId}:`, { toggle, menu, arrow });
        
        if (toggle && menu && arrow) {
            toggle.addEventListener('click', (e) => {
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

    // Sidebar close button
    const sidebarClose = document.getElementById('sidebar-close');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    
    if (sidebarClose && sidebar && sidebarOverlay) {
        sidebarClose.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });
    }

    // Sidebar overlay click to close
    if (sidebarOverlay && sidebar) {
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });
    }
});

// Form validation enhancements
document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Processing...';
            }
        });
    });
});
