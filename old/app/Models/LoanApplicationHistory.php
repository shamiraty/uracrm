<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplicationHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'loan_application_id',
        'user_id',
        'loan_amount',
        'loan_duration',
        'interest_rate',
        'monthly_deduction',
        'total_loan_with_interest',
        'total_interest',
        'processing_fee',
        'insurance',
        'disbursement_amount',
        'status',
        'action_taken', // Describes the action performed (e.g., "Created", "Updated", "Processed")
        'branch_id_employee'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'loan_amount' => 'float',
        'monthly_deduction' => 'float',
        'total_loan_with_interest' => 'float',
        'total_interest' => 'float',
        'processing_fee' => 'float',
        'insurance' => 'float',
        'disbursement_amount' => 'float',
    ];

    /**
     * Get the loan application associated with the history record.
     */
    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    /**
     * Get the user who made the change.
     */
    
     public function branchEmployee()
     {
         return $this->belongsTo(Branch::class, 'branch_id_employee');
     }
     public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * You might also want to add methods to fetch historical records in a specific format,
     * or to generate summaries or reports based on the history data.
     */
}
