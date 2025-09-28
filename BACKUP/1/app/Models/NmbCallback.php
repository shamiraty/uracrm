<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NmbCallback extends Model
{
    use HasFactory;

    public function loanOffer()
{
    return $this->belongsTo(LoanOffer::class);
}
}
