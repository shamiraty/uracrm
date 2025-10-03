<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Payment;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PaymentController extends Controller
{

public function accountantDashboard(Request $request)
{
    $user = auth()->user();

    // Get assigned enquiries for accountant with detailed relationships
    $query = Enquiry::with(['payment', 'registeredBy.role', 'assignedUsers.role', 'region', 'district', 'branch'])
        ->whereHas('assignedUsers', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

    // Apply filters
    if ($request->filled('status')) {
        if ($request->status === 'assigned_no_payment') {
            $query->where('status', 'assigned')->doesntHave('payment');
        } else {
            $query->whereHas('payment', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }
    }

    // NEW: Add enquiry type filter
    if ($request->filled('type')) {
        $query->where('type', $request->type);
        // Load child table relationship based on type
        $childRelation = $this->getRelationshipForType($request->type);
        if ($childRelation) {
            $query->with($childRelation);
        }
    }

    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('check_number', 'like', "%{$search}%")
              ->orWhere('full_name', 'like', "%{$search}%")
              ->orWhere('account_number', 'like', "%{$search}%");
        });
    }

    // Handle exports
    if ($request->has('export')) {
        if ($request->export === 'excel_general') {
            // General report - export all without filters
            $generalQuery = Enquiry::with(['payment', 'registeredBy', 'region', 'district', 'branch'])
                ->whereHas('assignedUsers', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            return $this->exportAccountantExcel($generalQuery, new Request());
        } elseif ($request->export === 'excel') {
            return $this->exportAccountantExcel($query, $request);
        } elseif ($request->export === 'pdf') {
            return $this->exportAccountantPDF($query, $request);
        }
    }

    $enquiries = $query->paginate(10)->withQueryString();

    // Get analytics
    $analytics = $this->getAccountantAnalytics($user);

    return view('payments.accountant_actions', compact('enquiries', 'analytics'));
}

private function getAccountantAnalytics($user)
{
    $baseQuery = Enquiry::whereHas('assignedUsers', function ($q) use ($user) {
        $q->where('user_id', $user->id);
    });

    // Clone base query for each calculation to avoid conflicts
    $total = (clone $baseQuery)->count();
    $assignedNoPayment = (clone $baseQuery)->where('status', 'assigned')->doesntHave('payment')->count();
    $initiated = (clone $baseQuery)->whereHas('payment', function ($q) {
        $q->where('status', 'initiated');
    })->count();
    $approved = (clone $baseQuery)->whereHas('payment', function ($q) {
        $q->where('status', 'approved');
    })->count();
    $paid = (clone $baseQuery)->whereHas('payment', function ($q) {
        $q->where('status', 'paid');
    })->count();
    $rejected = (clone $baseQuery)->whereHas('payment', function ($q) {
        $q->where('status', 'rejected');
    })->count();
    $overdue = (clone $baseQuery)->where('status', 'assigned')
        ->where('created_at', '<', now()->subDays(3))->count();

    return [
        'total' => $total,
        'assigned_no_payment' => $assignedNoPayment,
        'initiated' => $initiated,
        'approved' => $approved,
        'paid' => $paid,
        'rejected' => $rejected,
        'overdue' => $overdue,
    ];
}

private function exportAccountantData($query)
{
    $enquiries = $query->get();

    $headers = [
        'Content-Type' => 'application/vnd.ms-excel',
        'Content-Disposition' => 'attachment; filename="accountant_payments_' . date('Y-m-d_H-i-s') . '.xls"'
    ];

    $content = '<table border="1">';
    $content .= '<tr>';
    $content .= '<th>S/N</th>';
    $content .= '<th>Date Received</th>';
    $content .= '<th>Check Number</th>';
    $content .= '<th>Full Name</th>';
    $content .= '<th>Force Number</th>';
    $content .= '<th>Phone</th>';
    $content .= '<th>Bank Name</th>';
    $content .= '<th>Account Number</th>';
    $content .= '<th>Type</th>';
    $content .= '<th>Region</th>';
    $content .= '<th>District</th>';
    $content .= '<th>Branch</th>';
    $content .= '<th>Registered By</th>';
    $content .= '<th>Payment Status</th>';
    $content .= '<th>Payment Amount</th>';
    $content .= '<th>Payment Date</th>';
    $content .= '</tr>';

    foreach ($enquiries as $index => $enquiry) {
        $content .= '<tr>';
        $content .= '<td>' . ($index + 1) . '</td>';
        $content .= '<td>' . ($enquiry->date_received ?? $enquiry->created_at->format('Y-m-d')) . '</td>';
        $content .= '<td>' . $enquiry->check_number . '</td>';
        $content .= '<td>' . ucwords($enquiry->full_name) . '</td>';
        $content .= '<td>' . ($enquiry->force_no ?? 'N/A') . '</td>';
        $content .= '<td>' . ($enquiry->phone ?? 'N/A') . '</td>';
        $content .= '<td>' . strtoupper($enquiry->bank_name ?? 'N/A') . '</td>';
        $content .= '<td>' . ($enquiry->account_number ?? 'N/A') . '</td>';
        $content .= '<td>' . ucfirst(str_replace('_', ' ', $enquiry->type)) . '</td>';
        $content .= '<td>' . ($enquiry->region->name ?? 'N/A') . '</td>';
        $content .= '<td>' . ($enquiry->district->name ?? 'N/A') . '</td>';
        $content .= '<td>' . ($enquiry->branch->name ?? 'N/A') . '</td>';
        $content .= '<td>' . ($enquiry->registeredBy->name ?? 'N/A') . '</td>';
        $content .= '<td>' . ($enquiry->payment ? ucfirst($enquiry->payment->status) : 'Awaiting Initiation') . '</td>';
        $content .= '<td>' . ($enquiry->payment ? number_format($enquiry->payment->amount) : 'N/A') . '</td>';
        $content .= '<td>' . ($enquiry->payment ? $enquiry->payment->created_at->format('Y-m-d H:i') : 'N/A') . '</td>';
        $content .= '</tr>';
    }
    $content .= '</table>';

    return response($content, 200, $headers);
}

/**
 * Export accountant data to Excel with child table fields
 */
private function exportAccountantExcel($query, $request)
{
    // Load child relationships if type filter is applied
    $with = ['payment', 'registeredBy', 'region', 'district', 'branch'];
    if ($request->filled('type')) {
        $childRelation = $this->getRelationshipForType($request->type);
        if ($childRelation) {
            $with[] = $childRelation;
        }
    }

    $enquiries = $query->with($with)->get();
    $enquiryType = $request->get('type');

    $headers = [
        'Content-Type' => 'application/vnd.ms-excel',
        'Content-Disposition' => 'attachment; filename="accountant_report_' . date('Y-m-d_H-i-s') . '.xls"'
    ];

    $content = '<table border="1">';
    $content .= '<tr>';
    $content .= '<th>S/N</th>';
    $content .= '<th>Date Received</th>';
    $content .= '<th>Check Number</th>';
    $content .= '<th>Full Name</th>';
    $content .= '<th>Force Number</th>';
    $content .= '<th>Phone</th>';
    $content .= '<th>Bank Name</th>';
    $content .= '<th>Account Number</th>';
    $content .= '<th>Type</th>';

    // Add type-specific headers based on enquiry type
    if ($enquiryType) {
        $content .= $this->getChildTableHeaders($enquiryType);
    }

    $content .= '<th>Region</th>';
    $content .= '<th>District</th>';
    $content .= '<th>Branch</th>';
    $content .= '<th>Registered By</th>';
    $content .= '<th>Payment Status</th>';
    $content .= '<th>Payment Amount</th>';
    $content .= '<th>Payment Date</th>';
    $content .= '</tr>';

    foreach ($enquiries as $index => $enquiry) {
        $content .= '<tr>';
        $content .= '<td>' . ($index + 1) . '</td>';
        $content .= '<td>' . ($enquiry->date_received ?? $enquiry->created_at->format('Y-m-d')) . '</td>';
        $content .= '<td>' . $enquiry->check_number . '</td>';
        $content .= '<td>' . ucwords($enquiry->full_name) . '</td>';
        $content .= '<td>' . ($enquiry->force_no ?? 'N/A') . '</td>';
        $content .= '<td>' . ($enquiry->phone ?? 'N/A') . '</td>';
        $content .= '<td>' . strtoupper($enquiry->bank_name ?? 'N/A') . '</td>';
        $content .= '<td>' . ($enquiry->account_number ?? 'N/A') . '</td>';
        $content .= '<td>' . ucfirst(str_replace('_', ' ', $enquiry->type)) . '</td>';

        // Add type-specific data
        if ($enquiryType) {
            $content .= $this->getChildTableData($enquiry, $enquiryType);
        }

        $content .= '<td>' . ($enquiry->region->name ?? 'N/A') . '</td>';
        $content .= '<td>' . ($enquiry->district->name ?? 'N/A') . '</td>';
        $content .= '<td>' . ($enquiry->branch->name ?? 'N/A') . '</td>';
        $content .= '<td>' . ($enquiry->registeredBy->name ?? 'N/A') . '</td>';
        $content .= '<td>' . ($enquiry->payment ? ucfirst($enquiry->payment->status) : 'Awaiting Initiation') . '</td>';
        $content .= '<td>' . ($enquiry->payment ? number_format($enquiry->payment->amount) : 'N/A') . '</td>';
        $content .= '<td>' . ($enquiry->payment ? $enquiry->payment->created_at->format('Y-m-d H:i') : 'N/A') . '</td>';
        $content .= '</tr>';
    }
    $content .= '</table>';

    return response($content, 200, $headers);
}

/**
 * Export accountant data to PDF with child table fields
 */
private function exportAccountantPDF($query, $request)
{
    // Load child relationships if type filter is applied
    $with = ['payment', 'registeredBy', 'region', 'district', 'branch'];
    if ($request->filled('type')) {
        $childRelation = $this->getRelationshipForType($request->type);
        if ($childRelation) {
            $with[] = $childRelation;
        }
    }

    $enquiries = $query->with($with)->get();

    $analytics = [
        'total' => $enquiries->count(),
        'pending' => $enquiries->where('status', 'pending')->count(),
        'assigned' => $enquiries->where('status', 'assigned')->count(),
        'approved' => $enquiries->filter(function($e) { return $e->payment && $e->payment->status == 'approved'; })->count(),
    ];

    $enquiryType = $request->get('type');
    $accountant = auth()->user();

    $pdf = \PDF::loadView('payments.accountant_pdf_report', compact('enquiries', 'analytics', 'enquiryType', 'accountant'));

    return $pdf->download('accountant_report_' . date('Y-m-d') . '.pdf');
}

/**
 * Get relationship name based on enquiry type
 */
private function getRelationshipForType($type)
{
    $relationships = [
        'loan_application' => 'loanApplication',
        'refund' => 'refund',
        'withdraw_savings' => 'withdrawal',
        'withdraw_deposit' => 'withdrawal',
        'deduction_add' => 'deduction',
        'condolences' => 'condolence',
        'injured_at_work' => 'injury',
        'sick_for_30_days' => 'sickLeave',
        'benefit_from_disasters' => 'benefit',
        'retirement' => 'retirement',
        'share_enquiry' => 'share',
        'unjoin_membership' => 'membershipChanges',
        'join_membership' => 'membershipChanges',
        'residential_disaster' => 'residentialDisaster',
        'ura_mobile' => 'uraMobile',
    ];

    return $relationships[$type] ?? null;
}

/**
 * Get child table headers for Excel export
 */
private function getChildTableHeaders($type)
{
    $headers = '';
    switch ($type) {
        case 'loan_application':
            $headers = '<th>Loan Type</th><th>Loan Amount</th><th>Loan Duration</th><th>Interest Rate</th><th>Monthly Deduction</th>';
            break;
        case 'refund':
            $headers = '<th>Refund Amount</th><th>Refund Duration</th>';
            break;
        case 'withdraw_savings':
        case 'withdraw_deposit':
            $headers = '<th>Withdrawal Amount</th><th>Withdrawal Reason</th><th>Days Since Request</th>';
            break;
        case 'deduction_add':
            $headers = '<th>From Amount</th><th>To Amount</th><th>Changes</th><th>Status</th>';
            break;
        case 'condolences':
            $headers = '<th>Dependent Type</th><th>Gender</th>';
            break;
        case 'injured_at_work':
            $headers = '<th>Injury Description</th>';
            break;
        case 'sick_for_30_days':
            $headers = '<th>Start Date</th><th>End Date</th><th>Days</th>';
            break;
        case 'retirement':
            $headers = '<th>Retirement Date</th>';
            break;
        case 'share_enquiry':
            $headers = '<th>Share Amount</th>';
            break;
        case 'unjoin_membership':
        case 'join_membership':
            $headers = '<th>Category</th><th>Membership Status</th>';
            break;
        case 'residential_disaster':
            $headers = '<th>Disaster Type</th>';
            break;
    }
    return $headers;
}

/**
 * Get child table data for Excel export
 */
private function getChildTableData($enquiry, $type)
{
    $data = '';
    switch ($type) {
        case 'loan_application':
            $loan = $enquiry->loanApplication;
            $data = '<td>' . ($loan->loan_type ?? 'N/A') . '</td>';
            $data .= '<td>' . ($loan ? number_format($loan->loan_amount) : 'N/A') . '</td>';
            $data .= '<td>' . ($loan->loan_duration ?? 'N/A') . '</td>';
            $data .= '<td>' . ($loan->interest_rate ?? 'N/A') . '%</td>';
            $data .= '<td>' . ($loan ? number_format($loan->monthly_deduction) : 'N/A') . '</td>';
            break;
        case 'refund':
            $refund = $enquiry->refund;
            $data = '<td>' . ($refund ? number_format($refund->refund_amount) : 'N/A') . '</td>';
            $data .= '<td>' . ($refund->refund_duration ?? 'N/A') . '</td>';
            break;
        case 'withdraw_savings':
        case 'withdraw_deposit':
            $withdrawal = $enquiry->withdrawal;
            $data = '<td>' . ($withdrawal ? number_format($withdrawal->amount) : 'N/A') . '</td>';
            $data .= '<td>' . ($withdrawal->reason ?? 'N/A') . '</td>';
            $data .= '<td>' . ($withdrawal->days ?? '0') . '</td>';
            break;
        case 'deduction_add':
            $deduction = $enquiry->deduction;
            $data = '<td>' . ($deduction ? number_format($deduction->from_amount) : 'N/A') . '</td>';
            $data .= '<td>' . ($deduction ? number_format($deduction->to_amount) : 'N/A') . '</td>';
            $data .= '<td>' . ($deduction ? number_format($deduction->changes) : 'N/A') . '</td>';
            $data .= '<td>' . ucfirst($deduction->status ?? 'N/A') . '</td>';
            break;
        case 'condolences':
            $condolence = $enquiry->condolence;
            $data = '<td>' . ucfirst($condolence->dependent_member_type ?? 'N/A') . '</td>';
            $data .= '<td>' . ucfirst($condolence->gender ?? 'N/A') . '</td>';
            break;
        case 'injured_at_work':
            $injury = $enquiry->injury;
            $data = '<td>' . ($injury->description ?? 'N/A') . '</td>';
            break;
        case 'sick_for_30_days':
            $sickLeave = $enquiry->sickLeave;
            $data = '<td>' . ($sickLeave->startdate ?? 'N/A') . '</td>';
            $data .= '<td>' . ($sickLeave->enddate ?? 'N/A') . '</td>';
            $data .= '<td>' . ($sickLeave->days ?? 'N/A') . '</td>';
            break;
        case 'retirement':
            $retirement = $enquiry->retirement;
            $data = '<td>' . ($retirement->date_of_retirement ?? 'N/A') . '</td>';
            break;
        case 'share_enquiry':
            $share = $enquiry->share;
            $data = '<td>' . ($share ? number_format($share->share_amount) : 'N/A') . '</td>';
            break;
        case 'unjoin_membership':
        case 'join_membership':
            $membership = $enquiry->membershipChanges->where('action', $type == 'join_membership' ? 'join' : 'unjoin')->first();
            $data = '<td>' . ucfirst($membership->category ?? 'N/A') . '</td>';
            $data .= '<td>' . ucfirst($membership->membership_status ?? 'N/A') . '</td>';
            break;
        case 'residential_disaster':
            $disaster = $enquiry->residentialDisaster;
            $data = '<td>' . ucfirst($disaster->disaster_type ?? 'N/A') . '</td>';
            break;
        default:
            $data = '<td>N/A</td>';
    }
    return $data;
}

public function bulkReject(Request $request)
{
    $request->validate([
        'payment_ids' => 'required|array',
        'reason' => 'required|string|min:10'
    ]);

    DB::beginTransaction();

    try {
        $enquiries = Enquiry::whereIn('id', $request->payment_ids)->get();
        $rejectedCount = 0;

        foreach ($enquiries as $enquiry) {
            if ($enquiry->payment && in_array($enquiry->payment->status, ['initiated', 'approved'])) {
                $enquiry->payment->update([
                    'status' => 'rejected',
                    'rejected_by' => auth()->id(),
                    'remarks' => $request->reason
                ]);

                $enquiry->payment->logs()->create([
                    'rejected_by' => auth()->id()
                ]);

                // Update enquiry status
                $enquiry->update(['status' => 'rejected']);

                // Send SMS notification
                $message = "Hello {$enquiry->full_name}, your payment request has been rejected. Reason: {$request->reason}. For more information, contact 0677 026301";
                $this->sendEnquirySMS($enquiry->phone, $message);

                $rejectedCount++;
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => "Successfully rejected {$rejectedCount} payments"
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'success' => false,
            'message' => 'Error processing bulk rejection: ' . $e->getMessage()
        ], 500);
    }
}

public function managerDashboard(Request $request)
{
    // Get payments for manager dashboard - show all statuses for history
    $query = Payment::with(['enquiry.region', 'enquiry.district', 'enquiry.branch', 'enquiry.registeredBy', 'initiatedBy'])
        ->orderBy('created_at', 'desc');

    // Apply filters
    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('enquiry', function ($q) use ($search) {
            $q->where('check_number', 'like', "%{$search}%")
              ->orWhere('full_name', 'like', "%{$search}%")
              ->orWhere('account_number', 'like', "%{$search}%");
        });
    }

    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Handle export
    if ($request->has('export') && $request->export === 'excel') {
        return $this->exportManagerData($query);
    }

    $payments = $query->paginate(10)->withQueryString();

    // Get analytics
    $analytics = $this->getManagerAnalytics();

    return view('payments.manager_actions', compact('payments', 'analytics'));
}

private function getManagerAnalytics()
{
    // Use separate queries for accurate counts
    $total = Payment::count();
    $initiated = Payment::where('status', 'initiated')->count();
    $approved = Payment::where('status', 'approved')->count();
    $paid = Payment::where('status', 'paid')->count();
    $rejected = Payment::where('status', 'rejected')->count();
    $totalAmountInitiated = Payment::where('status', 'initiated')->sum('amount');
    $totalAmountApproved = Payment::where('status', 'approved')->sum('amount');
    $overdueInitiated = Payment::where('status', 'initiated')
        ->where('created_at', '<', now()->subDays(2))->count();

    return [
        'total' => $total,
        'initiated' => $initiated,
        'approved' => $approved,
        'paid' => $paid,
        'rejected' => $rejected,
        'total_amount_initiated' => $totalAmountInitiated,
        'total_amount_approved' => $totalAmountApproved,
        'overdue_initiated' => $overdueInitiated,
    ];
}


private function exportManagerData($query)
{
    $payments = $query->get();

    $headers = [
        'Content-Type' => 'application/vnd.ms-excel',
        'Content-Disposition' => 'attachment; filename="manager_payments_' . date('Y-m-d_H-i-s') . '.xls"'
    ];

    $content = '<table border="1">';
    $content .= '<tr>';
    $content .= '<th>S/N</th>';
    $content .= '<th>Date Initiated</th>';
    $content .= '<th>Check Number</th>';
    $content .= '<th>Full Name</th>';
    $content .= '<th>Force Number</th>';
    $content .= '<th>Phone</th>';
    $content .= '<th>Bank Name</th>';
    $content .= '<th>Account Number</th>';
    $content .= '<th>Type</th>';
    $content .= '<th>Amount (Tsh)</th>';
    $content .= '<th>Region</th>';
    $content .= '<th>District</th>';
    $content .= '<th>Branch</th>';
    $content .= '<th>Registered By</th>';
    $content .= '<th>Initiated By</th>';
    $content .= '<th>Status</th>';
    $content .= '</tr>';

    foreach ($payments as $index => $payment) {
        $content .= '<tr>';
        $content .= '<td>' . ($index + 1) . '</td>';
        $content .= '<td>' . $payment->created_at->format('Y-m-d H:i') . '</td>';
        $content .= '<td>' . $payment->enquiry->check_number . '</td>';
        $content .= '<td>' . ucwords($payment->enquiry->full_name) . '</td>';
        $content .= '<td>' . ($payment->enquiry->force_no ?? 'N/A') . '</td>';
        $content .= '<td>' . ($payment->enquiry->phone ?? 'N/A') . '</td>';
        $content .= '<td>' . strtoupper($payment->enquiry->bank_name ?? 'N/A') . '</td>';
        $content .= '<td>' . ($payment->enquiry->account_number ?? 'N/A') . '</td>';
        $content .= '<td>' . ucfirst(str_replace('_', ' ', $payment->enquiry->type)) . '</td>';
        $content .= '<td>' . number_format($payment->amount) . '</td>';
        $content .= '<td>' . ($payment->enquiry->region->name ?? 'N/A') . '</td>';
        $content .= '<td>' . ($payment->enquiry->district->name ?? 'N/A') . '</td>';
        $content .= '<td>' . ($payment->enquiry->branch->name ?? 'N/A') . '</td>';
        $content .= '<td>' . ($payment->enquiry->registeredBy->name ?? 'N/A') . '</td>';
        $content .= '<td>' . ($payment->initiatedBy->name ?? 'N/A') . '</td>';
        $content .= '<td>' . ucfirst($payment->status) . '</td>';
        $content .= '</tr>';
    }
    $content .= '</table>';

    return response($content, 200, $headers);
}

public function bulkApprove(Request $request)
{
    $request->validate([
        'payment_ids' => 'required|array',
        'otp' => 'required|string|size:6'
    ]);

    // Verify OTP for bulk approve
    $user = auth()->user();

    // For demo purposes, we'll assume OTP is valid if it matches a pattern
    // In production, you'd verify against stored OTP
    if (!$this->verifyBulkOTP($request->otp)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid OTP code'
        ], 400);
    }

    DB::beginTransaction();

    try {
        $payments = Payment::whereIn('id', $request->payment_ids)
            ->where('status', 'initiated')
            ->get();

        $approvedCount = 0;

        foreach ($payments as $payment) {
            $payment->update([
                'status' => 'approved',
                'approved_by' => auth()->id()
            ]);

            $payment->logs()->create([
                'approved_by' => auth()->id()
            ]);

            // Update enquiry status
            $payment->enquiry->update(['status' => 'approved']);

            // Send SMS notification
            $message = "Hello {$payment->enquiry->full_name}, your payment of Tsh " . number_format($payment->amount) . " has been approved. For more information, contact 0677 026301";
            $this->sendEnquiryapproveSMS($payment->enquiry->phone, $message);

            $approvedCount++;
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => "Successfully approved {$approvedCount} payments"
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'success' => false,
            'message' => 'Error processing bulk approval: ' . $e->getMessage()
        ], 500);
    }
}

public function sendBulkOTP(Request $request)
{
    $otp = rand(100000, 999999);

    // Store OTP in session for verification
    session(['bulk_otp' => $otp, 'bulk_otp_expires' => now()->addMinutes(10)]);

    // Send OTP to manager's phone
    $message = "Your OTP for bulk payment approval is: {$otp}. Valid for 10 minutes.";
    $this->sendEnquiryapproveSMS(auth()->user()->phone_number, $message);

    return response()->json([
        'success' => true,
        'message' => 'OTP sent to your registered phone number'
    ]);
}

private function verifyBulkOTP($inputOtp)
{
    $storedOtp = session('bulk_otp');
    $expiresAt = session('bulk_otp_expires');

    if (!$storedOtp || !$expiresAt || now()->greaterThan($expiresAt)) {
        return false;
    }

    return $storedOtp === $inputOtp;
}

public function managerBulkReject(Request $request)
{
    $request->validate([
        'payment_ids' => 'required|array',
        'reason' => 'required|string|min:10'
    ]);

    DB::beginTransaction();

    try {
        $payments = Payment::whereIn('id', $request->payment_ids)
            ->where('status', 'initiated')
            ->get();

        $rejectedCount = 0;

        foreach ($payments as $payment) {
            $payment->update([
                'status' => 'rejected',
                'rejected_by' => auth()->id(),
                'remarks' => $request->reason
            ]);

            $payment->logs()->create([
                'rejected_by' => auth()->id()
            ]);

            // Update enquiry status
            $payment->enquiry->update(['status' => 'rejected']);

            // Send SMS notification
            $message = "Hello {$payment->enquiry->full_name}, your payment request has been rejected by management. Reason: {$request->reason}. For more information, contact 0677 026301";
            $this->sendEnquirySMS($payment->enquiry->phone, $message);

            $rejectedCount++;
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => "Successfully rejected {$rejectedCount} payments"
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'success' => false,
            'message' => 'Error processing bulk rejection: ' . $e->getMessage()
        ], 500);
    }
}



public function showByType($type)
{
    $payments = Payment::with(['enquiry', 'initiatedBy', 'approvedBy', 'rejectedBy',  'paidBy'])
        ->whereHas('enquiry', function ($query) use ($type) {
            $query->where('type', $type);
        })
        ->orderBy('created_at', 'desc')
        ->get();

    $columnsToShow = ['initiated' => false, 'approved' => false, 'rejected' => false, 'paid' => false];

    foreach ($payments as $payment) {
        if ($payment->initiatedBy) $columnsToShow['initiated'] = true;
        if ($payment->approvedBy) $columnsToShow['approved'] = true;
        if ($payment->rejectedBy) $columnsToShow['rejected'] = true;

        if ($payment->paidBy) $columnsToShow['paid'] = true;
    }

    return view('payments.showByType', compact('payments', 'type', 'columnsToShow'));
}
public function showTimeline($paymentId)
{
    $payment = Payment::with(['logs.initiator', 'logs.approver', 'logs.payer', 'logs.rejector'])->findOrFail($paymentId);
    return view('payments.payment_timeline', compact('payment'));
}



public function initiate(Request $request, $enquiryId)
{
    $request->validate([
        'amount' => 'required|numeric',
        'note' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png|max:2048',
        'file_id' => 'nullable|exists:files,id'
    ]);

    $enquiry = Enquiry::findOrFail($enquiryId);

    DB::beginTransaction();

    try {
        $payment = Payment::create([
            'enquiry_id' => $enquiryId,
            'amount' => $request->amount,
            'status' => 'initiated',
            'payment_date' => now(),
            'initiated_by' => auth()->id(),
            'branch_id' => auth()->user()->branch_id,
        ]);
      

        if ($request->hasFile('note') && $request->file('note')->isValid()) {
            $file = $request->file('note');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $destinationPath = public_path('dokezo');

            // Ensure the directory exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Move the file to the public/dokezo directory
            $file->move($destinationPath, $fileName);
            $filePath = 'dokezo/' . $fileName;  // Path to be stored in the database

            $folio = $payment->folios()->create([
                'file_path' => $filePath,
                'description' => 'Payment Note',
                'file_id' => $request->file_id,
            ]);
        }

        $payment->logs()->create(['initiated_by' => auth()->id()]);

        $message = "Hello {$enquiry->full_name}, a payment of Tsh " . number_format($request->amount) . " has been initiated. For further information, please contact 0677 026301";
        $this->sendEnquirySMS($enquiry->phone, $message);

        DB::commit();

        return redirect()->route('enquiries.my')->with('success', 'Payment initiated successfully with note attached!');
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Failed to initiate payment: ' . $e->getMessage());
    }
}






private function sendEnquirySMS($phone, $message)
{
    $url = 'https://41.59.228.68:8082/api/v1/sendSMS';
    $apiKey = 'xYz123#';

    $client = new \GuzzleHttp\Client();
    try {
        $response = $client->request('POST', $url, [
            'verify' => false,  // Keep SSL verification disabled as in your working script
            'form_params' => [
                'msisdn' => $phone,
                'message' => $message,
                'key' => $apiKey,
            ]
        ]);

        $responseBody = $response->getBody()->getContents();
        \Log::info("SMS sent response: " . $responseBody);
        return $responseBody;
    } catch (\GuzzleHttp\Exception\GuzzleException $e) {
        \Log::error("Failed to send SMS: " . $e->getMessage());
        return null;
    }
}







private function sendEnquiryapproveSMS($phone, $message)
{
    $url = 'https://41.59.228.68:8082/api/v1/sendSMS';
    $apiKey = 'xYz123#';

    $client = new \GuzzleHttp\Client();
    try {
        $response = $client->request('POST', $url, [
            'verify' => false,  // Keep SSL verification disabled as in your working script
            'form_params' => [
                'msisdn' => $phone,
                'message' => $message,
                'key' => $apiKey,
            ]
        ]);

        $responseBody = $response->getBody()->getContents();
        \Log::info("SMS sent response: " . $responseBody);
        return $responseBody;
    } catch (\GuzzleHttp\Exception\GuzzleException $e) {
        \Log::error("Failed to send SMS: " . $e->getMessage());
        return null;
    }
}

public function sendOtpApprove($paymentId)
{
    $payment = Payment::findOrFail($paymentId);
    $otp = rand(100000, 999999);  // Generate a 6-digit OTP
    $payment->otp = $otp;
    $payment->otp_expires_at = now()->addMinutes(10);
    $payment->save();

    // Logic to send OTP via SMS
    $this->sendEnquiryapproveSMS(auth()->user()->phone_number, "Your OTP for payment approval is: $otp");

    return response()->json(['success' => true, 'message' => 'OTP has been sent.']);
}



public function verifyOtpApprove(Request $request, $paymentId)
{
    $payment = Payment::findOrFail($paymentId);
    $inputOtp = $request->input('otp');

    if ($payment->otp === $inputOtp && now()->lessThan($payment->otp_expires_at)) {
        // $payment->status = 'approved';
        // $payment->save();
        // return response()->json(['success' => true, 'message' => 'OTP verified successfully']);
        $payment->status = 'approved';
        $payment->approved_by = auth()->id();  // Set who is approving this payment
        $payment->save();
        $payment->logs()->create([
            'approved_by' => auth()->id()
        ]);

        // Retrieve the associated enquiry to get the member's phone number and payment details
        $enquiry = $payment->enquiry;

        // Prepare the SMS message
        $message = "Hello " . $enquiry->full_name . ", your payment of Tsh" . number_format($payment->amount) . " has been approved. The transaction details and next steps will be communicated to you shortly. For further information, please contact 0677 026301.";

        // Send the SMS
        $this->sendEnquiryapproveSMS($enquiry->phone, $message);

        return response()->json(['success' => true, 'message' => 'OTP verified and payment approved successfully. SMS notification sent.']);
    } else {
        return response()->json(['success' => false, 'message' => 'Invalid or expired OTP'], 400);
    }
}



//     public function pay($paymentId)
// {
//     $payment = Payment::findOrFail($paymentId);
//     if ($payment->status === 'approved') {
//         $payment->status = 'paid';
//         $payment->paid_by = auth()->id();
//         $payment->payment_date = now();
//         $payment->save();

//         $payment->logs()->create([
//             'paid_by' => auth()->id()
//         ]);

//         // Retrieve the associated enquiry to get the member's phone number and payment details
//         $enquiry = $payment->enquiry;

//         // Prepare the SMS message
//         $message = "Hello " . $enquiry->full_name . ", your payment of " . $payment->amount . " has been successfully completed. Thank you for your prompt response. Please check your account for confirmation.";

//         // Send the SMS
//         $this->sendEnquirypaidSMS($enquiry->phone, $message);

//         return redirect()->route('enquiries.my')->with('success', 'Payment completed successfully and the member has been notified!');
//     }

//     return redirect()->route('enquiries.my')->with('error', 'Payment must be approved before paying.');
// }

// private function sendEnquirypaidSMS($phone, $message)
// {
//     $url = 'https://41.59.228.68:8082/api/v1/sendSMS';
//     $apiKey = 'xYz123#';

//     $client = new \GuzzleHttp\Client();
//     try {
//         $response = $client->request('POST', $url, [
//             'verify' => false,  // Keep SSL verification disabled as in your working script
//             'form_params' => [
//                 'msisdn' => $phone,
//                 'message' => $message,
//                 'key' => $apiKey,
//             ]
//         ]);

//         $responseBody = $response->getBody()->getContents();
//         \Log::info("SMS sent response: " . $responseBody);
//         return $responseBody;
//     } catch (\GuzzleHttp\Exception\GuzzleException $e) {
//         \Log::error("Failed to send SMS: " . $e->getMessage());
//         return null;
//     }
// }

public function sendOtpPay($paymentId)
{
    $payment = Payment::findOrFail($paymentId);
    $otp = rand(100000, 999999);  // Generate a random 6-digit OTP
    $payment->otp = $otp;
    $payment->otp_expires_at = now()->addMinutes(10); // Set the OTP to expire in 10 minutes
    $payment->save();

    // Logic to send the OTP via SMS

    $this->sendEnquiryapproveSMS(auth()->user()->phone_number, "Your OTP for payment verification is: $otp");

    return response()->json(['success' => true, 'message' => 'OTP has been sent.']);
}




public function verifyOtpPay(Request $request, $paymentId)
{
    $payment = Payment::findOrFail($paymentId);
    $inputOtp = $request->input('otp');

    if ($payment->otp === $inputOtp && now()->lessThan($payment->otp_expires_at)) {
        $payment->status = 'paid';
        $payment->approved_by = auth()->id();  // Set who is approving this payment
        $payment->save();
        $payment->logs()->create([
            'approved_by' => auth()->id()
        ]);

        // Retrieve the associated enquiry to get the member's phone number and payment details
        $enquiry = $payment->enquiry;

        // Prepare the SMS message
        $message = "Hello " . $enquiry->full_name . ", your payment of Tsh " . number_format( $payment->amount) . " has been paid. The transaction details and next steps will be communicated to you shortly. For further information, please contact 0677 026301.";

        // Send the SMS
        $this->sendEnquiryapproveSMS($enquiry->phone, $message);

        return response()->json(['success' => true, 'message' => 'OTP verified and payment approved successfully. SMS notification sent.']);
    }

    return response()->json(['success' => false, 'message' => 'Invalid or expired OTP']);
}




    public function reject(Request $request, $paymentId)
    {


        $payment = Payment::findOrFail($paymentId);
        $payment->status = 'rejected';
        $payment->rejected_by = auth()->id();
        $payment->remarks = $request->remarks;
        $payment->save();
        $payment->logs()->create([
            'rejected_by' => auth()->id()
        ]);

        return redirect()->route('payment.accountant.dashboard')->with('success', 'Payment rejected successfully.');
    }

    /**
     * Send OTP for loan application approval
     */
    public function sendLoanOTP($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $otp = rand(100000, 999999);
        $payment->otp = $otp;
        $payment->otp_expires_at = now()->addMinutes(10);
        $payment->save();

        // Send OTP via SMS
        $this->sendEnquiryapproveSMS(auth()->user()->phone_number, "Your OTP for loan application approval is: $otp");

        return response()->json(['success' => true, 'message' => 'OTP has been sent.']);
    }

    /**
     * Verify OTP and approve loan application
     */
    public function verifyLoanOTP(Request $request, $paymentId)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
            'enquiry_id' => 'required|exists:enquiries,id'
        ]);

        $payment = Payment::findOrFail($paymentId);
        $inputOtp = $request->input('otp');

        if ($payment->otp !== $inputOtp || now()->greaterThan($payment->otp_expires_at)) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP'], 400);
        }

        DB::beginTransaction();

        try {
            $enquiry = Enquiry::findOrFail($request->enquiry_id);

            // Find the loan application
            $loanApplication = $enquiry->loanApplication;

            if (!$loanApplication) {
                return response()->json([
                    'success' => false,
                    'message' => 'No loan application found for this enquiry'
                ], 404);
            }

            // Update loan application status
            $loanApplication->update(['status' => 'approved']);

            // Update payment status
            $payment->update([
                'status' => 'approved',
                'approved_by' => auth()->id()
            ]);

            // Update enquiry status
            $enquiry->update(['status' => 'approved']);

            // Create payment log
            $payment->logs()->create([
                'approved_by' => auth()->id()
            ]);

            // Send SMS notification
            $message = "Hello {$enquiry->full_name}, your loan application has been approved. For more information, contact 0677 026301";
            $this->sendEnquiryapproveSMS($enquiry->phone, $message);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'OTP verified and loan application approved successfully. SMS notification sent.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error approving loan application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject loan application from manager dashboard
     */
    public function rejectLoanApplication(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'enquiry_id' => 'required|exists:enquiries,id',
            'reason' => 'required|string|min:10'
        ]);

        DB::beginTransaction();

        try {
            $payment = Payment::findOrFail($request->payment_id);
            $enquiry = Enquiry::findOrFail($request->enquiry_id);

            // Find the loan application
            $loanApplication = $enquiry->loanApplication;

            if (!$loanApplication) {
                return response()->json([
                    'success' => false,
                    'message' => 'No loan application found for this enquiry'
                ], 404);
            }

            // Update loan application status
            $loanApplication->update(['status' => 'rejected']);

            // Update payment status
            $payment->update([
                'status' => 'rejected',
                'rejected_by' => auth()->id(),
                'remarks' => $request->reason
            ]);

            // Update enquiry status
            $enquiry->update(['status' => 'rejected']);

            // Create payment log
            $payment->logs()->create([
                'rejected_by' => auth()->id()
            ]);

            // Send SMS notification
            $message = "Hello {$enquiry->full_name}, your loan application has been rejected. Reason: {$request->reason}. For more information, contact 0677 026301";
            $this->sendEnquirySMS($enquiry->phone, $message);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Loan application rejected successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting loan application: ' . $e->getMessage()
            ], 500);
        }
    }
}

