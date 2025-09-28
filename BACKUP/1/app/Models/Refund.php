<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;
    protected $fillable = [
        'enquiry_id',
        'refund_amount',
        'refund_duration',
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }
}
