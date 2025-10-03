<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use App\Models\Condolence;
use App\Models\Deduction;
use App\Models\Injury;
use App\Models\LoanApplication;
use App\Models\Membership;
use App\Models\MembershipChange;
use App\Models\Refund;
use App\Models\ResidentialDisaster;
use App\Models\Retirement;
use App\Models\Share;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Add this line
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $benefitCount = Benefit::count();
        $condolenceCount = Condolence::count();
        $deductionCount = Deduction::count();
        $injuryCount = Injury::count();
        $loanApplicationCount = LoanApplication::count();
        $membershipCount = Membership::count();
        $membershipChangeCount = MembershipChange::count();
        $refundCount = Refund::count();
        $residentialDisasterCount = ResidentialDisaster::count();
        $retirementCount = Retirement::count();
        $shareCount = Share::count();
        $withdrawalCount = Withdrawal::count();

        // Loan category frequency
        $loanCategoryData = LoanApplication::selectRaw('loan_type, COUNT(*) as count')
            ->groupBy('loan_type')
            ->pluck('count', 'loan_type');

        // Loan application count by month for the current year
        $monthlyLoanData = LoanApplication::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('count', 'month');

        // Deduction Data for Donut Chart
        $deductionData = Deduction::selectRaw('SUM(from_amount) as from_sum, SUM(to_amount) as to_sum')
            ->first();

        $fromAmount = $deductionData->from_sum ?? 0; // Handle potential null values
        $toAmount = $deductionData->to_sum ?? 0;

        $totalDeductions = $fromAmount + $toAmount;

        $fromPercentage = $totalDeductions > 0 ? ($fromAmount / $totalDeductions) * 100 : 0;
        $toPercentage = $totalDeductions > 0 ? ($toAmount / $totalDeductions) * 100 : 0;



       // Get unique users based on checkNumber
$uniqueUsers = DB::table('deduction_details')
->select('checkNumber', 'firstName', 'middleName', 'lastName', 'nationalId', 'deptName')
->groupBy('checkNumber', 'firstName', 'middleName', 'lastName', 'nationalId', 'deptName')
->get();

// Contributions and URA members count (distinct checkNumber)
$contributionCount = DB::table('deduction_details')
->where('deductionCode', '667')
->selectRaw('COUNT(DISTINCT checkNumber) as total')
->value('total');

$uraMembersCount = DB::table('deduction_details')
->where('deductionCode', '667')
->selectRaw('COUNT(DISTINCT checkNumber) as total')
->value('total');

// Salary loans count (distinct checkNumber)
$salaryLoansCount = DB::table('deduction_details')
->whereIn('deductionCode', ['769'])
->selectRaw('COUNT(DISTINCT checkNumber) as total')
->value('total');

$salaryLoans769ACount = DB::table('deduction_details')
->whereIn('deductionCode', ['769A'])
->selectRaw('COUNT(DISTINCT checkNumber) as total')
->value('total');

// Sum of salary loans (ensuring distinct checkNumber)
$salaryLoansSum = DB::table('deduction_details')
->whereIn('deductionCode', ['769'])
->selectRaw('SUM(DISTINCT deductionAmount) as total')
->value('total');

$salaryLoans769ASum = DB::table('deduction_details')
->whereIn('deductionCode', ['769A'])
->selectRaw('SUM(DISTINCT deductionAmount) as total')
->value('total');

// Get count & sum grouped by deptName (Using DISTINCT checkNumber)
$deductionsByDept = DB::table('deduction_details')
->select(
    'deptName',
    DB::raw("COUNT(DISTINCT CASE WHEN deductionCode = '667' THEN checkNumber END) as contributionCount"),
    DB::raw("SUM(DISTINCT CASE WHEN deductionCode = '667' THEN deductionAmount END) as contributionSum"),
    DB::raw("COUNT(DISTINCT CASE WHEN deductionCode = '769' THEN checkNumber END) as salaryLoans769Count"),
    DB::raw("COUNT(DISTINCT CASE WHEN deductionCode = '769A' THEN checkNumber END) as salaryLoans769ACount"),
    DB::raw("SUM(DISTINCT CASE WHEN deductionCode = '769' THEN deductionAmount END) as salaryLoans769Sum"),
    DB::raw("SUM(DISTINCT CASE WHEN deductionCode = '769A' THEN deductionAmount END) as salaryLoans769ASum")
)
->groupBy('deptName')
->orderByDesc('contributionCount')
->get();

// Calculate grand totals (Using DISTINCT checkNumber)
$grandTotalContributions = $deductionsByDept->sum('contributionCount');
$grandTotalSalaryLoans769Count = $deductionsByDept->sum('salaryLoans769Count');
$grandTotalSalaryLoans769ACount = $deductionsByDept->sum('salaryLoans769ACount');
$grandTotalSalaryLoans769Sum = $deductionsByDept->sum('salaryLoans769Sum');
$grandTotalSalaryLoans769ASum = $deductionsByDept->sum('salaryLoans769ASum');

        return view('dashboard', compact(
            'benefitCount',
            'condolenceCount',
            'deductionCount',
            'injuryCount',
            'loanApplicationCount',
            'membershipCount',
            'membershipChangeCount',
            'refundCount',
            'residentialDisasterCount',
            'retirementCount',
            'shareCount',
            'withdrawalCount',
            'loanCategoryData',
            'monthlyLoanData',
            'fromAmount',
            'toAmount',
            'fromPercentage',
            'toPercentage',

            'uniqueUsers', 'contributionCount', 'uraMembersCount', 
            'salaryLoansCount', 'salaryLoans769ACount', 
            'salaryLoansSum', 'salaryLoans769ASum', 'deductionsByDept',

            'grandTotalContributions', 'grandTotalSalaryLoans769Count', 'grandTotalSalaryLoans769ACount',
    'grandTotalSalaryLoans769Sum', 'grandTotalSalaryLoans769ASum'
        ));
    }
}