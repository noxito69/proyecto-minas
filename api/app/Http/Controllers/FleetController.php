<?php

namespace App\Http\Controllers;
use App\Models\Fleet;

use Illuminate\Http\Request;

class FleetController extends Controller
{

        public function store(Request $request)
    {
        $file = $request->file('csv_file');
        $csvData = array_map(function($line) {
            return str_getcsv($line, ';');
        }, file($file));

        array_shift($csvData); // Remove the header row

        foreach ($csvData as $row) {
            Fleet::create([
                'category' => $row[0] ?? null,
                'class' => $row[1] ?? null,
                'equipment_no' => $row[2] ?? null,
                'programmed_and_non_programmed_stop' => isset($row[3]) ? (float) $row[3] : null,
                'availability' => $row[4] ?? null,
                'operating_time' => isset($row[5]) ? (float) $row[5] : null,
                'availability_use' => $row[6] ?? null,
                'stand_by' => isset($row[7]) ? (float) $row[7] : null,
                'tonnage' => isset($row[8]) ? (float) str_replace(',', '', $row[8]) : null,
                'ton_per_hour' => isset($row[9]) ? (int) str_replace(',', '', $row[9]) : null,
            ]);
        }

        return response()->json(['message' => 'CSV data imported successfully'], 200);
    }


}
