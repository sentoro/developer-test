<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\DataConvertor\DataConvertorInterface;
use Validator;
use Illuminate\Http\Request;

class CsvExport extends Controller {

    private $dataConvertor = '';

    public function __construct(DataConvertorInterface $dataConvertor)
    {
        $this->dataConvertor = $dataConvertor;
    }

    /**
     * Converts the user input into a CSV file and streams the file back to the user
     */
    public function convert(Request $request)
    {

        $requestData = $request->all();

        $validator = Validator::make($requestData,[
                        'columns' => 'required',
                        'data' => 'required',
                    ]);

        if($validator->fails())
        {
            return response([
                    'error' => $validator->errors()
                ], 400);
        }

        try {
            $columns = $requestData['columns'];

            $data    = $requestData['data'];

            $this->dataConvertor->setData($data, $columns);

            $response = $this->dataConvertor->convertToFormat('newfile.csv');

            return response()
                 ->stream($response['callback'], 200, $response['headers']);
        } catch (\Exception $e) {
            return response([
                    'error' => $e->getMessage()
                ], 400);
        }

    }
}
