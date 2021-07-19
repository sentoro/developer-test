<?php
declare(strict_types=1);

namespace App\Services\Csv\Exceptions;

use Exception;

final class InvalidRowException extends Exception
{
    /**
     * @var int
     */
    private $rowIndex;

    public function __construct(string $message, int $code, int $rowIndex)
    {
        parent::__construct($message, $code);

        $this->rowIndex = $rowIndex;
    }

    public function getRowIndex(): int
    {
        return $this->rowIndex;
    }
}
