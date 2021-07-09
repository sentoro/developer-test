<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use League\Csv\Reader;
use Tests\TestCase;

/**
 * Class CSVExportTest
 * @package Tests\Feature
 */
class CSVExportTest extends TestCase
{
    /**
     * @throws \League\Csv\Exception
     */
    public function testCsvExport(): void
    {
        $tableItems = [
            [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'email' => 'test@email.com'
            ],
            [
                'firstName' => 'Barak',
                'lastName' => 'Obama',
                'email' => 'test.email@google.com'
            ]
        ];

        $response = $this->postJson('/api/csv-export', ['tableItems' => $tableItems]);

        $response->assertHeader('Content-Disposition', 'attachment; filename=items.csv');
        $reader = Reader::createFromString($response->streamedContent());
        $reader->setHeaderOffset(0);

        $this->assertCount(count($tableItems), $reader);
    }
}
