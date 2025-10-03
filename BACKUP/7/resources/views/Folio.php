<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{
    protected $fillable = ['file_path', 'folioable_type', 'folioable_id', 'file_id'];

    public function folioable()
    {
        return $this->morphTo();
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
