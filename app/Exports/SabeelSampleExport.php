<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ToArray;

class SabeelSampleExport implements ToArray
{
    public function array(): array
    {
        return [
            [
                'sabeel_code' => '001',
                'sabeel_address' => 'Sample Address 1, Saifee Nagar',
                'sabeel_sector' => 'ezzi',
                'sabeel_hof' => '12345678',
                'sabeel_type' => 'regular'
            ],
            [
                'sabeel_code' => '002',
                'sabeel_address' => 'Sample Address 2, Saifee Nagar',
                'sabeel_sector' => 'fakhri',
                'sabeel_hof' => '87654321',
                'sabeel_type' => 'student'
            ]
        ];
    }
}
