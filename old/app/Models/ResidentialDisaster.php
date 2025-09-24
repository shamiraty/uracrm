<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentialDisaster extends Model
{
    use HasFactory;
    protected $fillable = ['disaster_type'];

    public function enquiry()
    {
        return $this->morphOne(Enquiry::class, 'enquirable');
    }
    
}
