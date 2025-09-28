<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;
    protected $fillable = [
        'enquiry_id',
        'from_amount',
        'to_amount',
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }
}
