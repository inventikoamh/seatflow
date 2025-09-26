<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Seat;

class TayyebiRightLadiesOnChairSeatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $area = Area::where('slug', 'tayyebi-right-ladies-chair')->first();
        
        if (!$area) {
            $this->command->error('Tayyebi Right (Ladies on Chair) area not found!');
            return;
        }

        $this->command->info('Seeding seats for Tayyebi Right (Ladies on Chair)...');

        // Clear existing seats for this area
        Seat::where('area_id', $area->id)->delete();

        $seats = [];

        // Column labels for A-Z, AA-AL (up to column 38)
        $columnLabels = [];
        for ($i = 0; $i < 26; $i++) {
            $columnLabels[] = chr(65 + $i); // A-Z
        }
        for ($i = 0; $i < 12; $i++) {
            $columnLabels[] = 'A' . chr(65 + $i); // AA-AL (up to column 38)
        }

        // Rows 1-23: Right block only (columns 31-38, 8 seats per row)
        $rightSeatNumber = 5461; // Starting from 5461
        for ($row = 1; $row <= 23; $row++) {
            for ($col = 31; $col <= 38; $col++) {
                $seats[] = [
                    'area_id' => $area->id,
                    'seat_number' => $rightSeatNumber,
                    'row_number' => $row,
                    'column_number' => $col,
                    'column_label' => $columnLabels[$col - 1],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $rightSeatNumber++;
            }
        }

        // Rows 24-26: Middle block (columns 10-29, 20 seats) + Right block (columns 31-38, 8 seats)
        $middleSeatNumber = 5461 + (23 * 8); // Starting from 5645
        $rightSeatNumber = 5705; // Starting from 5705
        
        for ($row = 24; $row <= 26; $row++) {
            // Middle block: Columns 10-29 (20 seats)
            for ($col = 10; $col <= 29; $col++) {
                $seats[] = [
                    'area_id' => $area->id,
                    'seat_number' => $middleSeatNumber,
                    'row_number' => $row,
                    'column_number' => $col,
                    'column_label' => $columnLabels[$col - 1],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $middleSeatNumber++;
            }
            
            // Right block: Columns 31-38 (8 seats)
            for ($col = 31; $col <= 38; $col++) {
                $seats[] = [
                    'area_id' => $area->id,
                    'seat_number' => $rightSeatNumber,
                    'row_number' => $row,
                    'column_number' => $col,
                    'column_label' => $columnLabels[$col - 1],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $rightSeatNumber++;
            }
        }

        // Rows 28-33: Full rows (columns 1-38, 38 seats each)
        // Row 27 is blank (no seats)
        $fullRowSeatNumber = 5729; // Starting from 5729
        for ($row = 28; $row <= 33; $row++) {
            for ($col = 1; $col <= 38; $col++) {
                $seats[] = [
                    'area_id' => $area->id,
                    'seat_number' => $fullRowSeatNumber,
                    'row_number' => $row,
                    'column_number' => $col,
                    'column_label' => $columnLabels[$col - 1],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $fullRowSeatNumber++;
            }
        }

        // Insert all seats
        Seat::insert($seats);

        $this->command->info("Created " . count($seats) . " seats for Tayyebi Right (Ladies on Chair)");
        $this->command->info("Seat range: " . min(array_column($seats, 'seat_number')) . " to " . max(array_column($seats, 'seat_number')));
        $this->command->info("Grid: 33 rows × 38 columns");
        $this->command->info("Layout: Rows 1-23 right block only, Rows 24-26 middle+right blocks, Row 27 blank, Rows 28-33 full");
        $this->command->info("Right block (1-23): 5461-5644, Middle+Right (24-26): 5645-5728, Full rows (28-33): 5729-5956");
    }
}
