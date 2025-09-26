@extends('layouts.app')

@section('title', 'Edit ' . $location->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-foreground">Edit Location</h1>
            <p class="text-muted-foreground">Update location details</p>
        </div>

        <div class="bg-card rounded-lg border border-border shadow-sm">
            <form method="POST" action="{{ route('locations.update', $location) }}" class="p-6" data-validate>
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-foreground mb-2">
                            Location Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name', $location->name) }}" 
                               class="w-full rounded-md border border-border bg-background px-3 py-2 @error('name') border-red-500 @enderror" 
                               placeholder="e.g., Masjid, Tayyebi Hall"
                               required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-foreground mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full rounded-md border border-border bg-background px-3 py-2 @error('description') border-red-500 @enderror" 
                                  placeholder="Brief description of the location">{{ old('description', $location->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', $location->is_active) ? 'checked' : '' }}
                               class="rounded border-border text-primary focus:ring-primary">
                        <label for="is_active" class="ml-2 text-sm text-foreground">
                            Active Location
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('locations.show', $location) }}" 
                       class="bg-secondary text-secondary-foreground px-4 py-2 rounded-md hover:bg-secondary/90">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90">
                        Update Location
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
