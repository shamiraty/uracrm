<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['name', 'region_id'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    //new added saturyday morning 12/10/24
    public function enquiries()
{
    return $this->hasMany(Enquiry::class);
}

}

