<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CsvBuilderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CsvExportController extends Controller
{
    private $builderService;

    public function __construct(CsvBuilderService $builderService)
    {
        $this->builderService = $builderService;
    }

    /**
     * Converts the user input into a CSV file and streams the file back to the user
     */
    public function index(Request $request): Response
    {
        $content = $this->builderService->buildAttributes($request->get('columns'), $request->get('data'));
        $response = new Response($this->builderService::FILENAME_ATTR, Response::HTTP_OK);

        return $response;
    }
}
