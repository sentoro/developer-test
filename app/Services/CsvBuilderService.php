<?php

namespace App\Services;

class CsvBuilderService
{
    public const FILENAME_ATTR = "temporary_attr.csv";

    /**
     * @param array $columns
     * @param array $data
     * @return string|null
     */
    public function buildAttributes(array $columns, array $data): ?string
    {
        $i = 1;
        $list = [$i => $this->prepareColumns($columns)];

        foreach ($data as $item) {
            $i++;
            $list[$i] = $item;
        }

        $outputBuffer = fopen(self::FILENAME_ATTR, 'w');

        foreach($list as $item) {
            fputcsv($outputBuffer, $item, ",");
        }
        fclose($outputBuffer);

        $content = file_get_contents(self::FILENAME_ATTR);

        return $content ?? null;
    }

    private function prepareColumns(array $columns)
    {
        $result = [];
        foreach ($columns as $column) {
            $result[] = $column["key"];
        }

        return $result;
    }
}
