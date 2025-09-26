@extends('layouts.app')

@section('title', 'Clear All Data - DANGER ZONE')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Danger Header -->
        <div class="bg-red-50 border-2 border-red-200 rounded-lg p-6 mb-8">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <h1 class="text-3xl font-bold text-red-800">DANGER ZONE</h1>
            </div>
            <p class="text-red-700 text-lg font-semibold mb-2">
                ⚠️ This action will PERMANENTLY DELETE all data and cannot be undone!
            </p>
            <p class="text-red-600">
                This operation will forcefully delete all sabeels and mumineen regardless of their type, status, or any constraints.
            </p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Current Data Summary -->
        <div class="bg-card rounded-lg border border-border p-6 mb-8">
            <h2 class="text-xl font-semibold text-foreground mb-4">Current Data Summary</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <h3 class="font-semibold text-orange-800">Total Sabeels</h3>
                    <p class="text-3xl font-bold text-orange-600">{{ number_format($sabeelCount) }}</p>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-800">Total Mumineen</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ number_format($muminCount) }}</p>
                </div>
            </div>
        </div>

        <!-- Clear All Data Option -->
        <div class="bg-red-50 border-2 border-red-200 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-red-800 mb-4">🗑️ Clear ALL Data</h2>
            <p class="text-red-700 mb-4">
                This will delete ALL sabeels and ALL mumineen from the system. This action is IRREVERSIBLE!
            </p>
            
            <form method="POST" action="{{ route('admin.clear-all-data.all') }}" class="space-y-4" id="clearAllForm">
                @csrf
                
                <div class="bg-white border border-red-300 rounded-lg p-4">
                    <div class="mb-4">
                        <label for="confirmation_text_all" class="block text-sm font-medium text-red-800 mb-2">
                            Type "DELETE ALL DATA" to confirm:
                        </label>
                        <input type="text" name="confirmation_text" id="confirmation_text_all" 
                               class="w-full rounded-md border border-red-300 bg-white px-3 py-2 text-red-800 font-mono"
                               placeholder="DELETE ALL DATA" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="confirm_checkbox" class="rounded border-red-300 text-red-600 focus:ring-red-600" required>
                            <span class="ml-2 text-sm text-red-800 font-semibold">
                                I understand this action is PERMANENT and cannot be undone
                            </span>
                        </label>
                    </div>
                    
                    <button type="submit" 
                            class="bg-red-600 text-white px-6 py-3 rounded-md hover:bg-red-700 font-semibold"
                            onclick="return confirmFinalDeletion('ALL DATA')">
                        🗑️ DELETE ALL DATA
                    </button>
                </div>
            </form>
        </div>

        <!-- Clear Only Mumineen Option -->
        <div class="bg-orange-50 border-2 border-orange-200 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-orange-800 mb-4">👥 Clear Only Mumineen</h2>
            <p class="text-orange-700 mb-4">
                This will delete ALL mumineen but keep the sabeels. Useful for clearing member data while keeping family structure.
            </p>
            
            <form method="POST" action="{{ route('admin.clear-all-data.mumineen') }}" class="space-y-4" id="clearMumineenForm">
                @csrf
                
                <div class="bg-white border border-orange-300 rounded-lg p-4">
                    <div class="mb-4">
                        <label for="confirmation_text_mumineen" class="block text-sm font-medium text-orange-800 mb-2">
                            Type "DELETE ALL MUMINEEN" to confirm:
                        </label>
                        <input type="text" name="confirmation_text" id="confirmation_text_mumineen" 
                               class="w-full rounded-md border border-orange-300 bg-white px-3 py-2 text-orange-800 font-mono"
                               placeholder="DELETE ALL MUMINEEN" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="confirm_checkbox" class="rounded border-orange-300 text-orange-600 focus:ring-orange-600" required>
                            <span class="ml-2 text-sm text-orange-800 font-semibold">
                                I understand this will delete all {{ number_format($muminCount) }} mumineen permanently
                            </span>
                        </label>
                    </div>
                    
                    <button type="submit" 
                            class="bg-orange-600 text-white px-6 py-3 rounded-md hover:bg-orange-700 font-semibold"
                            onclick="return confirmFinalDeletion('ALL MUMINEEN')">
                        👥 DELETE ALL MUMINEEN
                    </button>
                </div>
            </form>
        </div>

        <!-- Clear Only Sabeels Option -->
        <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-purple-800 mb-4">🏠 Clear Only Sabeels</h2>
            <p class="text-purple-700 mb-4">
                This will delete ALL sabeels and their associated mumineen. This action is IRREVERSIBLE!
            </p>
            
            <form method="POST" action="{{ route('admin.clear-all-data.sabeels') }}" class="space-y-4" id="clearSabeelsForm">
                @csrf
                
                <div class="bg-white border border-purple-300 rounded-lg p-4">
                    <div class="mb-4">
                        <label for="confirmation_text_sabeels" class="block text-sm font-medium text-purple-800 mb-2">
                            Type "DELETE ALL SABEELS" to confirm:
                        </label>
                        <input type="text" name="confirmation_text" id="confirmation_text_sabeels" 
                               class="w-full rounded-md border border-purple-300 bg-white px-3 py-2 text-purple-800 font-mono"
                               placeholder="DELETE ALL SABEELS" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="confirm_checkbox" class="rounded border-purple-300 text-purple-600 focus:ring-purple-600" required>
                            <span class="ml-2 text-sm text-purple-800 font-semibold">
                                I understand this will delete all {{ number_format($sabeelCount) }} sabeels and {{ number_format($muminCount) }} mumineen permanently
                            </span>
                        </label>
                    </div>
                    
                    <button type="submit" 
                            class="bg-purple-600 text-white px-6 py-3 rounded-md hover:bg-purple-700 font-semibold"
                            onclick="return confirmFinalDeletion('ALL SABEELS')">
                        🏠 DELETE ALL SABEELS
                    </button>
                </div>
            </form>
        </div>

        <!-- Back to Safety -->
        <div class="text-center">
            <a href="{{ route('dashboard') }}" 
               class="bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 font-semibold">
                🏠 Back to Dashboard (Safe Zone)
            </a>
        </div>
    </div>
</div>

<script>
function confirmFinalDeletion(type) {
    const message = `Are you absolutely sure you want to delete ${type}?\n\nThis action is PERMANENT and cannot be undone!\n\nType "YES" to confirm:`;
    const userInput = prompt(message);
    
    if (userInput === "YES") {
        const finalConfirm = confirm(`FINAL WARNING: You are about to PERMANENTLY DELETE ${type}!\n\nThis action cannot be undone!\n\nClick OK only if you are 100% certain.`);
        return finalConfirm;
    }
    
    return false;
}

// Add visual feedback for dangerous actions
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[id^="clear"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = form.querySelector('button[type="submit"]');
            button.innerHTML = '⏳ Processing...';
            button.disabled = true;
        });
    });
});
</script>
@endsection
