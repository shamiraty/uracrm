<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'enquiry_id',
        'user_id',
        'amount',
        'interest',
        'remarks',
        'description',
        'from_amount',
        'to_amount',
        'duration',
        'date_of_retirement'
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
