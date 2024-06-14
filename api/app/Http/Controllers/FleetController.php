<?php

namespace App\Http\Controllers;
use App\Models\Fleet;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FleetController extends Controller
{

    public function index()
    {
        $fleets = Fleet::all();
        return response()->json($fleets);
    }

    public function indexShovel()
    {
        $shovelClasses = Fleet::select('category', 'class', 'equipment_no', 'operating_time')->where('category', 'Shovel Classes')->get();

        return response()->json($shovelClasses);
    }

    public function store(Request $request)
    {
        $lastCategory=null;
        Fleet::truncate();

        $file = $request->file('csv_file');
        $csvData = array_map(function($line) {
            return str_getcsv($line, ';');
        }, file($file));

        // Eliminar filas en blanco y encabezados no deseados
        $csvData = array_filter($csvData, function($row) {
            return array_filter($row);
        });

        // Eliminar encabezados adicionales si los hay
        if (isset($csvData[0]) && count($csvData[0]) < 3) {
            array_shift($csvData);
        }

        foreach ($csvData as $row) {
            // Saltar las filas con encabezados repetidos
            // if ($row[0] === 'Categoría' || $row[0] === 'Categoria') {
            //     continue;
            // }

            // Eliminar filas donde la columna 'category' esté vacía o tenga el valor 'Subtotal'
            if ($row[1] == 'Subtotal' || $row[0] === 'Categoría' || $row[0] == 'Horas de Flotas' || $row[0] == 'Disponibilidad= (Horas por turno / Horas disponible) Uso de la disponibilidad= (Horas disponible/Tiempo operativo)' || empty($row[1])) {
                continue;
            }

            // Si la categoría actual está vacía, usar la última categoría válida
            if (empty($row[0])) {
                $row[0] = $lastCategory;
            } else {
                $lastCategory = $row[0]; // Actualizar la última categoría válida
            }

            // Procesar los datos y sanitizarlos
            $data = [
                'category' => $lastCategory ?? null,
                'class' => $row[1] ?? null,
                'equipment_no' => $row[2] ?? null,
                'programmed_and_non_programmed_stop' => isset($row[3]) ? (float) $row[3] : null,
                'availability' => isset($row[4]) ? floatval(rtrim($row[4], ' %')) / 100 : null,
                'operating_time' => isset($row[5]) ? (float) $row[5] : null,
                'availability_use' => isset($row[6]) ? floatval(rtrim($row[6], ' %')) / 100 : null,
                'stand_by' => isset($row[7]) ? (float) $row[7] : null,
                'tonnage' => isset($row[8]) ? (float) str_replace(',', '', $row[8]) : null,
                'ton_per_hour' => isset($row[9]) ? (int) str_replace(',', '', $row[9]) : null,
            ];

            // Filtrar valores nulos y no crear registros vacíos
            Log::info('datos', $data);
            if (!array_filter($data)) {
                continue;
            }

            Fleet::create($data);
        }

        return response()->json(['message' => 'CSV data imported successfully'], 200);
    }

}
