<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'enquiry_id',
        'date_of_retirement',
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }
}
