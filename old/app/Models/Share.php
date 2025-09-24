<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    use HasFactory;
    protected $fillable = [
        'share_amount',
    ];

    public function enquiry()
    {
        return $this->morphOne(Enquiry::class, 'enquirable');
    }
}
