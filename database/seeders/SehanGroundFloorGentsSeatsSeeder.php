<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Seat;

class SehanGroundFloorGentsSeatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $area = Area::where('slug', 'sehan-ground-floor-gents')->first();
        
        if (!$area) {
            $this->command->error('Sehan Ground Floor (Gents) area not found!');
            return;
        }

        $this->command->info('Seeding seats for Sehan Ground Floor (Gents)...');

        // Clear existing seats for this area
        Seat::where('area_id', $area->id)->delete();

        $seats = [];

        // Column labels for A-Z, AA-AZ, BA-BC (up to column 55)
        $columnLabels = [];
        for ($i = 0; $i < 26; $i++) {
            $columnLabels[] = chr(65 + $i); // A-Z
        }
        for ($i = 0; $i < 26; $i++) {
            $columnLabels[] = 'A' . chr(65 + $i); // AA-AZ
        }
        for ($i = 0; $i < 3; $i++) {
            $columnLabels[] = 'B' . chr(65 + $i); // BA-BC (up to column 55)
        }

        // Uniform layout: 20 rows × 55 columns = 1100 seats
        // Seat numbers: 1146 to 2245
        $seatNumber = 1146;

        for ($row = 1; $row <= 20; $row++) {
            for ($col = 1; $col <= 55; $col++) {
                $seats[] = [
                    'area_id' => $area->id,
                    'seat_number' => $seatNumber,
                    'row_number' => $row,
                    'column_number' => $col,
                    'column_label' => $columnLabels[$col - 1],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $seatNumber++;
            }
        }

        // Insert all seats
        Seat::insert($seats);

        $this->command->info("Created " . count($seats) . " seats for Sehan Ground Floor (Gents)");
        $this->command->info("Seat range: " . min(array_column($seats, 'seat_number')) . " to " . max(array_column($seats, 'seat_number')));
        $this->command->info("Grid: 20 rows × 55 columns");
        $this->command->info("Layout: Uniform chart with no gaps");
        $this->command->info("Row 1: 1146-1200, Row 20: 2191-2245");
    }
}
