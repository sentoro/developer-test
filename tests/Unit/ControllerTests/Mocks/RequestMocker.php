<?php

namespace Tests\Unit\ControllerTests\Mocks;

use Symfony\Component\HttpFoundation\Request;
use Illuminate\Http\Request as IlluminateRequest;

trait RequestMocker
{
    protected function mockRequest(string $data): Request
    {
        $request = new IlluminateRequest();

        return $request->createFromBase(
            Request::create(
                route('csv.export'),
                'PATCH',
                [],
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                $data
            )
        );
    }
}
