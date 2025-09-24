<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyDeduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_number',
        'check_number',
        'first_name',
        'middle_name',
        'last_name',
        'national_id',
        'vote_code',
        'vote_name',
        'department_code',
        'department_name',
        'deduction_code',
        'deduction_description',
        'balance_amount',
        'deduction_amount',
        'has_stop_pay',
        'stop_pay_reason',
        'check_date',
    ];
}
