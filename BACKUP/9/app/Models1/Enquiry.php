<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_received',
        'full_name',
        'force_no',
        'check_number',
        'account_number',
        'bank_name',
        'district',
        'phone',
        'region',
        'type',
        'loan_type',
        'loan_amount',
        'loan_duration',
        'refund_amount',
        'loan_duration',
        'loan_category',
        'share_amount',
        'date_of_retirement',
        'retirement_amount',
        'from_amount',
        'to_amount',
        'withdraw_saving_amount',
        'withdraw_saving_reason',
        'withdraw_deposit_amount',
        'withdraw_deposit_reason',
        'unjoin_reason',
        'category',
        'file_path',
        'benefit_amount',
        'benefit_description',
        'benefit_remarks',
        'status',
        'basic_salary',
        'allowances',
        'take_home'


    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'enquiry_user');
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    public function loanApplication()
    {
        return $this->hasOne(LoanApplication::class);
    }

    
public function getDateReceivedAttribute($value)
{
    try {
        // Use Carbon::parse() to automatically interpret the date format
        return \Carbon\Carbon::parse($value)->format('d/m/Y');
    } catch (\Exception $e) {
        // Log the error or handle it appropriately
        logger()->error('Error parsing date received: ' . $e->getMessage());
        return null;  // Return null or some default value if the date parsing fails
    }
}


    // Get a detailed description for logging or notification purposes
    public function getDetailSummaryAttribute()
    {
        return "{$this->type} enquiry for {$this->full_name} received on {$this->date_received}";
    }

    public function response()
    {
        return $this->hasOne(Response::class);
    }
    public function assign()
{
    $this->update(['status' => 'assigned']);
}

public function approve()
{
    $this->update(['status' => 'approved']);
}

public function reject()
{
    $this->update(['status' => 'rejected']);
}

public function requiresPayment()
    {
        // List the types of enquiries that require payment
        $typesRequiringPayment = [
            'refund',
            'retirement',
            'withdraw_savings',
            'deduction_add',
            'share_enquiry',
                               'retirement',
                               'withdraw_deposit',
                               'unjoin_membership'
        ];

        return in_array($this->type, $typesRequiringPayment);
    }

    // Check if payment has already been initiated
    public function paymentInitiated()
    {
        return $this->payment()->exists();
    }



    //added  jumamosi  12/10/24  morning

    public function district()
    {
        return $this->belongsTo(District::class);
    }
    
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    


}
