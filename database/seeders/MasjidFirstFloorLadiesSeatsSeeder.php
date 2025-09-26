<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Seat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MasjidFirstFloorLadiesSeatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $area = Area::where('slug', 'masjid-first-floor-ladies')->first();

        if (!$area) {
            $this->command->error('Masjid First Floor (Ladies) area not found. Please run AreasSeeder first.');
            return;
        }

        $this->command->info('Seeding seats for Masjid First Floor (Ladies)...');

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

        // Row 1: Full row (1-55)
        for ($col = 1; $col <= 55; $col++) {
            $seats[] = [
                'area_id' => $area->id,
                'seat_number' => $col,
                'row_number' => 1,
                'column_number' => $col,
                'column_label' => $columnLabels[$col - 1],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Row 2: Full row (56-110)
        for ($col = 1; $col <= 55; $col++) {
            $seats[] = [
                'area_id' => $area->id,
                'seat_number' => 55 + $col,
                'row_number' => 2,
                'column_number' => $col,
                'column_label' => $columnLabels[$col - 1],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Rows 3-14: Left block (A-K) + Right block (AX-BC)
        $leftSeatNumber = 111;
        $rightSeatNumber = 573; // Starting from row 3 right block

        for ($row = 3; $row <= 14; $row++) {
            // Left block: Columns A-K (1-11)
            for ($col = 1; $col <= 11; $col++) {
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

            // Right block: Columns AS-BC (45-55) - 11 seats per row
            for ($col = 45; $col <= 55; $col++) {
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

        // Rows 15-20: Full rows (243-572)
        $fullSeatNumber = 243;
        for ($row = 15; $row <= 20; $row++) {
            for ($col = 1; $col <= 55; $col++) {
                $seats[] = [
                    'area_id' => $area->id,
                    'seat_number' => $fullSeatNumber,
                    'row_number' => $row,
                    'column_number' => $col,
                    'column_label' => $columnLabels[$col - 1],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $fullSeatNumber++;
            }
        }

        $chunks = array_chunk($seats, 500);
        foreach ($chunks as $chunk) {
            Seat::insert($chunk);
        }

        $this->command->info("Created 704 seats for Masjid First Floor (Ladies)");
        $this->command->info("Seat range: 1 to 704");
        $this->command->info("Grid: 20 rows × 55 columns");
        $this->command->info("Layout: Rows 1-2 & 15-20 full, Rows 3-14 left+right blocks");
        $this->command->info("Left block: 111-242, Right block: 578-704, Full rows: 243-572");
    }
}
