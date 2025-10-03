<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanOfferTopup extends Model
{
    protected $fillable = [
        'new_loan_offer_id',
        'original_loan_offer_id',
        'original_loan_number',
        'settlement_amount',
        'payoff_amount',
        'outstanding_balance',
        'payment_reference_number',
        'final_payment_date',
        'last_deduction_date',
        'last_pay_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'final_payment_date' => 'date',
        'last_deduction_date' => 'datetime',
        'last_pay_date' => 'datetime',
        'end_date' => 'datetime',
        'settlement_amount' => 'decimal:2',
        'payoff_amount' => 'decimal:2',
        'outstanding_balance' => 'decimal:2'
    ];

    public function newLoanOffer()
    {
        return $this->belongsTo(LoanOffer::class, 'new_loan_offer_id');
    }

    public function originalLoanOffer()
    {
        return $this->belongsTo(LoanOffer::class, 'original_loan_offer_id');
    }

    public function approve()
    {
        $this->status = 'approved';
        return $this->save();
    }

    public function disburse()
    {
        $this->status = 'disbursed';
        return $this->save();
    }

    public function cancel()
    {
        $this->status = 'cancelled';
        return $this->save();
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isDisbursed()
    {
        return $this->status === 'disbursed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public static function createFromBalanceResponse($newLoanOfferId, $originalLoanOfferId, $data)
    {
        return self::create([
            'new_loan_offer_id' => $newLoanOfferId,
            'original_loan_offer_id' => $originalLoanOfferId,
            'original_loan_number' => $data['loan_number'],
            'settlement_amount' => $data['settlement_amount'] ?? null,
            'payoff_amount' => $data['total_payoff_amount'] ?? null,
            'outstanding_balance' => $data['outstanding_balance'] ?? null,
            'payment_reference_number' => $data['payment_reference_number'] ?? null,
            'final_payment_date' => $data['final_payment_date'] ?? null,
            'last_deduction_date' => $data['last_deduction_date'] ?? null,
            'last_pay_date' => $data['last_pay_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'status' => 'pending'
        ]);
    }
}