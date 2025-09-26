<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Seat;

class MasjidSecondFloorLadiesSeatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $area = Area::where('slug', 'masjid-second-floor-ladies')->first();
        
        if (!$area) {
            $this->command->error('Masjid Second Floor (Ladies) area not found!');
            return;
        }

        $this->command->info('Seeding seats for Masjid Second Floor (Ladies)...');

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

        // Row 1: Full row (1001-1055)
        for ($col = 1; $col <= 55; $col++) {
            $seats[] = [
                'area_id' => $area->id,
                'seat_number' => 1000 + $col,
                'row_number' => 1,
                'column_number' => $col,
                'column_label' => $columnLabels[$col - 1],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Row 2: Full row (1056-1110)
        for ($col = 1; $col <= 55; $col++) {
            $seats[] = [
                'area_id' => $area->id,
                'seat_number' => 1000 + 55 + $col,
                'row_number' => 2,
                'column_number' => $col,
                'column_label' => $columnLabels[$col - 1],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Rows 3-14: Left block (A-K) + Right block (AS-BC)
        $leftSeatNumber = 1000 + 111; // Starting from 1111
        $rightSeatNumber = 1000 + 573; // Starting from 1573

        for ($row = 3; $row <= 14; $row++) {
            // Left block: Columns A-K (1-11) - 11 seats per row
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

        // Rows 15-20: Full rows (1243-1572)
        $fullRowSeatNumber = 1000 + 243; // Starting from 1243
        for ($row = 15; $row <= 20; $row++) {
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

        $this->command->info("Created " . count($seats) . " seats for Masjid Second Floor (Ladies)");
        $this->command->info("Seat range: " . min(array_column($seats, 'seat_number')) . " to " . max(array_column($seats, 'seat_number')));
        $this->command->info("Grid: 20 rows × 55 columns");
        $this->command->info("Layout: Rows 1-2 & 15-20 full, Rows 3-14 left+right blocks");
        $this->command->info("Left block: " . (1000 + 111) . "-" . (1000 + 242) . ", Right block: " . (1000 + 573) . "-" . (1000 + 704) . ", Full rows: " . (1000 + 243) . "-" . (1000 + 572));
    }
}
