<?php

namespace App\Models;

use App\Models\PaymentLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'enquiry_id',
        'amount',
        'status',
        'payment_date',
        'note_path',
         'initiated_by',
          'approved_by',
           'rejected_by',

           'paid_by'
    ];

    protected $casts = [
        'payment_date' => 'datetime:Y-m-d',  // Ensuring the format is recognized when retrieved
    ];



public function setPaymentDateAttribute($value)
{
    try {
        // Carbon::parse() attempts to parse a date from the given string without a specific format.
        $this->attributes['payment_date'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
    } catch (\Exception $e) {
        // Log the error or handle it as needed
        logger()->error('Error parsing date: ' . $e->getMessage());
        // Optionally, set a default value or leave it to handle elsewhere
    }
}

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }
    public function initiatedBy()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }


    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function logs()
    {
        return $this->hasMany(PaymentLog::class);
    }


    //added  jumamosi 12/10/24
    public static function getMetrics($startDate = null, $endDate = null)
    {
        // Default to today's date if no dates are provided
        $startDate = $startDate ?? today()->startOfDay();
        $endDate = $endDate ?? today()->endOfDay();
    
        return self::whereBetween('payment_date', [$startDate, $endDate]) // Filter for the given date range
            ->select('status')
            ->selectRaw('count(*) as count')
            ->selectRaw('sum(amount) as sum')
            ->groupBy('status')
            ->get();
    }
    

}
