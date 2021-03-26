<?php


namespace Tests\Unit\ControllerTests;

use App\Http\Controllers\CsvExportController;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\ControllerTests\Mocks\CsvBuilderServiceMocker;
use Tests\Unit\ControllerTests\Mocks\RequestMocker;

class CsvExportControllerTest extends TestCase
{
    use CsvBuilderServiceMocker;
    use RequestMocker;

    private $mockData = [
        "data" => [
            [
                "firstName" => "Jonh",
                "lastName" => "Doe",
                "emailAddress" => "john.doe@example.com",
            ],
            [
                "firstName" => "Jonh",
                "lastName" => "Doe",
                "emailAddress" => "john.doe@example.com",
            ],
        ],
        "columns" => [
            ["key" => "first name"],
            ["key" => "last name"],
            ["key" => "email address"],
        ]
    ];

    /**
     * Class index method success test.
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     *
     * @test
     * @return void
     */
    public function index__MethodSuccess()
    {
        $this->mockCsvBuilderService(['buildAttributes' => ""]);
        $request = $this->mockRequest(json_encode($this->mockData));
        $result = app()->make(CsvExportController::class)->index($request);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertTrue(strpos($result, 'csv') !== false);
    }

    /**
     * Class index method fail test.
     *
     * @test
     * @return void
     */
    public function index__MethodFail()
    {
        $this->mockCsvBuilderService(['buildAttributes' => null]);
        $request = $this->mockRequest(json_encode(["columns" => [], "data" => []]));
        $result = app()->make(CsvExportController::class)->index($request);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertTrue($result->getContent() == null);
    }
}
