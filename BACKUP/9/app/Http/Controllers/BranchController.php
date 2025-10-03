<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\District;
use App\Models\Region;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        $districts = District::all(); // Get all districts
        $regions = Region::all(); // Get all regions
        return view('branches.index', compact('branches', 'districts', 'regions'));
    }

    public function create()
    {
        $districts = District::all();
        $regions = Region::all();
        return view('branches.create', compact('districts', 'regions'));
    }

    public function store(Request $request)
    {
        // Validate that all fields are required
        $request->validate([
            'name' => 'required|string|max:255|unique:branches,name', // Ensure 'name' is unique
            'district_id' => 'required|exists:districts,id',
            'region_id' => 'required|exists:regions,id',
        ]);

        // Check if the branch name already exists
        $existingBranch = Branch::where('name', $request->name)->first();
        if ($existingBranch) {
            return redirect()->route('branches.index')->with('error', 'Branch name already exists!');
        }

        // Create the new branch
        Branch::create($request->all());
        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function show(Branch $branch)
    {
        return view('branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        $districts = District::all();
        $regions = Region::all();
        return view('branches.edit', compact('branch', 'districts', 'regions'));
    }

    public function update(Request $request, Branch $branch)
    {
        // Validate that all fields are required
        $request->validate([
            'name' => 'required|string|max:255|unique:branches,name,' . $branch->id, // Ensure 'name' is unique, excluding the current branch
            'district_id' => 'required|exists:districts,id',
            'region_id' => 'required|exists:regions,id',
        ]);

        // Check if the branch name already exists (excluding the current branch)
        $existingBranch = Branch::where('name', $request->name)->where('id', '!=', $branch->id)->first();
        if ($existingBranch) {
            return redirect()->route('branches.index')->with('error', 'Branch name already exists!');
        }

        // Update the existing branch
        $branch->update($request->all());
        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }

    /**
     * Branch Manager Dashboard
     * Display analytics and enquiries for the branch manager's branch
     */
    public function managerDashboard(Request $request)
    {
        $user = auth()->user();

        // Verify user is a branch manager and has a branch
        if (!$user->hasRole('branch_manager') || !$user->branch_id) {
            return redirect()->route('dashboard')
                ->with('error', 'Access denied. You must be assigned to a branch.');
        }

        $branch = $user->branch;

        // Get all users who registered enquiries from this branch
        $branchUserIds = \App\Models\User::where('branch_id', $branch->id)->pluck('id');

        // Get all unique region IDs from enquiries registered by branch users
        $regionIdsInBranch = \App\Models\Enquiry::whereIn('registered_by', $branchUserIds)
            ->whereNotNull('region_id')
            ->distinct()
            ->pluck('region_id');

        // Get all regions that have enquiries from this branch
        $regionsInBranch = \App\Models\Region::whereIn('id', $regionIdsInBranch)->get();

        // Get all unique district IDs from enquiries registered by branch users
        $districtIdsInBranch = \App\Models\Enquiry::whereIn('registered_by', $branchUserIds)
            ->whereNotNull('district_id')
            ->distinct()
            ->pluck('district_id');

        // If region is selected, filter districts by that region
        if ($request->filled('region_id')) {
            $districtsInBranch = \App\Models\District::where('region_id', $request->region_id)
                ->whereIn('id', $districtIdsInBranch)
                ->get();
        } else {
            $districtsInBranch = \App\Models\District::whereIn('id', $districtIdsInBranch)->get();
        }

        // Build base query for enquiries from this branch
        $enquiriesQuery = \App\Models\Enquiry::whereIn('registered_by', $branchUserIds);

        // Apply filters
        if ($request->filled('region_id')) {
            $enquiriesQuery->where('region_id', $request->region_id);
        }

        if ($request->filled('district_id')) {
            $enquiriesQuery->where('district_id', $request->district_id);
        }

        if ($request->filled('type')) {
            $enquiriesQuery->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $enquiriesQuery->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $enquiriesQuery->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $enquiriesQuery->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $enquiriesQuery->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('check_number', 'like', "%{$search}%")
                  ->orWhere('force_no', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Handle exports
        if ($request->has('export')) {
            if ($request->export === 'excel_general') {
                // General report - export all without filters
                $generalRequest = new Request();
                return $this->exportBranchManagerData($generalRequest);
            } elseif ($request->export === 'excel') {
                // Custom report with filters
                return $this->exportBranchManagerData($request);
            } elseif ($request->export === 'pdf') {
                // PDF report with filters
                return $this->exportBranchManagerPDF($request);
            }
        }

        // Get enquiries with pagination
        $perPage = $request->get('per_page', 15);
        $enquiries = $enquiriesQuery->with(['district', 'region', 'users', 'registeredBy'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Calculate analytics for the entire branch (unfiltered)
        $analytics = [
            'total' => \App\Models\Enquiry::whereIn('registered_by', $branchUserIds)->count(),
            'pending' => \App\Models\Enquiry::whereIn('registered_by', $branchUserIds)->where('status', 'pending')->count(),
            'assigned' => \App\Models\Enquiry::whereIn('registered_by', $branchUserIds)->where('status', 'assigned')->count(),
            'approved' => \App\Models\Enquiry::whereIn('registered_by', $branchUserIds)->where('status', 'approved')->count(),
            'rejected' => \App\Models\Enquiry::whereIn('registered_by', $branchUserIds)->where('status', 'rejected')->count(),
            'pending_overdue' => \App\Models\Enquiry::whereIn('registered_by', $branchUserIds)
                ->where('status', 'pending')
                ->where('created_at', '<=', now()->subDays(3))
                ->count(),
        ];

        // Calculate analytics for CURRENT FILTERED data (intensive analytics)
        $filteredEnquiriesForAnalytics = clone $enquiriesQuery;
        $filteredAnalytics = [
            'total' => $filteredEnquiriesForAnalytics->count(),
            'pending' => (clone $filteredEnquiriesForAnalytics)->where('status', 'pending')->count(),
            'assigned' => (clone $filteredEnquiriesForAnalytics)->where('status', 'assigned')->count(),
            'approved' => (clone $filteredEnquiriesForAnalytics)->where('status', 'approved')->count(),
            'rejected' => (clone $filteredEnquiriesForAnalytics)->where('status', 'rejected')->count(),
            'pending_overdue' => (clone $filteredEnquiriesForAnalytics)->where('status', 'pending')
                ->where('created_at', '<=', now()->subDays(3))
                ->count(),
        ];

        // Analytics by enquiry type
        $analyticsByType = \App\Models\Enquiry::whereIn('registered_by', $branchUserIds)
            ->selectRaw('type, COUNT(*) as count, status')
            ->groupBy('type', 'status')
            ->get()
            ->groupBy('type');

        // Analytics by region
        $analyticsByRegion = \App\Models\Enquiry::whereIn('registered_by', $branchUserIds)
            ->selectRaw('region_id, COUNT(*) as count, status')
            ->groupBy('region_id', 'status')
            ->get()
            ->groupBy('region_id');

        // Analytics by district
        $analyticsByDistrict = \App\Models\Enquiry::whereIn('registered_by', $branchUserIds)
            ->selectRaw('district_id, COUNT(*) as count, status')
            ->groupBy('district_id', 'status')
            ->get()
            ->groupBy('district_id');

        // Child table statistics
        $childTableStats = $this->getChildTableStatistics($branchUserIds);

        // Get all enquiry types
        $enquiryTypes = [
            'loan_application', 'refund', 'share_enquiry', 'retirement',
            'deduction_add', 'withdraw_savings', 'withdraw_deposit',
            'unjoin_membership', 'condolences', 'injured_at_work',
            'sick_for_30_days', 'benefit_from_disasters', 'join_membership', 'ura_mobile'
        ];

        return view('branches.manager.index', compact(
            'branch',
            'enquiries',
            'analytics',
            'filteredAnalytics',
            'analyticsByType',
            'analyticsByRegion',
            'analyticsByDistrict',
            'childTableStats',
            'districtsInBranch',
            'regionsInBranch',
            'enquiryTypes'
        ));
    }

    /**
     * Get districts by region for branch manager (AJAX)
     */
    public function getDistrictsByRegion(Request $request)
    {
        $user = auth()->user();

        if (!$user->hasRole('branch_manager') || !$user->branch_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $regionId = $request->get('region_id');
        $branchUserIds = \App\Models\User::where('branch_id', $user->branch_id)->pluck('id');

        // Get districts in this region that have enquiries from branch users
        $districtIdsInBranch = \App\Models\Enquiry::whereIn('registered_by', $branchUserIds)
            ->whereNotNull('district_id')
            ->distinct()
            ->pluck('district_id');

        $districts = \App\Models\District::where('region_id', $regionId)
            ->whereIn('id', $districtIdsInBranch)
            ->get(['id', 'name']);

        return response()->json($districts);
    }

    /**
     * Get region-specific analytics and export
     */
    public function regionAnalytics(Request $request, $regionId)
    {
        $user = auth()->user();

        if (!$user->hasRole('branch_manager') || !$user->branch_id) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $branch = $user->branch;
        $branchUserIds = \App\Models\User::where('branch_id', $branch->id)->pluck('id');

        $region = \App\Models\Region::findOrFail($regionId);

        // Get enquiries for this region only
        $enquiries = \App\Models\Enquiry::whereIn('registered_by', $branchUserIds)
            ->where('region_id', $regionId)
            ->with(['district', 'region', 'users', 'registeredBy'])
            ->get();

        $analytics = [
            'total' => $enquiries->count(),
            'pending' => $enquiries->where('status', 'pending')->count(),
            'assigned' => $enquiries->where('status', 'assigned')->count(),
            'approved' => $enquiries->where('status', 'approved')->count(),
        ];

        return view('branches.manager.region_view', compact('branch', 'region', 'enquiries', 'analytics'));
    }

    /**
     * Get district-specific analytics and export
     */
    public function districtAnalytics(Request $request, $districtId)
    {
        $user = auth()->user();

        if (!$user->hasRole('branch_manager') || !$user->branch_id) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $branch = $user->branch;
        $branchUserIds = \App\Models\User::where('branch_id', $branch->id)->pluck('id');

        $district = \App\Models\District::findOrFail($districtId);

        // Get enquiries for this district only
        $enquiries = \App\Models\Enquiry::whereIn('registered_by', $branchUserIds)
            ->where('district_id', $districtId)
            ->with(['district', 'region', 'users', 'registeredBy'])
            ->get();

        $analytics = [
            'total' => $enquiries->count(),
            'pending' => $enquiries->where('status', 'pending')->count(),
            'assigned' => $enquiries->where('status', 'assigned')->count(),
            'approved' => $enquiries->where('status', 'approved')->count(),
        ];

        return view('branches.manager.district_view', compact('branch', 'district', 'enquiries', 'analytics'));
    }

    /**
     * Get statistics from child tables for branch enquiries
     */
    private function getChildTableStatistics($branchUserIds)
    {
        $stats = [];

        // Get all enquiry IDs from this branch
        $enquiryIds = \App\Models\Enquiry::whereIn('registered_by', $branchUserIds)->pluck('id');

        // Loan Applications
        $stats['loan_applications'] = [
            'total' => \App\Models\LoanApplication::whereIn('enquiry_id', $enquiryIds)->count(),
            'total_amount' => \App\Models\LoanApplication::whereIn('enquiry_id', $enquiryIds)->sum('loan_amount'),
            'by_type' => \App\Models\LoanApplication::whereIn('enquiry_id', $enquiryIds)
                ->selectRaw('loan_type, COUNT(*) as count, SUM(loan_amount) as total')
                ->groupBy('loan_type')
                ->get(),
        ];

        // Payments
        $stats['payments'] = [
            'total' => \App\Models\Payment::whereIn('enquiry_id', $enquiryIds)->count(),
            'total_amount' => \App\Models\Payment::whereIn('enquiry_id', $enquiryIds)->sum('amount'),
            'by_status' => \App\Models\Payment::whereIn('enquiry_id', $enquiryIds)
                ->selectRaw('status, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('status')
                ->get(),
        ];

        // Refunds
        $stats['refunds'] = [
            'total' => \App\Models\Refund::whereIn('enquiry_id', $enquiryIds)->count(),
            'total_amount' => \App\Models\Refund::whereIn('enquiry_id', $enquiryIds)->sum('refund_amount'),
        ];

        // Withdrawals
        $stats['withdrawals'] = [
            'total' => \App\Models\Withdrawal::whereIn('enquiry_id', $enquiryIds)->count(),
            'total_amount' => \App\Models\Withdrawal::whereIn('enquiry_id', $enquiryIds)->sum('amount'),
        ];

        // Retirements
        $stats['retirements'] = [
            'total' => \App\Models\Retirement::whereIn('enquiry_id', $enquiryIds)->count(),
        ];

        // Shares
        $stats['shares'] = [
            'total' => \App\Models\Share::whereIn('enquiry_id', $enquiryIds)->count(),
            'total_amount' => \App\Models\Share::whereIn('enquiry_id', $enquiryIds)->sum('share_amount'),
        ];

        // Condolences
        $stats['condolences'] = [
            'total' => \App\Models\Condolence::whereIn('enquiry_id', $enquiryIds)->count(),
        ];

        // Injuries
        $stats['injuries'] = [
            'total' => \App\Models\Injury::whereIn('enquiry_id', $enquiryIds)->count(),
        ];

        // Sick Leaves
        $stats['sick_leaves'] = [
            'total' => \App\Models\SickLeave::whereIn('enquiry_id', $enquiryIds)->count(),
        ];

        // Benefits
        $stats['benefits'] = [
            'total' => \App\Models\Benefit::whereIn('enquiry_id', $enquiryIds)->count(),
        ];

        // Membership Changes
        $stats['membership_changes'] = [
            'total' => \App\Models\MembershipChange::whereIn('enquiry_id', $enquiryIds)->count(),
            'joins' => \App\Models\MembershipChange::whereIn('enquiry_id', $enquiryIds)->where('action', 'join')->count(),
            'unjoins' => \App\Models\MembershipChange::whereIn('enquiry_id', $enquiryIds)->where('action', 'unjoin')->count(),
        ];

        // Deductions
        $stats['deductions'] = [
            'total' => \App\Models\Deduction::whereIn('enquiry_id', $enquiryIds)->count(),
        ];

        // URA Mobile
        $stats['ura_mobile'] = [
            'total' => \App\Models\URAMobile::whereIn('enquiry_id', $enquiryIds)->count(),
        ];

        // Residential Disasters
        $stats['residential_disasters'] = [
            'total' => \App\Models\ResidentialDisaster::whereIn('enquiry_id', $enquiryIds)->count(),
        ];

        return $stats;
    }

    /**
     * Export Branch Manager Data to Excel with child table fields
     */
    public function exportBranchManagerData(Request $request)
    {
        $user = auth()->user();

        if (!$user->hasRole('branch_manager') || !$user->branch_id) {
            return redirect()->back()->with('error', 'Access denied.');
        }

        $branch = $user->branch;
        $branchUserIds = \App\Models\User::where('branch_id', $branch->id)->pluck('id');

        $enquiriesQuery = \App\Models\Enquiry::whereIn('registered_by', $branchUserIds);

        // Apply same filters as dashboard
        if ($request->filled('region_id')) {
            $enquiriesQuery->where('region_id', $request->region_id);
        }
        if ($request->filled('district_id')) {
            $enquiriesQuery->where('district_id', $request->district_id);
        }
        if ($request->filled('type')) {
            $enquiriesQuery->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $enquiriesQuery->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $enquiriesQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $enquiriesQuery->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $enquiriesQuery->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('check_number', 'like', "%{$search}%")
                  ->orWhere('force_no', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Load child relationships if type filter is applied
        $with = ['district', 'region', 'users', 'registeredBy', 'branch'];
        if ($request->filled('type')) {
            $childRelation = $this->getRelationshipForType($request->type);
            if ($childRelation) {
                $with[] = $childRelation;
            }
        }

        $enquiries = $enquiriesQuery->with($with)->get();
        $enquiryType = $request->get('type');

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="branch_manager_report_' . date('Y-m-d_H-i-s') . '.xls"'
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
            $content .= '</tr>';
        }
        $content .= '</table>';

        return response($content, 200, $headers);
    }

    /**
     * Export Branch Manager Data to PDF
     */
    public function exportBranchManagerPDF(Request $request)
    {
        $user = auth()->user();

        if (!$user->hasRole('branch_manager') || !$user->branch_id) {
            return redirect()->back()->with('error', 'Access denied.');
        }

        $branch = $user->branch;
        $branchUserIds = \App\Models\User::where('branch_id', $branch->id)->pluck('id');

        $enquiriesQuery = \App\Models\Enquiry::whereIn('registered_by', $branchUserIds);

        // Apply filters
        if ($request->filled('region_id')) {
            $enquiriesQuery->where('region_id', $request->region_id);
        }
        if ($request->filled('district_id')) {
            $enquiriesQuery->where('district_id', $request->district_id);
        }
        if ($request->filled('type')) {
            $enquiriesQuery->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $enquiriesQuery->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $enquiriesQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $enquiriesQuery->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $enquiriesQuery->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('check_number', 'like', "%{$search}%")
                  ->orWhere('force_no', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Load appropriate child table based on type filter
        $with = ['district', 'region', 'users', 'registeredBy'];
        if ($request->filled('type')) {
            $childRelation = $this->getRelationshipForExportType($request->type);
            if ($childRelation) {
                $with[] = $childRelation;
            }
        }

        $enquiries = $enquiriesQuery->with($with)->get();

        $analytics = [
            'total' => $enquiries->count(),
            'pending' => $enquiries->where('status', 'pending')->count(),
            'assigned' => $enquiries->where('status', 'assigned')->count(),
            'approved' => $enquiries->where('status', 'approved')->count(),
            'rejected' => $enquiries->where('status', 'rejected')->count(),
        ];

        $enquiryType = $request->get('type');

        $pdf = \PDF::loadView('branches.manager.pdf_report', compact('enquiries', 'branch', 'analytics', 'enquiryType'));

        return $pdf->download('branch_manager_report_' . date('Y-m-d') . '.pdf');
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
            'ura_mobile' => null,
        ];

        return $relationships[$type] ?? null;
    }

    /**
     * Get relationship name for export based on enquiry type (alias for PDF compatibility)
     */
    private function getRelationshipForExportType($type)
    {
        return $this->getRelationshipForType($type);
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
}
