<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Seat;

class TayyebiCenterGentsSeatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $area = Area::where('slug', 'tayyebi-center-gents')->first();
        
        if (!$area) {
            $this->command->error('Tayyebi Center (Gents) area not found!');
            return;
        }

        $this->command->info('Seeding seats for Tayyebi Center (Gents)...');

        // Clear existing seats for this area
        Seat::where('area_id', $area->id)->delete();

        $seats = [];

        // Column labels for A-T (up to column 20)
        $columnLabels = [];
        for ($i = 0; $i < 20; $i++) {
            $columnLabels[] = chr(65 + $i); // A-T
        }

        // Uniform layout: 23 rows × 20 columns = 460 seats
        // Seat numbers: 5001 to 5460
        $seatNumber = 5001;

        for ($row = 1; $row <= 23; $row++) {
            for ($col = 1; $col <= 20; $col++) {
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

        $this->command->info("Created " . count($seats) . " seats for Tayyebi Center (Gents)");
        $this->command->info("Seat range: " . min(array_column($seats, 'seat_number')) . " to " . max(array_column($seats, 'seat_number')));
        $this->command->info("Grid: 23 rows × 20 columns");
        $this->command->info("Layout: Uniform chart with no gaps");
        $this->command->info("Row 1: 5001-5020, Row 23: 5441-5460");
    }
}
