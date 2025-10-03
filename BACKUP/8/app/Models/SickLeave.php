<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SickLeave extends Model
{
    use HasFactory;
    protected $fillable = ['enquiry_id', 'startdate', 'enddate','days'];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }
}
