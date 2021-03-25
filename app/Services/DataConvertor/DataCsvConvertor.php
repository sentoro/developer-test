<?php

namespace App\Services\DataConvertor;

use App\Services\DataConvertor\DataConvertorInterface;

/**
 * Data convertor from table information to csv format
 */
class DataCsvConvertor implements DataConvertorInterface
{
    private $dataForConvert = [];

    public function setData(array $data, array $columns)
    {
        $this->dataForConvert = ['data' => $data, 'columns' => $columns];
    }

    public function convertToFormat(string $fileName) : array
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $fileName,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, $this->getColumns());
            foreach ($this->getData() as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return [
            'headers' => $headers,
            'callback' => $callback
        ];
    }

    private function getData() : array
    {
        return $this->dataForConvert['data'];
    }

    private function getColumns() : array
    {
        return $this->dataForConvert['columns'];
    }
}
