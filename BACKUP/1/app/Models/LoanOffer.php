<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanOffer extends Model
{
    protected $table = 'loan_offers';

    protected $fillable = [
        'check_number', 'first_name', 'middle_name', 'last_name', 'sex',
        'employment_date', 'marital_status', 'confirmation_date',
        'bank_account_number', 'nearest_branch_name', 'nearest_branch_code',
        'vote_code', 'vote_name', 'nin', 'designation_code', 'designation_name',
        'basic_salary', 'net_salary', 'one_third_amount', 'total_employee_deduction',
        'retirement_date', 'terms_of_employment', 'requested_amount', 'net_loan_amount',
        'take_home_amount', 'desired_deductible_amount', 'tenure', 'fsp_code', 'product_code',
        'interest_rate', 'processing_fee', 'insurance', 'physical_address',
        'telephone_number', 'email_address', 'mobile_number', 'application_number',
        'loan_purpose', 'contract_start_date', 'contract_end_date',
        'swift_code', 'bank_id', 'funding','reason',
        'fsp_reference_number',
        'loan_number',
        'total_amount_to_pay',
        'other_charges',
        'approval',
        'status',
        'installments_paid',
        'settlement_amount',
        'offer_type',
        'loan_type',
        'payment_reference_number',
        'final_payment_date',
        'last_deduction_date',
        'last_pay_date',
        'end_date_str',
        'outstanding_balance',
    ];

    protected $casts = [
        'final_payment_date'    => 'datetime',
        'last_deduction_date'   => 'datetime',
        'last_pay_date'         => 'datetime',
        'employment_date'       => 'date',
        'confirmation_date'     => 'date',
        'contract_start_date'   => 'date',
        'contract_end_date'     => 'date',
    ];

    public function callbacks()
    {
        return $this->hasMany(NmbCallback::class);
    }

    public function paymentDestination()
    {
        return $this->belongsTo(PaymentDestination::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function approvals()
    {
        return $this->hasMany(LoanOfferApproval::class);
    }

    public function disbursements()
    {
        return $this->hasMany(LoanDisbursement::class);
    }

    public function topupAsNew()
    {
        return $this->hasOne(LoanOfferTopup::class, 'new_loan_offer_id');
    }

    public function topupAsOriginal()
    {
        return $this->hasOne(LoanOfferTopup::class, 'original_loan_offer_id');
    }

    public function latestApproval()
    {
        return $this->hasOne(LoanOfferApproval::class)->latest();
    }

    public function latestDisbursement()
    {
        return $this->hasOne(LoanDisbursement::class)->latest();
    }

    public function initialApproval()
    {
        return $this->hasOne(LoanOfferApproval::class)->where('approval_type', 'initial');
    }

    public function finalApproval()
    {
        return $this->hasOne(LoanOfferApproval::class)->where('approval_type', 'final');
    }

    public function employerApproval()
    {
        return $this->hasOne(LoanOfferApproval::class)->where('approval_type', 'employer');
    }

    public function fspApproval()
    {
        return $this->hasOne(LoanOfferApproval::class)->where('approval_type', 'fsp');
    }

    public function isNewLoan()
    {
        return $this->loan_type === 'new';
    }

    public function isTopupLoan()
    {
        return $this->loan_type === 'topup';
    }

    public function hasApproval($type = null)
    {
        $query = $this->approvals();
        
        if ($type) {
            $query->where('approval_type', $type);
        }
        
        return $query->where('status', 'approved')->exists();
    }

    public function isFullyApproved()
    {
        return $this->hasApproval('initial') && $this->hasApproval('final');
    }

    public function isDisbursed()
    {
        return $this->disbursements()->where('status', 'success')->exists();
    }

    protected static function booted()
    {
        static::saving(function ($loanOffer) {
            // Automatically set bank_id based on swift_code if not already set
            if ($loanOffer->swift_code && !$loanOffer->bank_id) {
                $bank = Bank::where('swift_code', $loanOffer->swift_code)->first();
                if ($bank) {
                    $loanOffer->bank_id = $bank->id;
                }
            }
        });
    }
}
