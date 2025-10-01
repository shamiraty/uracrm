<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipChange extends Model
{
    use HasFactory;
    protected $fillable = [
        'enquiry_id',
        'category', // 'normal' or 'job_termination'
        'action', // e.g., 'unjoin'
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

 

}
