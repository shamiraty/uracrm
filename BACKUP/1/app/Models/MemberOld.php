<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'members';

    // Define the primary key
    protected $primaryKey = 'check_number';

    // Disable timestamps (if not required)
    public $timestamps = false;

    // Specify the fillable fields
    protected $fillable = [
        'check_number',
        'first_name',
        'middle_name',
        'last_name',
        'member_number',
    ];
}
