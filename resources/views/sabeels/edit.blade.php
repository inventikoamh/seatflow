@extends('layouts.app')

@section('title', 'Edit Sabeel')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-foreground">Edit Sabeel</h1>
            <p class="text-muted-foreground">Update sabeel information</p>
        </div>

        <div class="bg-card p-6 rounded-lg border border-border">
            <form method="POST" action="{{ route('sabeels.update', $sabeel) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Sabeel Code -->
                    <div>
                        <label for="sabeel_code" class="block text-sm font-medium text-foreground mb-1">
                            Sabeel Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="sabeel_code" id="sabeel_code" 
                               value="{{ old('sabeel_code', $sabeel->sabeel_code) }}" 
                               class="w-full rounded-md border border-border bg-background px-3 py-2 @error('sabeel_code') border-red-500 @enderror"
                               required>
                        @error('sabeel_code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sector -->
                    <div>
                        <label for="sabeel_sector" class="block text-sm font-medium text-foreground mb-1">
                            Sector <span class="text-red-500">*</span>
                        </label>
                        <select name="sabeel_sector" id="sabeel_sector" 
                                class="w-full rounded-md border border-border bg-background px-3 py-2 @error('sabeel_sector') border-red-500 @enderror"
                                required>
                            <option value="">Select Sector</option>
                            <option value="ezzi" {{ old('sabeel_sector', $sabeel->sabeel_sector) === 'ezzi' ? 'selected' : '' }}>Ezzi</option>
                            <option value="fakhri" {{ old('sabeel_sector', $sabeel->sabeel_sector) === 'fakhri' ? 'selected' : '' }}>Fakhri</option>
                            <option value="hakimi" {{ old('sabeel_sector', $sabeel->sabeel_sector) === 'hakimi' ? 'selected' : '' }}>Hakimi</option>
                            <option value="shujai" {{ old('sabeel_sector', $sabeel->sabeel_sector) === 'shujai' ? 'selected' : '' }}>Shujai</option>
                            <option value="al_masjid_us_saifee" {{ old('sabeel_sector', $sabeel->sabeel_sector) === 'al_masjid_us_saifee' ? 'selected' : '' }}>AL MASJID US SAIFEE</option>
                            <option value="raj_township" {{ old('sabeel_sector', $sabeel->sabeel_sector) === 'raj_township' ? 'selected' : '' }}>Raj Township</option>
                            <option value="zainy" {{ old('sabeel_sector', $sabeel->sabeel_sector) === 'zainy' ? 'selected' : '' }}>Zainy</option>
                            <option value="student" {{ old('sabeel_sector', $sabeel->sabeel_sector) === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="mtnc" {{ old('sabeel_sector', $sabeel->sabeel_sector) === 'mtnc' ? 'selected' : '' }}>MTNC</option>
                            <option value="unknown" {{ old('sabeel_sector', $sabeel->sabeel_sector) === 'unknown' ? 'selected' : '' }}>UNKNOWN</option>
                        </select>
                        @error('sabeel_sector')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label for="sabeel_address" class="block text-sm font-medium text-foreground mb-1">
                        Address <span class="text-red-500">*</span>
                    </label>
                    <textarea name="sabeel_address" id="sabeel_address" rows="3"
                              class="w-full rounded-md border border-border bg-background px-3 py-2 @error('sabeel_address') border-red-500 @enderror"
                              required>{{ old('sabeel_address', $sabeel->sabeel_address) }}</textarea>
                    @error('sabeel_address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Type -->
                    <div>
                        <label for="sabeel_type" class="block text-sm font-medium text-foreground mb-1">
                            Type <span class="text-red-500">*</span>
                        </label>
                        <select name="sabeel_type" id="sabeel_type" 
                                class="w-full rounded-md border border-border bg-background px-3 py-2 @error('sabeel_type') border-red-500 @enderror"
                                required>
                            <option value="">Select Type</option>
                            <option value="regular" {{ old('sabeel_type', $sabeel->sabeel_type) === 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="student" {{ old('sabeel_type', $sabeel->sabeel_type) === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="res_without_sabeel" {{ old('sabeel_type', $sabeel->sabeel_type) === 'res_without_sabeel' ? 'selected' : '' }}>Resident Without Sabeel</option>
                            <option value="moallemeen" {{ old('sabeel_type', $sabeel->sabeel_type) === 'moallemeen' ? 'selected' : '' }}>Moallemeen</option>
                            <option value="regular_lock_joint" {{ old('sabeel_type', $sabeel->sabeel_type) === 'regular_lock_joint' ? 'selected' : '' }}>Regular Lock/Joint</option>
                            <option value="left_sabeel" {{ old('sabeel_type', $sabeel->sabeel_type) === 'left_sabeel' ? 'selected' : '' }}>Left Sabeel</option>
                        </select>
                        @error('sabeel_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Head of Family -->
                    <div>
                        <label for="sabeel_hof" class="block text-sm font-medium text-foreground mb-1">
                            Head of Family ITS_ID <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="sabeel_hof" id="sabeel_hof" 
                               value="{{ old('sabeel_hof', $sabeel->sabeel_hof) }}" 
                               class="w-full rounded-md border border-border bg-background px-3 py-2 @error('sabeel_hof') border-red-500 @enderror"
                               placeholder="Enter 8-digit ITS_ID"
                               maxlength="8"
                               required>
                        @error('sabeel_hof')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-muted-foreground mt-1">Enter the 8-digit ITS_ID of the head of family</p>
                    </div>
                </div>


                <!-- Form Actions -->
                <div class="flex justify-end gap-4 pt-6 border-t border-border">
                    <a href="{{ route('sabeels.index') }}" 
                       class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                        Update Sabeel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
