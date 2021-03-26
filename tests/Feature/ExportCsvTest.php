<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportCsvTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRouteExists()
    {
        $response = $this->post('/api/csv-export');

        $this->assertIsObject($response);
    }

    /**
     *
     * @return void
     */
    public function testRouteWithFullData()
    {
        $columns = ['first_name', 'last_name'];

        $data = [['john', 'doe'], ['john', 'doe']];

        $request_data = [
            'columns' => $columns,
            'data'    => $data
        ];

        $response = $this->post('/api/csv-export', $request_data);

        $this->assertEquals($response->getStatusCode(), 200);
    }

    /**
     *
     * @return void
     */
    public function testRouteValidation()
    {
        $columns = ['first_name', 'last_name'];

        $data = [['john', 'doe'], ['john', 'doe']];

        $request_data = [
            'columns' => $columns
        ];

        $response = $this->post('/api/csv-export', $request_data);

        $this->assertEquals($response->status(), 400);

        $content = $response->content();

        $this->assertStringContainsString('error', $content);
    }
}
