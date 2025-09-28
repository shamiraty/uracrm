<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'check_number',
        'full_name',
        'account_number',
        'bank_name',
        'basic_salary',
        'allowance',
        'gross_amount',
        'net_amount'
    ];
}
