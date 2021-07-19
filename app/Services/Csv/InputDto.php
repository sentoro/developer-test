<?php
declare(strict_types=1);

namespace App\Services\Csv;

final class InputDto
{
    /**
     * @var array
     */
    private $header;

    /**
     * @var array
     */
    private $data;

    private function __construct(array $header, array $data)
    {
        $this->header = $header;
        $this->data = $data;
    }

    public function getHeader(): array
    {
        return $this->header;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public static function create(array $header, array $data): self
    {
        return new self($header, $data);
    }
}
