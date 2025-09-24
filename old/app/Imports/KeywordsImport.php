<?php

namespace App\Imports;

use App\Models\Keyword;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KeywordsImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        return new Keyword([
            'name'  => $row['name'],
            'code'  => $row['code']
        ]);
    }
}

