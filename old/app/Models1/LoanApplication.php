<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'enquiry_id',
        'loan_amount',
        'loan_duration',
        'interest_rate',
        'monthly_deduction',
        'total_loan_with_interest',
        'total_interest',
        'processing_fee',
        'insurance',
        'disbursement_amount',
        'status'
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

    //added  jumamosi  12/10/24

     // Method to get status metrics

 
     // Method to get type metrics (assuming you have a 'type' field)
     // Updated method to get status metrics based on today's date
     public static function getMetrics($startDate = null, $endDate = null)
     {
         // Default to today's date if no dates are provided
         $startDate = $startDate ?? today()->startOfDay();
         $endDate = $endDate ?? today()->endOfDay();
     
         return self::whereBetween('updated_at', [$startDate, $endDate]) // Filter for the given date range
             ->select('status')
             ->selectRaw('count(*) as count')
             ->selectRaw('sum(loan_amount) as sum')
             ->groupBy('status')
             ->get();
     }
     


//loan analysis
// Method to get detailed metrics by status, count, and sums
public static function getDetailedStatusMetrics($startDate = null, $endDate = null)
{
    // Default to today's date if no dates are provided
    $startDate = $startDate ?? today()->startOfDay();
    $endDate = $endDate ?? today()->endOfDay();

    return self::whereBetween('updated_at', [$startDate, $endDate])
        ->select('status')
        ->selectRaw('count(*) as count')
        ->selectRaw('sum(loan_amount) as total_loan_amount')
        ->selectRaw('sum(interest_rate) as total_interest_rate')
        ->selectRaw('sum(monthly_deduction) as total_monthly_deduction')
        ->selectRaw('sum(total_loan_with_interest) as total_loan_with_interest')
        ->selectRaw('sum(total_interest) as total_interest')
        ->selectRaw('sum(processing_fee) as total_processing_fee')
        ->selectRaw('sum(insurance) as total_insurance')
        ->selectRaw('sum(disbursement_amount) as total_disbursement_amount')
        ->groupBy('status')
        ->get();
}





 // Method to get monthly loan application data for the current year
 public static function getMonthlyDataForCurrentYear()
 {
     $currentYear = today()->year;

     return self::whereYear('updated_at', $currentYear)
         ->selectRaw('MONTH(updated_at) as month, COUNT(*) as count, SUM(loan_amount) as total_loan_amount')
         ->groupBy('month')
         ->orderBy('month')
         ->get();
 }

}
