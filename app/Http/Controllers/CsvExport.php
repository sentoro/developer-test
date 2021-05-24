<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\ExportToCSV;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class CsvExport extends Controller
{
    /**
     * Converts the user input into a CSV file and streams the file back to the user
     */
    public function convert(Request $request)
    {
        $fileName = Str::random(10) . ".csv";
        $data = $request->all();
        $file = Excel::store(new ExportToCSV($data), $fileName, 'public', 'Csv');
        if ($file) {
            $filepath = config('app.url') . '/storage/' . $fileName;

            return response(['success' => true, 'url' => $filepath], 200);
        }
    }
}
