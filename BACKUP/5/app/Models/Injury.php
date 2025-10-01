<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Injury extends Model
{
    use HasFactory;
    protected $fillable = ['enquiry_id', 'description'];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }
}
