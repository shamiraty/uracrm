<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;
    protected $fillable = [
        'enquiry_id',
        'amount',
        'type', // 'savings' or 'deposit'
        'reason',
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }
}
