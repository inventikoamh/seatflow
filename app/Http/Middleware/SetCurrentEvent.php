<?php

namespace App\Http\Middleware;

use App\Services\EventService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SetCurrentEvent
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Get current events
        $currentEvents = EventService::getAllEventsWithDefaults();
        
        // Share with all views
        View::share('currentEvents', $currentEvents);
        
        // Add to request for controllers
        $request->merge(['currentEvents' => $currentEvents]);

        return $next($request);
    }
}
