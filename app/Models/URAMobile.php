<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class URAMobile extends Model
{
    use HasFactory;
    protected $table = 'ura_mobile';
    protected $fillable = ['enquiry_id', 'mobile_number'];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }
}
