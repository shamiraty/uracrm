<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Injury extends Model
{
    use HasFactory;
    protected $fillable = ['description'];

    public function enquiry()
    {
        return $this->morphOne(Enquiry::class, 'enquirable');
    }
}
