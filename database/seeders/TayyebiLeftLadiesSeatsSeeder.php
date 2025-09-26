<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Seat;

class TayyebiLeftLadiesSeatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $area = Area::where('slug', 'tayyebi-left-ladies')->first();
        
        if (!$area) {
            $this->command->error('Tayyebi Left (Ladies) area not found!');
            return;
        }

        $this->command->info('Seeding seats for Tayyebi Left (Ladies)...');

        // Clear existing seats for this area
        Seat::where('area_id', $area->id)->delete();

        $seats = [];

        // Column labels for A-J (up to column 10)
        $columnLabels = [];
        for ($i = 0; $i < 10; $i++) {
            $columnLabels[] = chr(65 + $i); // A-J
        }

        // Uniform layout: 26 rows × 10 columns = 260 seats
        // Seat numbers: 5957 to 6216
        $seatNumber = 5957;

        for ($row = 1; $row <= 26; $row++) {
            for ($col = 1; $col <= 10; $col++) {
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

        $this->command->info("Created " . count($seats) . " seats for Tayyebi Left (Ladies)");
        $this->command->info("Seat range: " . min(array_column($seats, 'seat_number')) . " to " . max(array_column($seats, 'seat_number')));
        $this->command->info("Grid: 26 rows × 10 columns");
        $this->command->info("Layout: Uniform chart with no gaps");
        $this->command->info("Row 1: 5957-5966, Row 26: 6207-6216");
    }
}
