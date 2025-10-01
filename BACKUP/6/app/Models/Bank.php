<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = [
        'name',
        'swift_code',
        'short_name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function loanOffers()
    {
        return $this->hasMany(LoanOffer::class);
    }

    public function branches()
    {
        return $this->hasMany(BankBranch::class);
    }
}