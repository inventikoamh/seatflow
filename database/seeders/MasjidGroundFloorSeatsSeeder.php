<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Seat;
use Illuminate\Database\Seeder;

class MasjidGroundFloorSeatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the Masjid Ground Floor (Gents) area
        $area = Area::where('slug', 'masjid-ground-floor-gents')->first();
        
        if (!$area) {
            $this->command->error('Masjid Ground Floor (Gents) area not found. Please run AreasSeeder first.');
            return;
        }

        // Clear existing seats for this area
        Seat::where('area_id', $area->id)->delete();

        // Generate column labels (A, B, C, ..., BC)
        $columnLabels = [];
        for ($i = 0; $i < 55; $i++) {
            if ($i < 26) {
                $columnLabels[] = chr(65 + $i); // A-Z
            } else {
                $columnLabels[] = 'A' . chr(65 + ($i - 26)); // AA-AZ
                if ($i >= 52) {
                    $columnLabels[$i] = 'B' . chr(65 + ($i - 52)); // BA-BC
                }
            }
        }

        $seatNumber = 1;
        $seats = [];

        // Create seats based on the JSON structure
        for ($row = 1; $row <= 21; $row++) {
            $seatsInRow = ($row <= 2) ? 50 : 55; // First 2 rows have 50 seats, rest have 55
            
            for ($col = 1; $col <= $seatsInRow; $col++) {
                $columnLabel = $columnLabels[$col - 1];
                
                $seats[] = [
                    'area_id' => $area->id,
                    'seat_number' => $seatNumber,
                    'row_number' => $row,
                    'column_number' => $col,
                    'column_label' => $columnLabel,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $seatNumber++;
            }
        }

        // Insert seats in batches for better performance
        $chunks = array_chunk($seats, 500);
        foreach ($chunks as $chunk) {
            Seat::insert($chunk);
        }

        $totalSeats = $seatNumber - 1;
        $this->command->info("Created {$totalSeats} seats for Masjid Ground Floor (Gents)");
        $this->command->info("Seat range: 1 to {$totalSeats}");
        $this->command->info("Grid: 21 rows × 55 columns (first 2 rows have 50 seats)");
    }
}