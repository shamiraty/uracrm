<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'ClientId',
        'Name',
        'AccountNumber',
        'checkNo',
        'Gender',
        'phone',
    ];

        public function cardDetails()
    {
        return $this->hasMany(CardDetail::class, 'check_namba', 'checkNo');
    }
}