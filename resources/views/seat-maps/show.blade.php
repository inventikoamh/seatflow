@extends('layouts.seat-map')

@section('title', 'Seat Map - ' . $area->name)

@section('content')
<!-- Full Screen Header -->
<div class="bg-card border-b border-border">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-foreground">{{ $area->name }}</h1>
                <p class="text-muted-foreground mt-1">{{ $area->location->name }} • {{ $area->description }}</p>
                <div class="flex gap-4 mt-2 text-sm text-muted-foreground">
                    <span>Capacity: <span class="font-semibold text-foreground">{{ number_format($area->capacity) }}</span></span>
                    <span>Floor: <span class="font-semibold text-foreground">{{ $area->floor === 0 ? 'Ground' : 'Floor ' . $area->floor }}</span></span>
                    <span>Gender: <span class="font-semibold text-foreground">{{ ucfirst($area->gender_type) }}</span></span>
                    <span>Event: <span class="font-semibold text-foreground">{{ ucfirst($area->event_type) }}</span></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Full Screen Seat Map Container -->
<div class="flex-1 p-2 h-full">
    <div id="seat-map-container" class="w-full h-full">
        <!-- Seat map will be rendered here -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('seat-map-container');
    
    // Initialize React component
    if (window.React && window.ReactDOM && window.SeatMap) {
        const root = window.ReactDOM.createRoot(container);
        root.render(window.React.createElement(window.SeatMap, {
            areaId: {{ $area->id }},
            allowMultiSelect: true,
            readonly: false,
            showLegend: true,
            className: 'w-full',
            onSeatClick: handleSeatClick,
            onSeatSelect: handleSeatSelect
        }));
    } else {
        container.innerHTML = '<div class="text-center p-8 text-red-600">Seat map component not available</div>';
    }
});

function handleSeatClick(seat) {
    // Seat clicked - you can add custom logic here
}

function handleSeatSelect(seatIds) {
    // Seats selected - you can add custom logic here
}
</script>
@endsection
