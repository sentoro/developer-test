<?php
declare(strict_types=1);

namespace Tests\Unit;

use App\Services\Csv\CsvService;
use App\Services\Csv\InputDto;
use App\Services\Csv\InputValidator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class CsvTest extends TestCase
{
    public function testCsvCreating(): void
    {
        $input = InputDto::create(
            [
                'first_name',
                'last_name',
                'age',
            ],
            [
                [
                    'Marty',
                    'Mcfly',
                    '17',
                ],
            ]
        );

        $csv = $this->service()->create($input);

        self::assertEquals(
            "first_name,last_name,age\nMarty,Mcfly,17\n",
            $csv
        );
    }

    private function service(): CsvService
    {
        $validator = new InputValidator(255);

        return new CsvService($validator);
    }
}
