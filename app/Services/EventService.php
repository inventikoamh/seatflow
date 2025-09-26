<?php

namespace App\Services;

use App\Models\Event;

class EventService
{
    /**
     * Get the current default event for a specific type.
     */
    public static function getCurrentEvent(string $type = null): ?Event
    {
        return Event::getDefaultEvent($type);
    }

    /**
     * Get the current default Ramzaan event.
     */
    public static function getCurrentRamzaanEvent(): ?Event
    {
        return Event::getDefaultEvent('ramzaan');
    }

    /**
     * Get the current default Ashara event.
     */
    public static function getCurrentAsharaEvent(): ?Event
    {
        return Event::getDefaultEvent('ashara');
    }

    /**
     * Set the current event context for the application.
     */
    public static function setCurrentEvent(Event $event): void
    {
        $event->setAsDefault();
    }

    /**
     * Get all events with their default status.
     */
    public static function getAllEventsWithDefaults(): array
    {
        $ramzaanDefault = self::getCurrentRamzaanEvent();
        $asharaDefault = self::getCurrentAsharaEvent();

        return [
            'ramzaan' => $ramzaanDefault,
            'ashara' => $asharaDefault,
        ];
    }

    /**
     * Get the previous event for a given event.
     */
    public static function getPreviousEvent(Event $event): ?Event
    {
        return $event->previousEvent;
    }

    /**
     * Get events that can be used as previous events for a given type.
     */
    public static function getAvailablePreviousEvents(string $eventType, int $excludeId = null): array
    {
        $query = Event::where('event_type', $eventType)
                     ->where('is_active', true);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->orderBy('start_date', 'desc')->get()->toArray();
    }
}
