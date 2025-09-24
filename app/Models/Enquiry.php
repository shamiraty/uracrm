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
        'district_id',
        'phone',
        'region_id',
        'type',
        'status',
        'basic_salary',
        'allowances',
        'take_home',
        'registered_by',
        'branch_id',
        'file_id',
        'command_id'  
    ];

    public $timestamps = true;
    public function users()
    {
        return $this->belongsToMany(User::class, 'enquiry_user');
    }
    /*
    public function command()
    {
        return $this->belongsTo(Command::class, 'command_id');  // Define the relationship to Command
    }
        */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    public function loanApplication()
    {
        return $this->hasOne(LoanApplication::class);
    }
  // Define the relationship to the Branch model
  public function branch()
  {
      return $this->belongsTo(Branch::class, 'branch_id');  // Relating branch_id to Branch model
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
        return $this->belongsTo(District::class, 'district_id');  // Ensure 'district_id' is the correct column name
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');  // Ensure 'region_id' is the correct column name
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'enquiry_user')
                    ->withPivot('assigned_by')
                    ->withTimestamps();
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
// In Enquiry, Payment, LoanApplication models
public function folios()
{
    return $this->morphMany(Folio::class, 'folioable');
}

//Export  table kwenye Enquiry------------------------------------------------
public function enquirable()
    {
        return $this->morphTo(); // This sets up the polymorphic relationship
    }


    public function enquiry()
    {
        return $this->morphOne(Enquiry::class, 'enquirable');
    }
    

// Enquiry.php

// Enquiry.php
/*
public function command()
{
    return $this->belongsTo(Command::class);
}
    */
public function command()
{
    return $this->belongsTo(Command::class, 'command_id');
}


//Export  table kwenye Enquiry------------------------------end------------------
//**********************************DASHBOARD  ANALYTICS*********************/


 // Retrieve total payment amounts grouped by enquiry type
/*
1. table mbili zimetumika hapa 'Enquiry', 'Payments'
2. kuonyesha kila Enquiry.id  payment yake kwenye Payments.Enquiry_ID
3. kwa kuangalia Enquiry.Type
4. Enquiry.Type-> Payment.amount sum ->  where both enquiry.id and payment.enquiry_id are the same
*/
    public static function getTotalPaymentsByType()
    {
        return self::query()
            ->join('payments', 'enquiries.id', '=', 'payments.enquiry_id')
            ->select('enquiries.type')
            ->selectRaw('SUM(payments.amount) as total_amount')
            ->groupBy('enquiries.type')
            ->get();
    }


// Retrieve total payment amounts grouped by payment status  count and sum amount
/*
1. table mbili zimetumika hapa 'Enquiry', 'Payments'
2. kuonyesha kila Enquiry.id  payment yake kwenye Payments.Enquiry_ID
3. kwa kuangalia Enquiry.Type  count na sum amount
4. Enquiry.Type-> Payment.amount sum ->  where both enquiry.id and payment.enquiry_id are the same
*/
 
public static function getTotalPaymentsByStatus()
{
    return self::query()
        ->join('payments', 'enquiries.id', '=', 'payments.enquiry_id')
        ->select('enquiries.type', 'payments.status')
        ->selectRaw('COUNT(payments.id) as payment_count')
        ->selectRaw('SUM(payments.amount) as total_amount')
        ->groupBy('enquiries.type', 'payments.status')
        ->get();
}


//on dashboard  return enquiry type by count

public static function getEnquiryTypeCount()
{
    return self::query()
        ->select('type')
        ->selectRaw('COUNT(*) as type_count')
        ->groupBy('type')
        ->get();
}






public static function getEnquiryPayments()
{
    return self::query()
        ->join('payments', 'enquiries.id', '=', 'payments.enquiry_id')
        ->select('enquiries.type')
        ->selectRaw('COUNT(enquiries.id) as enquiry_count')
        ->selectRaw('SUM(payments.amount) as total_amount')
        ->groupBy('enquiries.type')
        ->get();
}










// In Enquiry Model

// Function to retrieve total payments and count per Enquiry type
public static function getTotalPaymentsAndCountByType()
{
    return self::query()
        ->select('type')
        ->selectRaw('COUNT(id) as enquiry_count')
        ->join('payments', 'enquiries.id', '=', 'payments.enquiry_id')
        ->selectRaw('SUM(payments.amount) as total_amount')
        ->groupBy('enquiries.type')
        ->get();
}



 // In App/Models/Enquiry.php

public function loanApplications()
{
    return $this->hasMany(LoanApplication::class); 
}  



}
