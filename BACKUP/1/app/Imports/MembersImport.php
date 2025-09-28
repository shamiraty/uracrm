<?php

namespace App\Imports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MembersImport implements ToModel, WithHeadingRow
{
    /**
     * Transform each row into a Member model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Member([
            'check_number' => $row['check_number'], // Adjust if necessary based on your Excel/CSV column headers
            'first_name'   => $row['first_name'],
            'middle_name'  => $row['middle_name'],
            'last_name'    => $row['last_name'],
            'member_number'=> $row['member_number'],
        ]);
    }
}
