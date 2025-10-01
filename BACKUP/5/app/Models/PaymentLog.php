<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable = ['payment_id', 'initiated_by', 'approved_by', 'paid_by', 'rejected_by', 'branch_id_employee'];

    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function branchEmployee()
    {
        return $this->belongsTo(Branch::class, 'branch_id_employee');  // Define the relationship with the Branch model
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
