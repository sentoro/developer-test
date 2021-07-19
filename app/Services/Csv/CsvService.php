<?php
declare(strict_types=1);

namespace App\Services\Csv;

final class CsvService
{
    /**
     * @var InputValidator
     */
    private $validator;

    public function __construct(InputValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @throws Exceptions\InvalidColumnException
     * @throws Exceptions\InvalidRowException
     */
    public function create(InputDto $inputDto): string
    {
        $this->validator->validate($inputDto);

        $data = $inputDto->getData();
        array_unshift($data, $inputDto->getHeader());

        return $this->arrayToCsv($data);
    }

    private function arrayToCsv(array $data): string
    {
        $f = fopen('php://memory', 'r+');
        foreach ($data as $item) {
            fputcsv($f, $item);
        }
        rewind($f);

        return stream_get_contents($f);
    }
}
