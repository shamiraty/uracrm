<?php

namespace App\Imports;

use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class PayrollImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
     * Define how each row will be transformed into a Payroll model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Payroll([
            'check_number'   => $row['checknumber'],
            'full_name'      => $row['fullname'],
            'account_number' => $row['accountnumber'],
            'bank_name'      => $row['bankname'],
            'basic_salary'   => $row['basicsalary'],
            'allowance'      => $row['allowance'],
            'gross_amount'   => $row['grossamount'],
            'net_amount'     => $row['netamount'],
        ]);
    }
}

