@extends('layouts.app')

@section('title', 'Create Mumin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-foreground">Create New Mumin</h1>
            <p class="text-muted-foreground">Add a new family member to the system</p>
        </div>

        <div class="bg-card p-6 rounded-lg border border-border">
            <form method="POST" action="{{ route('mumineen.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- ITS ID -->
                    <div>
                        <label for="ITS_ID" class="block text-sm font-medium text-foreground mb-1">
                            ITS ID <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="ITS_ID" id="ITS_ID" 
                               value="{{ old('ITS_ID') }}" 
                               class="w-full rounded-md border border-border bg-background px-3 py-2 @error('ITS_ID') border-red-500 @enderror"
                               maxlength="8" required>
                        @error('ITS_ID')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-muted-foreground mt-1">8-digit unique identifier</p>
                    </div>

                    <!-- Full Name -->
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-foreground mb-1">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="full_name" id="full_name" 
                               value="{{ old('full_name') }}" 
                               class="w-full rounded-md border border-border bg-background px-3 py-2 @error('full_name') border-red-500 @enderror"
                               required>
                        @error('full_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sabeel Code -->
                <div>
                    <label for="sabeel_code" class="block text-sm font-medium text-foreground mb-1">
                        Sabeel <span class="text-red-500">*</span>
                    </label>
                    <select name="sabeel_code" id="sabeel_code" 
                            class="w-full rounded-md border border-border bg-background px-3 py-2 @error('sabeel_code') border-red-500 @enderror"
                            required>
                        <option value="">Select Sabeel</option>
                        @foreach($sabeels as $sabeel)
                            <option value="{{ $sabeel->sabeel_code }}" 
                                    {{ old('sabeel_code', request('sabeel_code')) === $sabeel->sabeel_code ? 'selected' : '' }}>
                                {{ $sabeel->sabeel_code }} - {{ ucfirst($sabeel->sabeel_sector) }} 
                                @if($sabeel->sabeel_type !== 'regular')
                                    ({{ ucfirst(str_replace('_', ' ', $sabeel->sabeel_type)) }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('sabeel_code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mobile Number -->
                <div>
                    <label for="mobile_number" class="block text-sm font-medium text-foreground mb-1">
                        Mobile Number
                    </label>
                    <input type="tel" name="mobile_number" id="mobile_number" 
                           value="{{ old('mobile_number') }}" 
                           class="w-full rounded-md border border-border bg-background px-3 py-2 @error('mobile_number') border-red-500 @enderror"
                           placeholder="98765 43210">
                    @error('mobile_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-muted-foreground mt-1">Optional - Mobile number for contact</p>
                </div>

                <!-- Age -->
                <div>
                    <label for="age" class="block text-sm font-medium text-foreground mb-1">
                        Age
                    </label>
                    <input type="number" name="age" id="age" 
                           value="{{ old('age') }}" 
                           class="w-full rounded-md border border-border bg-background px-3 py-2 @error('age') border-red-500 @enderror"
                           placeholder="25" min="0" max="120">
                    @error('age')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-muted-foreground mt-1">Optional - Age in years</p>
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-foreground mb-1">
                        Gender <span class="text-red-500">*</span>
                    </label>
                    <select name="gender" id="gender" 
                            class="w-full rounded-md border border-border bg-background px-3 py-2 @error('gender') border-red-500 @enderror"
                            required>
                        <option value="">Select Gender</option>
                        <option value="M" {{ old('gender') === 'M' ? 'selected' : '' }}>Male (M)</option>
                        <option value="F" {{ old('gender') === 'F' ? 'selected' : '' }}>Female (F)</option>
                    </select>
                    @error('gender')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-foreground mb-1">
                            Type <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" 
                                class="w-full rounded-md border border-border bg-background px-3 py-2 @error('type') border-red-500 @enderror"
                                required>
                            <option value="">Select Type</option>
                            <option value="resident" {{ old('type') === 'resident' ? 'selected' : '' }}>Resident</option>
                            <option value="mehmaan" {{ old('type') === 'mehmaan' ? 'selected' : '' }}>Mehmaan</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Misaq -->
                    <div>
                        <label for="misaq" class="block text-sm font-medium text-foreground mb-1">
                            Misaq <span class="text-red-500">*</span>
                        </label>
                        <select name="misaq" id="misaq" 
                                class="w-full rounded-md border border-border bg-background px-3 py-2 @error('misaq') border-red-500 @enderror"
                                required>
                            <option value="">Select Misaq Status</option>
                            <option value="done" {{ old('misaq') === 'done' ? 'selected' : '' }}>Done</option>
                            <option value="not_done" {{ old('misaq') === 'not_done' ? 'selected' : '' }}>Not Done</option>
                        </select>
                        @error('misaq')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-4 pt-6 border-t border-border">
                    <a href="{{ route('mumineen.index') }}" 
                       class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                        Create Mumin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-format mobile number
document.getElementById('mobile_number').addEventListener('input', function(e) {
    // Get only digits from the input
    let digits = e.target.value.replace(/\D/g, '');
    
    // Limit to 10 digits
    if (digits.length > 10) {
        digits = digits.substring(0, 10);
    }
    
    // Format with space after 5 digits
    let formatted = '';
    if (digits.length >= 6) {
        formatted = digits.substring(0, 5) + ' ' + digits.substring(5);
    } else {
        formatted = digits;
    }
    
    e.target.value = formatted;
});

// Auto-format ITS ID
document.getElementById('ITS_ID').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 8) {
        value = value.substring(0, 8);
    }
    e.target.value = value;
});
</script>
@endsection
