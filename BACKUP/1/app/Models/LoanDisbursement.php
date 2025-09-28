<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanDisbursement extends Model
{
    protected $fillable = [
        'loan_offer_id',
        'bank_id',
        'channel_identifier',
        'destination_code',
        'swift_code',
        'status',
        'amount',
        'net_amount',
        'account_number',
        'account_name',
        'reference_number',
        'batch_id',
        'transaction_id',
        'disbursed_at',
        'disbursed_by',
        'failure_reason',
        'response_data'
    ];

    protected $casts = [
        'disbursed_at' => 'datetime',
        'amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'response_data' => 'array'
    ];

    public function loanOffer()
    {
        return $this->belongsTo(LoanOffer::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function disbursedBy()
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }

    public function markAsSuccess($transactionId, $referenceNumber = null, $responseData = null)
    {
        $this->status = 'success';
        $this->transaction_id = $transactionId;
        $this->reference_number = $referenceNumber;
        $this->disbursed_at = now();
        $this->response_data = $responseData;
        
        return $this->save();
    }

    public function markAsFailed($reason, $responseData = null)
    {
        $this->status = 'failed';
        $this->failure_reason = $reason;
        $this->response_data = $responseData;
        
        return $this->save();
    }

    public function isSuccess()
    {
        return $this->status === 'success';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public static function createDisbursement($loanOfferId, $bankId, $amount, $accountNumber, $accountName = null)
    {
        return self::create([
            'loan_offer_id' => $loanOfferId,
            'bank_id' => $bankId,
            'status' => 'pending',
            'amount' => $amount,
            'account_number' => $accountNumber,
            'account_name' => $accountName
        ]);
    }
}