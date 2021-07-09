<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\GenerateCSVRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExport extends Controller
{
    /**
     * Converts the user input into a CSV file and streams the file back to the user
     */
    public function convert(GenerateCSVRequest $request): StreamedResponse
    {
        $tableItems = $request->get('tableItems');

        $filename = 'items.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = array_keys($tableItems[0]);

        $callback = function () use ($tableItems, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tableItems as $item) {
                fputcsv($file, $item);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
