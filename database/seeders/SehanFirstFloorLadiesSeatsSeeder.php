<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Seat;

class SehanFirstFloorLadiesSeatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $area = Area::where('slug', 'sehan-first-floor-ladies')->first();
        
        if (!$area) {
            $this->command->error('Sehan First Floor (Ladies) area not found!');
            return;
        }

        $this->command->info('Seeding seats for Sehan First Floor (Ladies)...');

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

        // Rows 1-16: Left block (A-H) + Right block (AV-BC) = 16 seats per row
        $leftSeatNumber = 2001; // Starting from 2001
        $rightSeatNumber = 2129; // Starting from 2129

        for ($row = 1; $row <= 16; $row++) {
            // Left block: Columns A-H (1-8) - 8 seats per row
            for ($col = 1; $col <= 8; $col++) {
                $seats[] = [
                    'area_id' => $area->id,
                    'seat_number' => $leftSeatNumber,
                    'row_number' => $row,
                    'column_number' => $col,
                    'column_label' => $columnLabels[$col - 1],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $leftSeatNumber++;
            }

            // Right block: Columns AV-BC (48-55) - 8 seats per row
            for ($col = 48; $col <= 55; $col++) {
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

        // Rows 17-20: Full rows (A-BC) - 55 seats each
        $fullRowSeatNumber = 2257; // Starting from 2257
        for ($row = 17; $row <= 20; $row++) {
            for ($col = 1; $col <= 55; $col++) {
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

        $this->command->info("Created " . count($seats) . " seats for Sehan First Floor (Ladies)");
        $this->command->info("Seat range: " . min(array_column($seats, 'seat_number')) . " to " . max(array_column($seats, 'seat_number')));
        $this->command->info("Grid: 20 rows × 55 columns");
        $this->command->info("Layout: Rows 1-16 left+right blocks (16 seats each), Rows 17-20 full (55 seats each)");
        $this->command->info("Left block: 2001-2128, Right block: 2129-2256, Full rows: 2257-2476");
    }
}
