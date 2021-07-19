<?php
declare(strict_types=1);

namespace App\Services\Csv;

use App\Services\Csv\Exceptions\InvalidColumnException;
use App\Services\Csv\Exceptions\InvalidRowException;

final class InputValidator
{
    private const ROW_COLUMNS_COUNT_ERROR_CODE = 1000;
    private const COLUMN_LENGTH_ERROR_CODE = 2000;

    /**
     * @var int
     */
    private $columnMaxLength;

    public function __construct(int $columnMaxLength = 255)
    {
        $this->columnMaxLength = $columnMaxLength;
    }

    /**
     * @throws InvalidColumnException
     * @throws InvalidRowException
     */
    public function validate(InputDto $input): void
    {
        $this->validateRowsColumnsCount($input);
        $this->validateColumnsLength($input);
    }

    /**
     * @throws InvalidRowException
     */
    private function validateRowsColumnsCount(InputDto $input): void
    {
        $headerLen = count($input->getHeader());

        $longerRow = collect($input->getData())->search(function (array $data) use ($headerLen) {
            return count($data) > $headerLen;
        });

        if ($longerRow !== false) {
            throw new InvalidRowException(
                'Invalid row columns count, row must be smaller or equal to header',
                self::ROW_COLUMNS_COUNT_ERROR_CODE,
                $longerRow
            );
        }
    }

    /**
     * @throws InvalidColumnException
     */
    private function validateColumnsLength(InputDto $input): void
    {
        $data = $input->getData();
        array_unshift($data, $input->getHeader());
        $data = array_values($data);

        foreach ($data as $rowIndex => $row) {
            $invalidLenColumnIndex = collect($row)->search(function ($column) {
                return $this->columnLength($column) > $this->columnMaxLength;
            });

            if ($invalidLenColumnIndex !== false) {
                $message = sprintf(
                    'Invalid column %d:%d length, current length (%d) more than acceptable (%d)',
                    $rowIndex,
                    $invalidLenColumnIndex,
                    $this->columnLength($row[$invalidLenColumnIndex]),
                    $this->columnMaxLength
                );

                throw new InvalidColumnException($message, self::COLUMN_LENGTH_ERROR_CODE, $rowIndex, $invalidLenColumnIndex);
            }
        }
    }

    private function columnLength($column): int
    {
        return strlen((string) $column);
    }
}
