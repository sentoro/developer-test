<?php

namespace App\Services\DataConvertor;

/**
 * Data convertor contract for table information
 */
interface DataConvertorInterface
{
    public function setData(array $data, array $columns);

    public function convertToFormat(string $fileName) : array;
}
