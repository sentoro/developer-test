<?php

namespace Tests\Unit;

use Tests\TestCase;

class CsvExportTest extends TestCase
{
    /**
     * @Test Test for exporting csv file.
     **/
    public function testCsv(){
        $data = [['key'=>'value','key'=>'value'],['key'=>'value','key'=>'value']];
        $response = $this->patch( '/api/csv-export',$data);
        $this->assertTrue(strpos($response->content(), 'csv') !== false);
        $response->assertStatus(200);
    }

    /**
     * @Test Test for checking wrong data case.
     **/
    public function testCsvWhenDataIsWrong(){
        $data = [[false],[1],[''],[null],['sdf',54,false],['key'=>'value']];
        $key = array_rand($data);
        $response = $this->patch( '/api/csv-export',$data[$key]);
        $this->assertTrue(strpos($response->content(), 'Invalid argument supplied for foreach()') !== false);
        $response->assertStatus(500);
    }

    /**
     * @Test Test for checking no data case.
     **/
    public function testCsvWhenNotData(){
        $response = $this->patch( '/api/csv-export');
        $this->assertTrue(strpos($response->content(), 'Your data empty') !== false);
        $response->assertStatus(400);
    }
}
