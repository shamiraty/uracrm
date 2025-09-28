<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condolence extends Model
{
    use HasFactory;
    protected $fillable = ['enquiry_id', 'dependent_member_type', 'gender'];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }
}
