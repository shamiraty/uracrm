<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description'];

    // Relationship with Branches if needed
    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_department');
    }
}
