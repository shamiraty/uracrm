<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentialDisaster extends Model
{
    use HasFactory;
    protected $fillable = ['enquiry_id', 'disaster_type'];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }
    
}
