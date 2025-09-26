<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Event;
use Illuminate\Database\Seeder;

class LocationEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Locations
        $masjid = Location::create([
            'name' => 'Masjid',
            'slug' => 'masjid',
            'description' => 'Main masjid building with multiple floors',
            'is_active' => true,
        ]);

        $tayyebi = Location::create([
            'name' => 'Tayyebi Hall',
            'slug' => 'tayyebi',
            'description' => 'Tayyebi hall building for additional seating',
            'is_active' => true,
        ]);

        // Create Events
        $ramzaan1446 = Event::create([
            'name' => 'Ramzaan 1446',
            'slug' => 'ramzaan-1446',
            'description' => 'Ramzaan seat allocation for year 1446',
            'start_date' => '2025-02-28',
            'end_date' => '2025-03-30',
            'event_type' => 'ramzaan',
            'previous_event_id' => null,
            'is_default' => false,
            'is_active' => true,
        ]);

        $ramzaan1447 = Event::create([
            'name' => 'Ramzaan 1447',
            'slug' => 'ramzaan-1447',
            'description' => 'Ramzaan seat allocation for year 1447',
            'start_date' => '2026-02-18',
            'end_date' => '2026-03-19',
            'event_type' => 'ramzaan',
            'previous_event_id' => $ramzaan1446->id,
            'is_default' => true, // Set as default Ramzaan event
            'is_active' => true,
        ]);

        $ashara1446 = Event::create([
            'name' => 'Ashara 1446',
            'slug' => 'ashara-1446',
            'description' => 'Ashara seat allocation for year 1446',
            'start_date' => '2025-07-25',
            'end_date' => '2025-08-03',
            'event_type' => 'ashara',
            'previous_event_id' => null,
            'is_default' => false,
            'is_active' => true,
        ]);

        $ashara1447 = Event::create([
            'name' => 'Ashara 1447',
            'slug' => 'ashara-1447',
            'description' => 'Ashara seat allocation for year 1447',
            'start_date' => '2026-07-14',
            'end_date' => '2026-07-23',
            'event_type' => 'ashara',
            'previous_event_id' => $ashara1446->id,
            'is_default' => true, // Set as default Ashara event
            'is_active' => true,
        ]);

        $this->command->info('Locations and Events created successfully!');
        $this->command->info('Default Events:');
        $this->command->info('- Ramzaan 1447 (Default Ramzaan)');
        $this->command->info('- Ashara 1447 (Default Ashara)');
    }
}
