<?php
declare(strict_types=1);

namespace App\Services\Csv\Exceptions;

use Exception;

final class InvalidColumnException extends Exception
{
    /**
     * @var int
     */
    private $rowIndex;

    /**
     * @var int
     */
    private $columnIndex;

    public function __construct(string $message, int $code, int $rowIndex, int $columnIndex)
    {
        parent::__construct($message, $code);

        $this->rowIndex = $rowIndex;
        $this->columnIndex = $columnIndex;
    }

    public function getRowIndex(): int
    {
        return $this->rowIndex;
    }

    public function getColumnIndex(): int
    {
        return $this->columnIndex;
    }
}
