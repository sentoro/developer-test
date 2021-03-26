<?php

namespace Tests\Unit\ControllerTests\Mocks;

use App\Services\CsvBuilderService;
use Mockery;

trait CsvBuilderServiceMocker
{
    protected function mockCsvBuilderService(array $methodsMock = []): Mockery\MockInterface
    {
        $service = Mockery::mock(CsvBuilderService::class, $methodsMock);
        $this->instance(CsvBuilderService::class, $service);

        return $service;
    }
}
