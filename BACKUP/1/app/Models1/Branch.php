<?php

// app/Models/Branch.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'district_id', 'region_id'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'branch_department');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'branch_user');
    }
}
