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
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function convert(Request $request)
    {
        try {
            $fileName = Str::random(10) . ".csv";
            $data = $request->all();
            if (!count($data)) {
                return response(['success' => false, 'message' => 'Your data empty'], 400);
            }
            Excel::store(new ExportToCSV($data), $fileName, 'public', 'Csv');
            $filepath = config('app.url') . '/storage/' . $fileName;
            return response(['success' => true, 'url' => $filepath], 200);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
