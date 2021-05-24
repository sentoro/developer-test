<?php


namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;

class ExportToCSV implements ShouldAutoSize, WithEvents, FromView
{
    /**
     * @var
     */
    private $data;

    /**
     * ExportToCSV constructor.
     *
     * @param $data
     */
    function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->data;

        return view('csv')->with(compact('data'));
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [];
    }

}
