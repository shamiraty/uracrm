<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    use HasFactory;
    protected $fillable = [
        'enquiry_id',
        'share_amount',
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }
}
