<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankBranch extends Model
{
    protected $fillable = [
        'bank_id',
        'branch_name',
        'branch_code',
        'address',
        'city',
        'region',
        'phone',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFullNameAttribute()
    {
        return $this->bank->name . ' - ' . $this->branch_name;
    }
}