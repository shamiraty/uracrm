<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'file_series_id', 'keyword1_id', 'keyword2_id', 'running_number','file_part',
        'file_subject', 'reference_number', 'department_id', 'branch_id'
    ];

    public function fileSeries() {
        return $this->belongsTo(FileSeries::class);
    }

    public function keyword1() {
        return $this->belongsTo(Keyword::class, 'keyword1_id');
    }

    public function keyword2() {
        return $this->belongsTo(Keyword::class, 'keyword2_id');
    }

    public function department() {
        return $this->belongsTo(Department::class);  // Assume you have a Department model
    }

    public function folios()
    {
        return $this->hasMany(Folio::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
