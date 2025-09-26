<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Area;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create Masjid location
        $masjid = Location::firstOrCreate(
            ['slug' => 'masjid'],
            [
                'name' => 'Masjid',
                'description' => 'Main Masjid building with multiple floors and sections',
                'is_active' => true,
            ]
        );

        // Get or create Tayyebi Hall location
        $tayyebiHall = Location::firstOrCreate(
            ['slug' => 'tayyebi-hall'],
            [
                'name' => 'Tayyebi Hall',
                'description' => 'Tayyebi Hall with multiple sections for different events',
                'is_active' => true,
            ]
        );

        // Masjid Areas for Ramzaan
        $masjidAreas = [
            [
                'name' => 'Masjid Ground Floor (Gents)',
                'slug' => 'masjid-ground-floor-gents',
                'description' => 'Ground floor seating area for men during Ramzaan',
                'capacity' => 400,
                'gender_type' => 'male',
                'floor' => 0,
                'section' => 'main',
                'event_type' => 'ramzaan',
            ],
            [
                'name' => 'Sehan Ground Floor (Gents)',
                'slug' => 'sehan-ground-floor-gents',
                'description' => 'Ground floor courtyard seating area for men during Ramzaan',
                'capacity' => 200,
                'gender_type' => 'male',
                'floor' => 0,
                'section' => 'courtyard',
                'event_type' => 'ramzaan',
            ],
            [
                'name' => 'Masjid First Floor (Ladies)',
                'slug' => 'masjid-first-floor-ladies',
                'description' => 'First floor seating area for women during Ramzaan',
                'capacity' => 300,
                'gender_type' => 'female',
                'floor' => 1,
                'section' => 'main',
                'event_type' => 'ramzaan',
            ],
            [
                'name' => 'Sehan First Floor (Ladies)',
                'slug' => 'sehan-first-floor-ladies',
                'description' => 'First floor courtyard seating area for women during Ramzaan',
                'capacity' => 150,
                'gender_type' => 'female',
                'floor' => 1,
                'section' => 'courtyard',
                'event_type' => 'ramzaan',
            ],
            [
                'name' => 'Masjid Second Floor (Ladies)',
                'slug' => 'masjid-second-floor-ladies',
                'description' => 'Second floor seating area for women during Ramzaan',
                'capacity' => 250,
                'gender_type' => 'female',
                'floor' => 2,
                'section' => 'main',
                'event_type' => 'ramzaan',
            ],
        ];

        // Tayyebi Areas for Ramzaan
        $tayyebiAreas = [
            [
                'name' => 'Tayyebi Center (Gents)',
                'slug' => 'tayyebi-center-gents',
                'description' => 'Center section seating area for men during Ramzaan',
                'capacity' => 300,
                'gender_type' => 'male',
                'floor' => 0,
                'section' => 'center',
                'event_type' => 'ramzaan',
            ],
            [
                'name' => 'Tayyebi Left (Ladies)',
                'slug' => 'tayyebi-left-ladies',
                'description' => 'Left section seating area for women during Ramzaan',
                'capacity' => 200,
                'gender_type' => 'female',
                'floor' => 0,
                'section' => 'left',
                'event_type' => 'ramzaan',
            ],
            [
                'name' => 'Tayyebi Right (Ladies on Chair)',
                'slug' => 'tayyebi-right-ladies-chair',
                'description' => 'Right section seating area for women on chairs during Ramzaan (Rahat Block)',
                'capacity' => 150,
                'gender_type' => 'female',
                'floor' => 0,
                'section' => 'right',
                'event_type' => 'ramzaan',
            ],
        ];

        // Create Masjid areas
        foreach ($masjidAreas as $areaData) {
            Area::firstOrCreate(
                [
                    'location_id' => $masjid->id,
                    'slug' => $areaData['slug']
                ],
                array_merge($areaData, [
                    'location_id' => $masjid->id,
                    'is_active' => true,
                ])
            );
        }

        // Create Tayyebi areas
        foreach ($tayyebiAreas as $areaData) {
            Area::firstOrCreate(
                [
                    'location_id' => $tayyebiHall->id,
                    'slug' => $areaData['slug']
                ],
                array_merge($areaData, [
                    'location_id' => $tayyebiHall->id,
                    'is_active' => true,
                ])
            );
        }

        $this->command->info('Areas seeded successfully!');
        $this->command->info('Created Masjid areas:');
        foreach ($masjidAreas as $area) {
            $this->command->info("- {$area['name']}: {$area['capacity']} capacity");
        }
        $this->command->info('Created Tayyebi areas:');
        foreach ($tayyebiAreas as $area) {
            $this->command->info("- {$area['name']}: {$area['capacity']} capacity");
        }
    }
}