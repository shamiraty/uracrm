<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanOfferApproval extends Model
{
    protected $fillable = [
        'loan_offer_id',
        'approval_type',
        'status',
        'approved_by',
        'rejected_by',
        'reason',
        'approved_at',
        'rejected_at',
        'fsp_reference_number',
        'total_amount_to_pay',
        'other_charges'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'total_amount_to_pay' => 'decimal:2',
        'other_charges' => 'decimal:2'
    ];

    public function loanOffer()
    {
        return $this->belongsTo(LoanOffer::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function approve($userId, $additionalData = [])
    {
        $this->status = 'approved';
        $this->approved_by = $userId;
        $this->approved_at = now();
        
        if (!empty($additionalData)) {
            $this->fill($additionalData);
        }
        
        return $this->save();
    }

    public function reject($userId, $reason = null)
    {
        $this->status = 'rejected';
        $this->rejected_by = $userId;
        $this->rejected_at = now();
        $this->reason = $reason;
        
        return $this->save();
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }
}