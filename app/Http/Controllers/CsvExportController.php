<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CsvExportRequest;
use App\Services\Csv\CsvService;
use App\Services\Csv\Exceptions\InvalidColumnException;
use App\Services\Csv\Exceptions\InvalidRowException;

class CsvExportController extends Controller
{
    /**
     * @var CsvService
     */
    private $service;

    public function __construct(CsvService $service)
    {
        $this->service = $service;
    }

    /**
     * Converts the user input into a CSV file and streams the file back to the user.
     */
    public function convert(CsvExportRequest $request)
    {
        $inputDto = $request->getDto();

        try {
            $csv = $this->service->create($inputDto);

            return response()->streamDownload(function () use ($csv) {
                echo $csv;
            }, 'data.csv');
        } catch (InvalidColumnException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'row' => $e->getRowIndex(),
                'column' => $e->getColumnIndex(),
            ], 400);
        } catch (InvalidRowException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'row' => $e->getRowIndex(),
            ], 400);
        }
    }
}
