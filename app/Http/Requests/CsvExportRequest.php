<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Services\Csv\InputDto;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string[] $header
 * @property string[] $data
 *
 * Class CsvExportRequest
 *
 * @package App\Http\Requests
 */
class CsvExportRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'header' => ['required', 'array'],
            'header.*' => ['string', 'nullable'],

            'data' => ['required', 'array'],
            'data.*' => ['array'],
            'data.*.*' => ['string', 'nullable'],
        ];
    }

    public function getDto(): InputDto
    {
        return InputDto::create($this->header, $this->data);
    }
}
