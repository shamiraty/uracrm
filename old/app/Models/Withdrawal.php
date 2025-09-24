<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount',
        'type', // 'savings' or 'deposit'
    ];

    public function enquiry()
    {
        return $this->morphOne(Enquiry::class, 'enquirable');
    }
}
