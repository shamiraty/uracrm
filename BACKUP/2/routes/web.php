<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
//added
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanController;
//end added
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\LoanTrendController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FileSeriesController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RepresentativeController;
use App\Http\Controllers\DashboardTrendsController;
use App\Http\Controllers\MortgageCalculatorController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\EmployeeLoanController;
use App\Http\Controllers\DeductionApiController;
use App\Http\Controllers\NmbDisbursementController;
use App\Http\Controllers\ExistingLoanImportController;
use App\Exports\LoanOfficerApplicationsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\BulkSMSController;
use App\Http\Controllers\ContributionAnalysisController;
use App\Models\District;
use App\Http\Controllers\DeductionVarianceController;
use App\Http\Controllers\DeductionDifferencesController;
use App\Http\Controllers\CommandController;
use App\Http\Controllers\CardDetailManagerController;

// Protected Routes with comprehensive middleware
Route::middleware(['auth', 'user.status','update.activity', 'role.access'])->group(function () {


    // ============================================
    // USER ACTIVITY AND STATUS ROUTES
    // ============================================
//-----------------------------------------------	
/*
	ROLES:
	admin
	superadmin
    system_admin
	*/
    Route::post('/users/update-activity', [UserController::class, 'updateActivity'])
        ->name('users.update-activity');

    Route::get('/users/online-users', [UserController::class, 'getOnlineUsersHtml'])
        ->name('users.online-users');

    Route::post('/users/clear-online-status', [UserController::class, 'clearOnlineStatus'])
        ->name('users.clear-online-status');

    // Export and Analytics Routes
    Route::post('/users/export', [UserController::class, 'exportUsers'])
        ->name('users.export');
    Route::post('/users/export-range', [UserController::class, 'exportUsersRange'])
        ->name('users.export-range');
    Route::post('/users/export-quick', [UserController::class, 'exportUsersQuick'])
        ->name('users.export-quick');
    Route::post('/users/security-audit', [UserController::class, 'generateSecurityAudit'])
        ->name('users.security-audit');
    Route::post('/users/schedule-report', [UserController::class, 'scheduleReport'])
        ->name('users.schedule-report');
    Route::get('/users/analytics-data', [UserController::class, 'getAnalyticsData'])
        ->name('users.analytics-data');

//-----------------------------------------------	








    // ============================================
    // EXPORT ROUTES FOR VARIOUS ENQUIRY TYPES
    // ============================================
	/*
	ROLES:
admin
accountant
loanofficer
Registrar
superadmin
system_admin
public_relation_officer
registrar_hq
representative
general_manager
branch_manager
*/
    Route::get('/loan-applications/export', [EnquiryController::class, 'exportLoanApplication'])
        ->name('exportLoanApplication');
    Route::get('export-enquiries', [EnquiryController::class, 'exportMembershipChanges'])
        ->name('exportEnquiriesUnjoinMembership');
    Route::get('export-condolences', [EnquiryController::class, 'exportCondolences'])
        ->name('exportCondolences');
    Route::get('/deductions/export', [EnquiryController::class, 'export'])
        ->name('deductions.export');
    Route::get('/refunds/export', [EnquiryController::class, 'exportRefund'])
        ->name('exportRefund');
    Route::get('/residential-disasters/export', [EnquiryController::class, 'ResidentialDisasterExport'])
        ->name('residential_disasters');
    Route::get('/retirements/export', [EnquiryController::class, 'exportRetirement'])
        ->name('exportRetirement');
    Route::get('/shares/export', [EnquiryController::class, 'exportShare'])
        ->name('exportShare');
    Route::get('/sickleave/export', [EnquiryController::class, 'exportSickLeave'])
        ->name('exportSickLeave');
    Route::get('/withdrawal/export', [EnquiryController::class, 'WithdrawalExport'])
        ->name('withdrawalExport');
    Route::get('/injury/export', [EnquiryController::class, 'InjuryExport'])
        ->name('injuryExport');
    Route::get('/membership/export', [EnquiryController::class, 'JoinMembershipExport'])
        ->name('membershipExport');
    Route::get('/all-enquiries/export', [EnquiryController::class, 'allEnquiriesExport'])
        ->name('allEnquiriesExport');

    
	
	
	// ============================================
    // ENQUIRY MANAGEMENT ROUTES
    // ============================================
    Route::get('/enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
    // Route::get('/enquiries/create', [EnquiryController::class, 'create'])->name('enquiries.create');
	
	
	
	//registrar_hq
	//Registrar
    Route::get('/enquiries/create/{check_number?}', [EnquiryController::class, 'create'])
    ->name('enquiries.create');
    Route::get('/enquiries/fetch-payroll/{check_number}', [EnquiryController::class, 'fetchPayroll']);
    Route::post('/enquiries', [EnquiryController::class, 'store'])->name('enquiries.store');
	
//admin
//accountant
//loanofficer
//Registrar
//superadmin
//system_admin
//public_relation_officer
//registrar_hq
//representative
//general_manager
//branch_manager
    Route::get('/enquiries/{enquiry}', [EnquiryController::class, 'show'])->name('enquiries.show');
	
	//registrar_hq
	//Registrar
    Route::get('/enquiries/{enquiry}/edit', [EnquiryController::class, 'edit'])->name('enquiries.edit');
    Route::put('/enquiries/{enquiry}', [EnquiryController::class, 'update'])->name('enquiries.update');
    Route::delete('/enquiries/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');


    // ============================================
    // DASHBOARD ROUTES
    // ============================================
	
	//general_manager
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // ============================================
    // RESPONSE AND STATUS MANAGEMENT ROUTES
    // ============================================
	

//accountant
//loanofficer
//Registrar
//registrar_hq
//general_manager
//branch_manager
    Route::get('enquiries/{enquiry}/responses/create', [ResponseController::class, 'create'])->name('responses.create');
    Route::post('enquiries/{enquiry}/responses', [ResponseController::class, 'store'])->name('responses.store');
    Route::post('/enquiries/{enquiry}/change-status', [EnquiryController::class, 'changeStatus'])->name('enquiries.changeStatus');

    // ============================================
    // NOTIFICATION ROUTES
    // ============================================
	
	//admin
//accountant
//loanofficer
//Registrar
//superadmin
//system_admin
//public_relation_officer
//registrar_hq
//representative
//general_manager
//branch_manager
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markNotificationAsRead'])->name('notifications.read');

    // ============================================
    // MEMBER MANAGEMENT ROUTES
    // ============================================

	//admin
//accountant
//loanofficer
//Registrar
//superadmin
//system_admin
//public_relation_officer
//registrar_hq
//representative
//general_manager
//branch_manager	
    Route::get('members/{member}/details', [MemberController::class, 'showDetails'])->name('members.details');
    Route::post('/members/{member}/status/{status}', [MemberController::class, 'updateStatus'])->name('members.updateStatus');



//registrar_hq
    Route::post('/enquiries/{enquiry}/assign', [EnquiryController::class, 'assignUsersToEnquiry'])->name('enquiries.assign');
    Route::post('/enquiries/{enquiry}/reassign', [EnquiryController::class, 'reassignUsersToEnquiry'])->name('enquiries.reassign');
	
	//general_manager
	//accountant
//loanofficer
    Route::get('/my-enquiries', [EnquiryController::class, 'myAssignedEnquiries'])->name('enquiries.my');
	
	//loanofficer
    Route::get('/my-loan-applications', [LoanController::class, 'loanOfficerDashboard'])->name('loans.my');

    // ============================================
    // BULK OPERATIONS ROUTES
    // ============================================
	
	//registrar_hq
    Route::post('/enquiries/bulk-assign', [EnquiryController::class, 'bulkAssign'])->name('enquiries.bulk-assign');	
    Route::post('/enquiries/bulk-reassign', [EnquiryController::class, 'bulkReassign'])->name('enquiries.bulk-reassign');
    Route::post('/enquiries/bulk-delete', [EnquiryController::class, 'bulkDelete'])->name('enquiries.bulk-delete');

    // ============================================
    // USER AND ROLE MANAGEMENT ROUTES
    // ============================================
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);

    // Additional user management routes
	
	//superadmin
//system_admin
//admin
    Route::get('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])
        ->name('users.reset-password');
    Route::get('/users/bulk-operations', [UserController::class, 'bulkOperations'])
        ->name('users.bulk-operations');







    // ============================================
    // LOAN CALCULATION ROUTES
    // ============================================
	//loanofficer
    Route::post('/calculate-loan/{loanApplicationId}', [LoanController::class, 'calculateLoan'])->name('calculate.loan');
    // ============================================
    // PAYMENT MANAGEMENT ROUTES
    // ============================================
	
	//accountant
    Route::get('/enquiries/{enquiry}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
	
	//accountant
    Route::post('/enquiries/{enquiry}/payments', [PaymentController::class, 'store'])->name('payments.store');
	
	//accountant
    Route::post('/payment/initiate/{enquiryId}', [PaymentController::class, 'initiate'])->name('payment.initiate');
	
	//general_manager
    Route::post('/payment/approve/{paymentId}', [PaymentController::class, 'approve'])->name('payment.approve');
	
	//accountant
    Route::post('/payment/pay/{paymentId}', [PaymentController::class, 'pay'])->name('payment.pay');
	
	//general_manager
	//accountant
    Route::match(['POST', 'PUT'], '/payment/reject/{paymentId}', [PaymentController::class, 'reject'])->name('payment.reject');
	
	//general_manager
	//accountant
    Route::get('/payments/{paymentId}/timeline', [PaymentController::class, 'showTimeline'])->name('payments.timeline');
	
	//general_manager
	//accountant
    Route::get('/payments/type/{type}', [PaymentController::class, 'showByType'])->name('payments.type');



    // ============================================
    // OTP VERIFICATION ROUTES
    // ============================================
	//general_manager
    Route::post('/send-otp-approve/{paymentId}', [PaymentController::class, 'sendOtpApprove'])->name('send.otp.approve');
    Route::post('/verify-otp-approve/{paymentId}', [PaymentController::class, 'verifyOtpApprove'])->name('verify.otp.approve');

    // Payment Dashboard Routes
	//accountant
    Route::get('/payment/accountant/dashboard', [PaymentController::class, 'accountantDashboard'])->name('payment.accountant.dashboard');
	
	//general_manager
	//accountant
    Route::get('/payment/manager/dashboard', [PaymentController::class, 'managerDashboard'])->name('payment.manager.dashboard');

    // Single Payment OTP Routes
	//general_manager
	//accountant
    Route::post('/payment/send-otp-approve/{paymentId}', [PaymentController::class, 'sendOtpApprove'])->name('payment.send.otp.approve');
    Route::post('/payment/verify-otp-approve/{paymentId}', [PaymentController::class, 'verifyOtpApprove'])->name('payment.verify.otp.approve');
    Route::post('/payment/{paymentId}/send-otp-pay', [PaymentController::class, 'sendOtpPay'])->name('send.otp.pay');
    Route::post('/payment/{paymentId}/verify-otp-pay', [PaymentController::class, 'verifyOtpPay'])->name('verify.otp.pay');

   //general_manager
	//accountant
    Route::post('/payment/bulk-reject', [PaymentController::class, 'bulkReject'])->name('payment.bulk.reject');
    Route::post('/payment/manager-bulk-reject', [PaymentController::class, 'managerBulkReject'])->name('payment.manager.bulk.reject');
    Route::post('/payment/bulk-approve', [PaymentController::class, 'bulkApprove'])->name('payment.bulk.approve');
    Route::post('/payment/send-bulk-otp', [PaymentController::class, 'sendBulkOTP'])->name('payment.send.bulk.otp');

    // Loan Application Approval Routes (Manager)
	//general_manager
	//loanofficer
    Route::post('/payment/send-loan-otp/{paymentId}', [PaymentController::class, 'sendLoanOTP'])->name('payment.send.loan.otp');
    Route::post('/payment/verify-loan-otp/{paymentId}', [PaymentController::class, 'verifyLoanOTP'])->name('payment.verify.loan.otp');
    Route::post('/payment/reject-loan-application', [PaymentController::class, 'rejectLoanApplication'])->name('payment.reject.loan.application');
    Route::get('loans/{member}/amortization-form', [LoanController::class, 'showAmortizationForm'])->name('loans.amortizationForm');
    Route::post('loans/{member}/amortization', [LoanController::class, 'calculateAmortization'])->name('loans.calculate');
    Route::post('/payment/pay/{paymentId}', [PaymentController::class, 'pay'])->name('payment.pay');
    Route::post('/loans/{loanApplication}/process', [LoanController::class, 'process'])->name('loans.process');
Route::post('/loans/{loanApplication}/approve', [LoanController::class, 'approve'])->name('loans.approve');
Route::post('/loans/{loanApplication}/reject', [LoanController::class, 'reject'])->name('loans.reject');
Route::post('/loans/{loanApplication}/send-otp-approve-loan', [LoanController::class, 'sendOtpApproveLoan'])->name('loans.send-otp-approve');
Route::post('/loans/{loanApplication}/verify-otp-approve-loan', [LoanController::class, 'verifyOtpApproveLoan'])->name('loans.verify-otp-approve');
Route::post('/loans/bulk-reject', [LoanController::class, 'bulkReject'])->name('loans.bulk-reject');
Route::get('/mortgage-form', [MortgageCalculatorController::class, 'showForm'])->name('mortgage.form');
Route::post('/calculate-loanable-amount', [MortgageCalculatorController::class, 'calculateLoanableAmount'])->name('calculate.loanable.amount');



	//general_manager
	//loanofficer
Route::post('upload-data', [MemberController::class, 'store'])->name('members.store');
Route::get('processed-loans', [MemberController::class, 'showProcessedLoans'])->name('members.processedLoans');
Route::get('upload-form', [MemberController::class, 'showUploadForm'])->name('members.uploadForm');
Route::post('store-members', [MemberController::class, 'store'])->name('members.store');
Route::get('loans/{member}/amortization-form', [LoanController::class, 'showAmortizationForm'])->name('loans.amortizationForm');
Route::post('loans/{member}/amortization', [LoanController::class, 'calculateAmortization'])->name('loans.calculate');



//user Profile and Update
// Route::get('/profile', [UserProfileController::class, 'showProfile'])->name('profile.show');
//     Route::get('/profile/edit', [UserProfileController::class, 'editProfile'])->name('profile.edit');
//     Route::post('/profile/edit', [UserProfileController::class, 'updateProfile'])->name('profile.update');
//     Route::post('/profile/change-password', [UserProfileController::class, 'changePassword'])->name('profile.change-password');



    Route::get('/trends', [DashboardTrendsController::class, 'index'])->name('trends');
    Route::get('/loan_trends', [LoanTrendController::class, 'index'])->name('loan_trends');
    Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
Route::get('/branches/create', [BranchController::class, 'create'])->name('branches.create');
Route::post('/branches', [BranchController::class, 'store'])->name('branches.store'); // Ensure this line is correct
Route::get('/branches/{branch}', [BranchController::class, 'show'])->name('branches.show');
Route::get('/branches/{branch}/edit', [BranchController::class, 'edit'])->name('branches.edit');
Route::put('/branches/{branch}', [BranchController::class, 'update'])->name('branches.update');
Route::delete('/branches/{branch}', [BranchController::class, 'destroy'])->name('branches.destroy');
Route::resource('departments', DepartmentController::class);
Route::resource('representatives', RepresentativeController::class);
Route::get('/trends', [DashboardTrendsController::class, 'index'])->name('trends');
    Route::get('/loan_trends', [LoanTrendController::class, 'index'])->name('loan_trends');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
Route::get('/payroll/upload', [PayrollController::class, 'showUploadForm'])->name('payroll.showUpload');
Route::post('/payroll/upload', [PayrollController::class, 'uploadExcel'])->name('payroll.upload');
Route::resource('files', FileController::class);
Route::resource('file_series', FileSeriesController::class);
Route::resource('keywords', KeywordController::class);
Route::post('keywords/import', [KeywordController::class, 'import'])->name('keywords.import');
Route::get('keywords/import/show', [KeywordController::class, 'showImportForm'])->name('keywords.showImportForm');


//HIZI ROUTES  HAZIHITAJI USER LOGIN KSWA SABABU ZINAUNGANISHA NA MFUMO WA NJE/ SIJAJUA SECURITY YAKE INAWEKWA VIPI
Route::get('/testapi',[HomeController::class, 'showData'] )->name('test.api');
Route::get('/api-response', [HomeController::class, 'consumeExternalService']);
Route::get('/send-xml', [App\Http\Controllers\ApiController::class, 'sendXmlData']);
Route::get('/consume-xml', [App\Http\Controllers\ApiController::class, 'consumeXml']);
Route::get('/send-product-details', [App\Http\Controllers\ApiController::class, 'sendProductDetails']);
Route::post('/product-decommission', [App\Http\Controllers\ApiController::class, 'sendProductDecommission'])->name('product.decommission');
Route::get('/trigger-decommission', [App\Http\Controllers\ApiController::class, 'triggerDecommission'])
     ->name('trigger.decommission');
	 
	 
	 
	 

// ============================================
// LOAN MANAGEMENT ROUTES
// ============================================

// Loan Status Views (Using EmployeeLoanController for now)
//loanofficer
Route::get('/loans/pending', [EmployeeLoanController::class, 'pendingLoans'])->name('loans.pending');
Route::get('/loans/approved', [EmployeeLoanController::class, 'approvedLoans'])->name('loans.approved');
Route::get('/loans/rejected', [EmployeeLoanController::class, 'rejectedLoans'])->name('loans.rejected');
Route::get('/loans/disbursed', [EmployeeLoanController::class, 'disbursedLoans'])->name('loans.disbursed');

// Disbursement Management Routes
//accountant
Route::get('/disbursements/pending', [EmployeeLoanController::class, 'pendingDisbursements'])->name('disbursements.pending');
Route::post('/disbursements/process', [EmployeeLoanController::class, 'processDisbursement'])->name('disbursements.process');
Route::post('/disbursements/reject', [EmployeeLoanController::class, 'rejectDisbursement'])->name('disbursements.reject');
Route::post('/disbursements/batch/process', [EmployeeLoanController::class, 'batchProcessDisbursement'])->name('disbursements.batch.process');
Route::post('/disbursements/batch/retry', [EmployeeLoanController::class, 'batchRetryDisbursement'])->name('disbursements.batch.retry');
Route::post('/disbursements/{id}/retry', [EmployeeLoanController::class, 'retryDisbursement'])->name('disbursements.retry');
Route::get('/disbursements/export', [EmployeeLoanController::class, 'exportDisbursements'])->name('disbursements.export');

// Bulk Operations
Route::post('/loans/bulk-approve', [EmployeeLoanController::class, 'bulkApprove'])->name('loans.bulk-approve');
Route::post('/loans/bulk-reject', [EmployeeLoanController::class, 'bulkReject'])->name('loans.bulk-reject');

// Reports and Analytics
//loanofficer
Route::get('/loans/reports', [EmployeeLoanController::class, 'reports'])->name('loans.reports');
Route::get('/loans/collections', [EmployeeLoanController::class, 'collections'])->name('loans.collections');

// Employee Loan Offers Routes (ESS Integration)
Route::get('/loan-offers', [EmployeeLoanController::class, 'indexLoanOffers'])
     ->name('loan-offers.index'); 

Route::get('/loan-offers/{id}/edit', [EmployeeLoanController::class, 'editLoanOffer'])
     ->name('loan-offers.edit');

Route::put('/loan-offers/{id}', [EmployeeLoanController::class, 'updateLoanOffer'])
     ->name('loan-offers.update');

// API routes for loan offers
Route::get('/loan-offers/{id}', [EmployeeLoanController::class, 'showLoanOffer'])->name('loan-offers.show');


//accountant
Route::post('/loan-offers/batch-disburse', [EmployeeLoanController::class, 'batchDisbursement'])->name('loan-offers.batch-disburse');
Route::post('/loan-offers/{id}/reject-disbursement', [EmployeeLoanController::class, 'rejectDisbursement'])->name('loan-offers.reject-disbursement');
// Disbursement Management Routes
Route::get('/disbursements', [EmployeeLoanController::class, 'disbursementsIndex'])->name('disbursements.index');
  

  
// Add this with your other loan-offers routes

//loanofficer
Route::get('/loan-offers/{id}/details', [EmployeeLoanController::class, 'getLoanOfferDetails'])->name('loan-offers.details');
Route::get('/loan-offers/{loanOffer}/callbacks', [App\Http\Controllers\EmployeeLoanController::class, 'showCallbacks'])->name('loan-offers.callbacks');
// Add this with your other loan-offers routes
Route::get('/loan-offers/{loanOffer}/callbacks-ajax', [App\Http\Controllers\EmployeeLoanController::class, 'fetchCallbacksAjax'])->name('loan-offers.callbacks.ajax');
Route::get('/test-nmb-hardcoded', [App\Http\Controllers\EmployeeLoanController::class, 'testNmbHardcoded']);
Route::get('/monthly-deductions', [EmployeeLoanController::class, 'listMonthlyDeductions'])->name('monthly-deductions.list');
// Export and Sync routes
Route::get('/loan-offers/export', [EmployeeLoanController::class, 'export'])->name('loan-offers.export');
Route::post('/loan-offers/sync', [EmployeeLoanController::class, 'syncFromESS'])->name('loan-offers.sync');
Route::post('/loan-offers/bulk-action', [EmployeeLoanController::class, 'bulkAction'])->name('loan-offers.bulk-action');
// Import Existing Loans Routes
Route::get('/existing-loans/import', [ExistingLoanImportController::class, 'showImportForm'])->name('existing-loans.import-form');
Route::post('/existing-loans/import', [ExistingLoanImportController::class, 'import'])->name('existing-loans.import');
Route::post('/existing-loans/import-default', [ExistingLoanImportController::class, 'importDefault'])->name('existing-loans.import-default');
Route::get('/existing-loans/template', [ExistingLoanImportController::class, 'downloadTemplate'])->name('existing-loans.template');



// NMB Integration Routes  hazihitaji userlogin  inaunga na mfumo wa nje
Route::post('/nmb/callback', [NmbDisbursementController::class, 'handleCallback'])->name('nmb.callback');

Route::get('/nmb/callback', function() {
    return response()->json([
        'status' => 'ok',
        'message' => 'NMB callback endpoint is active. Please use POST method for actual callbacks.',
        'timestamp' => now()->toIso8601String()
    ]);
})->name('nmb.callback.verify');

Route::post('/nmb/bulk-disbursement', [NmbDisbursementController::class, 'processBulkDisbursement'])->name('nmb.bulk.disbursement')->middleware('auth');
Route::get('/nmb/status/{batchId}', [NmbDisbursementController::class, 'checkStatus'])->name('nmb.status.check')->middleware('auth');
Route::post('/nmb/reconcile', [NmbDisbursementController::class, 'reconcileTransactions'])->name('nmb.reconcile')->middleware('auth');

});









require __DIR__.'/auth.php';

// ============================================
// UNAUTHORIZED ACCESS ROUTES
// ============================================
Route::get('/unauthorized-access', [App\Http\Controllers\UnauthorizedAccessController::class, 'show'])
    ->name('unauthorized.access')
    ->middleware('auth');

Route::get('/unauthorized-access/data', [App\Http\Controllers\UnauthorizedAccessController::class, 'getUnauthorizedAccessData'])
    ->name('unauthorized.access.data')
    ->middleware(['auth', 'role.access']);

Route::post('/unauthorized-access/export-excel', [App\Http\Controllers\UnauthorizedAccessController::class, 'exportToExcel'])
    ->name('unauthorized.access.export.excel')
    ->middleware(['auth', 'role.access']);

Route::post('/unauthorized-access/export-frequent-pdf', [App\Http\Controllers\UnauthorizedAccessController::class, 'exportFrequentAttemptsToPdf'])
    ->name('unauthorized.access.export.frequent.pdf')
    ->middleware(['auth', 'role.access']);

Route::post('/unauthorized-access/export-frequent-csv', [App\Http\Controllers\UnauthorizedAccessController::class, 'exportFrequentAttemptsToCSV'])
    ->name('unauthorized.access.export.frequent.csv')
    ->middleware(['auth', 'role.access']);

// ============================================
// AUTHENTICATION AND OTP ROUTES (Outside Auth Middleware)
// ============================================
Route::get('/', [AuthenticatedSessionController::class, 'create']);
Route::post('/otp-confirm', [AuthenticatedSessionController::class, 'confirmOTP'])->name('otp.confirm');
Route::post('/resend-otp', [AuthenticatedSessionController::class, 'resendOTP'])->name('otp.resend');
Route::post('/check-cooldown-status', [AuthenticatedSessionController::class, 'checkCooldownStatus'])->name('otp.check-cooldown');
Route::post('/debug-cache-state', [AuthenticatedSessionController::class, 'debugCacheState'])->name('otp.debug-cache');
Route::get('/otp-verify', function () {
    return view('auth.otp-verify');
})->name('otp.verify');

// Password change routes (outside main middleware)  hizi hazina role yoyote  ni pale user anaingia kwa mara ya kwanza  labada iwepo  user.status 
Route::middleware(['auth'])->group(function () {
    Route::get('/password/change/first', [AuthenticatedSessionController::class, 'showFirstPasswordChangeForm'])->name('password.change.first');
    Route::post('/password/change/first', [AuthenticatedSessionController::class, 'storeFirstPasswordChange'])->name('password.change.first.store');
    Route::get('/password/change/required', [AuthenticatedSessionController::class, 'showRequiredPasswordChangeForm'])->name('password.change.required');
    Route::post('/password/change/required', [AuthenticatedSessionController::class, 'storeRequiredPasswordChange'])->name('password.change.required.store');
});



//loanOfficer
// Redirect authenticated users to loan offers
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('loan-offers.index');
    }
    return redirect()->route('login');
});




//admin
//accountant
//loanofficer
//Registrar
//superadmin
//system_admin
//public_relation_officer
//registrar_hq
//representative
//general_manager
//branch_manager
Route::get('/deduction-headers/{checkDate}', [DeductionApiController::class, 'getDeductionHeaders']);
Route::get('/deduction-details/{checkDate}', [DeductionApiController::class, 'getDeductionDetails']);
Route::get('/deduction-details', [DeductionApiController::class, 'showDeductionDetails'])->name('deduction.details');
// Routes for contributions
Route::get('/deductions/contributions', [DeductionApiController::class, 'viewContributions'])->name('deductions.contributions.view');
Route::post('/deductions/contributions', [DeductionApiController::class, 'showContributions'])->name('deductions.contributions.show');

// Routes for loans
Route::get('/deductions/loans', [DeductionApiController::class, 'viewLoans'])->name('deductions.loans.view');
Route::post('/deductions/loans', [DeductionApiController::class, 'showLoans'])->name('deductions.loans.show');

Route::get('/deductions/import', [DeductionApiController::class, 'importAllDeductions'])
    ->name('deductions.import');
Route::get('/deductions/import/form', [DeductionApiController::class, 'showImportForm'])
    ->name('deductions.import.form');



//superadmin
//system_admin
//admin
Route::post('/deductions/import', [DeductionApiController::class, 'importAllDeductions'])
    ->name('deductions.import');

// ============================================
// DEDUCTION MANAGEMENT ROUTES
// ============================================

//admin
//accountant
//loanofficer
//Registrar
//superadmin
//system_admin
//public_relation_officer
//registrar_hq
//representative
//general_manager
//branch_manager
Route::get('/deductions/contributions', [DeductionApiController::class, 'handleContributions'])
    ->name('deductions.contributions.handle');
Route::get('export-salary-pdf/{checkNumber}', [DeductionApiController::class, 'exportSalaryDetailPdf'])
    ->name('exportSalaryDetailPdf');
Route::get('export-member-contribution-pdf/{checkNumber}', [DeductionApiController::class, 'exportMemberContributionPdf'])
    ->name('exportMemberContributionPdf');
Route::get('/deductions/members', [DeductionApiController::class, 'listMembers'])
    ->name('deductions.members.list');
Route::get('/deductions/salary-loans', [DeductionApiController::class, 'listSalaryLoans'])
    ->name('deductions.salary.loans');
Route::get('/deductions/details/{checkNumber}', [DeductionApiController::class, 'showSalaryLoanDetails'])
    ->name('deductions.details');
Route::get('/deductions/details/contributions/{checkNumber}', [DeductionApiController::class, 'showMemberContribution'])
    ->name('deductions.contributiondetails');
Route::get('/deductions/contributions/{checkNumber}/export/csv', [DeductionApiController::class, 'exportMemberContributionCsv'])
    ->name('deductions.export.csv');
Route::get('/deductions/salary/{checkNumber}/export/csv', [DeductionApiController::class, 'exportSalaryDetailCsv'])
    ->name('salary_detail.export.csv');
Route::get('/deductions/variance', [DeductionVarianceController::class, 'index'])
    ->name('deductions.variance');
Route::get('/deductions/export-csv', [DeductionVarianceController::class, 'exportCsv'])
    ->name('deductions.export_csv');
Route::get('/differences', [DeductionDifferencesController::class, 'index'])
    ->name('deduction667.differences.index');
Route::get('/differences/export', [DeductionDifferencesController::class, 'export'])
    ->name('deduction667.differences.export');
Route::get('/de/analysis', [ContributionAnalysisController::class, 'index'])
    ->name('deductions.contribution_analysis');
Route::get('/de/analysis/export', [ContributionAnalysisController::class, 'export'])
    ->name('deductions.export_analysis');

// ============================================
// MEMBERSHIP AND USER PROFILE ROUTES
// ============================================

//admin
//accountant
//loanofficer
//Registrar
//superadmin
//system_admin
//public_relation_officer
//registrar_hq
//representative
//general_manager
//branch_manager
Route::resource('members', MembershipController::class);
Route::resource('uramembers', MemberController::class);

//superadmin
//system_admin
Route::post('uramembers/import', [MemberController::class, 'import'])->name('uramembers.import');


//admin
//accountant
//loanofficer
//Registrar
//superadmin
//system_admin
//public_relation_officer
//registrar_hq
//representative
//general_manager
//branch_manager
Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::post('/profile/update-password', [UserController::class, 'updatePassword'])->name('profile.update-password');

// ============================================
// COMMAND AND RANK MANAGEMENT ROUTES
// ============================================
//superadmin
//system_admin
Route::resource('commands', CommandController::class);
Route::get('/ranks/create', [RankController::class, 'create'])->name('ranks.create');
Route::post('/ranks', [RankController::class, 'store'])->name('ranks.store');
Route::get('/ranks/{id}/edit', [RankController::class, 'edit']);
Route::put('/ranks/{id}', [RankController::class, 'update']);
Route::delete('/ranks/{id}', [RankController::class, 'destroy']);

// ============================================
// DISTRICTS AND REGIONS ROUTES
// ============================================
//superadmin
//system_admin
Route::get('/districts/{regionId}', function($regionId) {
    $districts = District::where('region_id', $regionId)->get();
    return response()->json($districts);
});

// ============================================
// EXPORT AND ANALYTICS ROUTES
// ============================================
//loanofficer
Route::get('/export-loan-applications', [LoanController::class, 'loanOfficerDashboard'])->name('export.loan.applications');

// ============================================
// BULK SMS AND CAMPAIGN ROUTES
// ============================================

//admin
//accountant
//loanofficer
//superadmin
//system_admin
//public_relation_officer
//general_manager
Route::get('/bulk-sms-form', [BulkSMSController::class, 'showForm'])->name('bulk.sms.form');
Route::post('/bulk-sms-parse', [BulkSMSController::class, 'parseCSV'])->name('bulk.sms.parse');
Route::post('/bulk-sms-send', [BulkSMSController::class, 'sendBulkSMS'])->name('bulk.sms.send');
Route::post('/bulk-sms/export-problematic', [BulkSMSController::class, 'exportProblematicCSV'])->name('bulk.sms.export-problematic');
Route::post('/bulk-sms/export-failed', [BulkSMSController::class, 'exportFailedSMSCSV'])->name('bulk.sms.export-failed');

// ============================================
// CARD DETAILS MANAGEMENT ROUTES
// ============================================
//superadmin
//system_admin
Route::get('/card-details', [CardDetailManagerController::class, 'index'])->name('card-details.index');
Route::post('/card-details/sync', [CardDetailManagerController::class, 'syncFromApi'])->name('card-details.sync');
Route::get('/card-details/{id}/edit', [CardDetailManagerController::class, 'edit'])->name('card-details.edit');
Route::patch('/card-details/{id}', [CardDetailManagerController::class, 'update'])->name('card-details.update');
Route::get('/card-details/{id}/status', [CardDetailManagerController::class, 'showStatusUpdateForm'])->name('card-details.showStatusUpdateForm');
Route::patch('/card-details/{id}/update-status', [CardDetailManagerController::class, 'updateStatus'])->name('card-details.updateStatus');
Route::delete('/card-details/{id}', [CardDetailManagerController::class, 'destroy'])->name('card-details.destroy');
Route::get('/card-details/{id}', [CardDetailManagerController::class, 'show'])->name('card-details.show');
Route::get('card-export/export-csv', [CardDetailManagerController::class, 'exportCsv'])->name('card-details.exportCsv');