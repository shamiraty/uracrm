<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class URAMobile extends Model
{
    use HasFactory;
    protected $table = 'ura_mobile';
    protected $fillable = ['mobile_number'];

    public function enquiry()
    {
        return $this->morphOne(Enquiry::class, 'enquirable');
    }
}
