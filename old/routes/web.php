<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanController;
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
use App\Http\Controllers\DeductionApiController;
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

//Route::middleware(['auth'])->group(function () {

Route::middleware(['auth', 'user.status','update.activity'])->group(function () {


Route::post('/users/update-activity', [UserController::class, 'updateActivity'])
    ->name('users.update-activity')->middleware('auth');

Route::get('/users/online-users', [UserController::class, 'getOnlineUsersHtml'])
    ->name('users.online-users')->middleware('auth');

Route::post('/users/clear-online-status', [UserController::class, 'clearOnlineStatus'])
    ->name('users.clear-online-status')->middleware('auth');

    // First Password Change Routes
    Route::get('/password/change/first', [AuthenticatedSessionController::class, 'showFirstPasswordChangeForm'])->name('password.change.first');
    Route::post('/password/change/first', [AuthenticatedSessionController::class, 'storeFirstPasswordChange'])->name('password.change.first.store');


    Route::get('/password/change/required', [AuthenticatedSessionController::class, 'showRequiredPasswordChangeForm'])->name('password.change.required');
    Route::post('/password/change/required', [AuthenticatedSessionController::class, 'storeRequiredPasswordChange'])->name('password.change.required.store');

    // 1. a route to export loan applications to CSV format
    Route::get('/loan-applications/export', [EnquiryController::class, 'exportLoanApplication'])->name('exportLoanApplication');
    
    // 2. a route to export Membership changes to CSV format
    Route::get('export-enquiries', [EnquiryController::class, 'exportMembershipChanges'])->name('exportEnquiriesUnjoinMembership');
    
    // 3. a route to export Conndolences to CSV format
    Route::get('export-condolences', [EnquiryController::class, 'exportCondolences'])->name('exportCondolences');
    
    // 4. a route to export deduction adjustment to CSV format
    Route::get('/deductions/export', [EnquiryController::class, 'export'])->name('deductions.export');
    
    // 5. a route to export refund to CSV format
    Route::get('/refunds/export', [EnquiryController::class, 'exportRefund'])->name('exportRefund');
    
    // 6. a a route to export residential disaster to CSV format
    Route::get('/residential-disasters/export', [EnquiryController::class, 'ResidentialDisasterExport'])->name('residential_disasters');
    
    // 7. a route to export retirement to CSV format
    Route::get('/retirements/export', [EnquiryController::class, 'exportRetirement'])->name('exportRetirement');
    
    // 8. a route to export shares to CSV format
    Route::get('/shares/export', [EnquiryController::class, 'exportShare'])->name('exportShare');
    
    // 9. a route to export sick30days to CSV format
    Route::get('/sickleave/export', [EnquiryController::class, 'exportSickLeave'])->name('exportSickLeave');
    
    // 10. a route to export with-draw savings to CSV format
    Route::get('/withdrawal/export', [EnquiryController::class, 'WithdrawalExport'])->name('withdrawalExport');
    
    // 11. a route to export injury from work to CSV format
    Route::get('/injury/export', [EnquiryController::class, 'InjuryExport'])->name('injuryExport');
    
    // 12. a route to Join memberships applications to CSV format
    Route::get('/membership/export', [EnquiryController::class, 'JoinMembershipExport'])->name('membershipExport');
    
    // 13. a route to export all enquiry
    Route::get('/all-enquiries/export', [EnquiryController::class, 'allEnquiriesExport'])->name('allEnquiriesExport');
    
    // 14. a enquiries  registered by users
    Route::get('/enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
    
    // 15. a user enquiry on click item
    Route::get('/enquiries/create/{check_number?}', [EnquiryController::class, 'create'])->name('enquiries.create');

    // 16. a enquiry to fetch member in the payroll
    Route::get('/enquiries/fetch-payroll/{check_number}', [EnquiryController::class, 'fetchPayroll']);

    // 17. a route to store  enquiry registered by registrar in the database
    Route::post('/enquiries', [EnquiryController::class, 'store'])->name('enquiries.store');
    
    // 18. a route to show application more details,  registered by registrar
    Route::get('/enquiries/{enquiry}', [EnquiryController::class, 'show'])->name('enquiries.show');
    
    // 19. a route to edit enquiry
    Route::get('/enquiries/{enquiry}/edit', [EnquiryController::class, 'edit'])->name('enquiries.edit');
    
    // 20. a route to edit enquiry
    Route::put('/enquiries/{enquiry}', [EnquiryController::class, 'update'])->name('enquiries.update');
    
    // 21. a route to delete enquiry
    Route::delete('/enquiries/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');

    // 22. a route to access dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // 23. a route to create enquiry
    Route::get('enquiries/{enquiry}/responses/create', [ResponseController::class, 'create'])->name('responses.create');

    // 24. a route to create enquiry
    Route::post('enquiries/{enquiry}/responses', [ResponseController::class, 'store'])->name('responses.store');

    // 25. a route to change enquiry status, assign, approve
    Route::post('/enquiries/{enquiry}/change-status', [EnquiryController::class, 'changeStatus'])->name('enquiries.changeStatus');

    // 26. a route to enquiry notification ring icon on top nav
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markNotificationAsRead'])->name('notifications.read');

    // 27. a route to show member details
    Route::get('members/{member}/details', [MemberController::class, 'showDetails'] )->name('members.details');

    // 28. a route to update member details
    Route::post('/members/{member}/status/{status}', [MemberController::class, 'updateStatus'] )->name('members.updateStatus');
    
    // 29. a route for roles
    Route::resource('roles', RoleController::class);
    
    // 30. a route for permission
    Route::resource('permissions', PermissionController::class);

    // 31. a route to show system users, index
    Route::resource('users', UserController::class);

    // 32. a route to calculate Loan
    Route::post('/calculate-loan/{loanApplicationId}', [LoanController::class, 'calculateLoan'])->name('calculate.loan');;

    // 33. a route to show enquiries
    Route::get('/enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
    
    // 34. a route to create enquiry
    Route::get('/enquiries/create', [EnquiryController::class, 'create'])->name('enquiries.create');
    
    // 35. a route to store enquiry
    Route::post('/enquiries', [EnquiryController::class, 'store'])->name('enquiries.store');
    
    // 36. a route to show enquiry
    Route::get('/enquiries/{enquiry}', [EnquiryController::class, 'show'])->name('enquiries.show');
    
    // 37. a route to edit enquiry
    Route::get('/enquiries/{enquiry}/edit', [EnquiryController::class, 'edit'])->name('enquiries.edit');
    
    // 38. a route to update enquiry
    Route::put('/enquiries/{enquiry}', [EnquiryController::class, 'update'])->name('enquiries.update');
    
    // 40. a route to delete enquiry
    Route::delete('/enquiries/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');
    
    // 41. a route to change status of enquiry
    Route::post('enquiries/{enquiry}/change-status', [EnquiryController::class, 'changeStatus'])->name('enquiries.changeStatus');
    
    // 42. a route to show member details
    Route::get('members/{member}/details', [MemberController::class, 'showDetails'])->name('members.details');
    
    // 43. a route to assign enquiry to Accountant or LoanOfficer
    Route::post('/enquiries/{enquiry}/assign', [EnquiryController::class, 'assignUsersToEnquiry'])->name('enquiries.assign');

    // 44. a route to show my assigned enquiry
    Route::get('/my-enquiries', [EnquiryController::class,'myAssignedEnquiries'])->name('enquiries.my');

    // 45. a route to create payment
    Route::get('/enquiries/{enquiry}/payments/create', [PaymentController::class, 'create'])->name('payments.create');

    // 46. a route to store payment
    Route::post('/enquiries/{enquiry}/payments', [PaymentController::class, 'store'])->name('payments.store');

    // 47. a route to pay or to make payment
    Route::post('/payment/pay/{paymentId}', [PaymentController::class, 'pay'])->name('payment.pay');
    
    // 48. a route for payment timeline
    Route::get('/payments/{paymentId}/timeline', [PaymentController::class, 'showTimeline'])->name('payments.timeline');
    
    // 49. a route for rejecting payment
    Route::post('/payment/reject/{paymentId}',[PaymentController::class, 'reject'] )->name('payment.reject');

    // 50. a route for sending OTP when Login
    Route::post('/send-otp-approve/{paymentId}', [PaymentController::class, 'sendOtpApprove'])->name('send.otp.approve');

    // 51. a route to approve OTP after when OTP is entered
    Route::post('/verify-otp-approve/{paymentId}', [PaymentController::class, 'verifyOtpApprove'])->name('verify.otp.approve');

    // 52. a route to send OTP pay
    Route::post('/send-otp-pay/{paymentId}', [PaymentController::class, 'sendOtpPay'])->name('send.otp.pay');

    // 53. a route to verify OTP pay
    Route::post('/verify-otp-pay/{paymentId}', [PaymentController::class, 'verifyOtpPay'])->name('verify.otp.pay');

    // 54. a route to show payment by type
    Route::get('/payments/type/{type}', [PaymentController::class, 'showByType'])->name('payments.type');

    // 55. a route to initiate payment
    Route::post('/payment/initiate/{enquiryId}', [PaymentController::class, 'initiate'])->name('payment.initiate');

    // 56. a route to approve payment
    Route::post('/payment/approve/{paymentId}', [PaymentController::class, 'approve'])->name('payment.approve');

    // 57. a route to pay
    Route::post('/payment/pay/{paymentId}',[PaymentController::class, 'pay'] )->name('payment.pay');

    // 58. a route to reject payment
    Route::post('/payment/reject/{paymentId}',[PaymentController::class, 'reject'] )->name('payment.reject');
    
    // 59. a route to approve payment OTP
    Route::post('/send-otp-approve/{paymentId}', [PaymentController::class, 'sendOtpApprove'])->name('send.otp.approve');

    // 60. a route to approve OTP entered,  Payment
    Route::post('/verify-otp-approve/{paymentId}', [PaymentController::class, 'verifyOtpApprove'])->name('verify.otp.approve');

    // 62. a route to send OTP pay
    Route::post('/send-otp-pay/{paymentId}', [PaymentController::class, 'sendOtpPay'])->name('send.otp.pay');

    // 63. a route to verify OTP pay
    Route::post('/verify-otp-pay/{paymentId}', [PaymentController::class, 'verifyOtpPay'])->name('verify.otp.pay');

    // 64. a route for Amortization page
    Route::get('loans/{member}/amortization-form', [LoanController::class, 'showAmortizationForm'])->name('loans.amortizationForm');
    
    // 65. a route for Amortization when a user is clicked  from table
    Route::post('loans/{member}/amortization', [LoanController::class, 'calculateAmortization'])->name('loans.calculate');
    
    // 66. a route for payment
    Route::post('/payment/pay/{paymentId}', [PaymentController::class, 'pay'])->name('payment.pay');
   
    // 67. a route to process a loan
    Route::post('/loans/{loanApplication}/process', [LoanController::class, 'process'])->name('loans.process');
    
    // 68. a route to approve Loan
    Route::post('/loans/{loanApplication}/approve', [LoanController::class, 'approve'])->name('loans.approve');
    
    // 69. a route to reject loan application
    Route::post('/loans/{loanApplication}/reject', [LoanController::class, 'reject'])->name('loans.reject');

    // 70. a route for Loan Officer to approve Loan OTP
    Route::post('/loans/{loanApplication}/send-otp-approve-loan', [LoanController::class, 'sendOtpApproveLoan'])->name('loans.send-otp-approve');

    // 71. a route for Loan Officer to approve Loan OTP, OTP approval
    Route::post('/loans/{loanApplication}/verify-otp-approve-loan', [LoanController::class, 'verifyOtpApproveLoan'])->name('loans.verify-otp-approve');

    // 72. a route for mortgage
    Route::get('/mortgage-form', [MortgageCalculatorController::class, 'showForm'])->name('mortgage.form');

    // 73. a route to calculate Loanable-amount
    Route::post('/calculate-loanable-amount', [MortgageCalculatorController::class, 'calculateLoanableAmount'])->name('calculate.loanable.amount');

    // 74. a route to upload member
    Route::post('upload-data', [MemberController::class, 'store'])->name('members.store');

    // 75. a route to show proccessed Loan
    Route::get('processed-loans', [MemberController::class, 'showProcessedLoans'])->name('members.processedLoans');
    
    // 76. a route to upload member
    Route::get('upload-form', [MemberController::class, 'showUploadForm'])->name('members.uploadForm');

    // 77. a route to store members
    Route::post('store-members', [MemberController::class, 'store'])->name('members.store');

    // 78. a route to show member amortization-form
    Route::get('loans/{member}/amortization-form', [LoanController::class, 'showAmortizationForm'])->name('loans.amortizationForm');

    // 79. a route to show member amortization
    Route::post('loans/{member}/amortization', [LoanController::class, 'calculateAmortization'])->name('loans.calculate');

    // 80. a route to display branches
    Route::resource('branches', BranchController::class);

    // 81. a route to display departments
    Route::resource('departments', DepartmentController::class);

    // 82. a route to upload payroll, is used in create enquiry
    Route::get('/payroll/upload', [PayrollController::class, 'showUploadForm'])->name('payroll.showUpload');

    // 83. a route to upload payroll, is used in create enquiry
    Route::post('/payroll/upload', [PayrollController::class, 'uploadExcel'])->name('payroll.upload');

    // 84. a route for file management
    Route::resource('files', FileController::class);

    // 85. a route for file management
    Route::resource('file_series', FileSeriesController::class);

    // 86. a route for file management
    Route::resource('keywords', KeywordController::class);

    // 87. a route for file management
    Route::post('keywords/import', [KeywordController::class, 'import'])->name('keywords.import');
    
    // 88. a route for file management
    Route::get('keywords/import/show', [KeywordController::class, 'showImportForm'])->name('keywords.showImportForm');

    // 89. a route for deduction Import
    Route::get('/deductions/import/form', [DeductionApiController::class, 'showImportForm'])->name('deductions.import.form');

    // 90. a route for deduction Import
    Route::post('/deductions/import', [DeductionApiController::class, 'importAllDeductions'])->name('deductions.import');

    // 91. a route for deduction contribution
    Route::get('/deductions/contributions', [DeductionApiController::class, 'handleContributions'])->name('deductions.contributions.handle');

    // 92. a route to export Marejesho (Mikopo CSV)
    Route::get('export-salary-pdf/{checkNumber}', [DeductionApiController::class, 'exportSalaryDetailPdf'])->name('exportSalaryDetailPdf');

    // 93. a route to export Marejesho (Michango PDF)
    Route::get('export-member-contribution-pdf/{checkNumber}', [DeductionApiController::class, 'exportMemberContributionPdf'])->name('exportMemberContributionPdf');

    // 94. a route to show  members  ( Marejesho)
    Route::get('/deductions/members', [DeductionApiController::class, 'listMembers'])->name('deductions.members.list');

    // 95. a route to show  Mikopo  ( Marejesho) 
    Route::get('/deductions/salary-loans', [DeductionApiController::class, 'listSalaryLoans'])->name('deductions.salary.loans');
 
    // 96. a route to show  michango  ( Marejesho)
    Route::get('/deductions/contributions', [DeductionApiController::class, 'handleContributions'])->name('deductions.contributions.handle');

    // 97. angalia taarifa zaidi salary ( Marejesho)
    Route::get('/deductions/details/{checkNumber}', [DeductionApiController::class, 'showSalaryLoanDetails'])->name('deductions.details');

    // 98. angalia taarifa zaidi member contributin ( Marejesho)
   Route::get('/deductions/details/contributions/{checkNumber}', [DeductionApiController::class, 'showMemberContribution'])->name('deductions.contributiondetails');

   // 99. export taarifa zaidi member contribution Export CSV ( Marejesho)
   Route::get('/deductions/contributions/{checkNumber}/export/csv', [DeductionApiController::class, 'exportMemberContributionCsv'])->name('deductions.export.csv');

   // 100. export taarifa zaidi salary or Mikopo ( Marejesho )
   Route::get('/deductions/salary/{checkNumber}/export/csv', [DeductionApiController::class, 'exportSalaryDetailCsv'])->name('salary_detail.export.csv');

   // 101. Bulk SMS send and create Compain
   //Route::post('/bulk-sms', [BulkSMSController::class, 'sendBulkSMS'])->name('bulk.sms');

   // 102. Bulk SMS send and create Compain
   //Route::get('/bulk-sms-form', function () {return view('sms.bulk-sms');})->name('bulk.sms.form');
});

   // 103. Route to create
   Route::get('/', [AuthenticatedSessionController::class, 'create']);

   // 104. Route to confirm OTP
   Route::post('/otp-confirm', [AuthenticatedSessionController::class, 'confirmOTP'])->name('otp.confirm');

   // 105. Route to confirm OTP
   Route::get('/otp-verify', function () {return view('auth.otp-verify');})->name('otp.verify');

   // 105. Route for rank management,  to create
   Route::get('/ranks/create', [RankController::class, 'create'])->name('ranks.create');
   
   // 106. Route for rank management,  to store
   Route::post('/ranks', [RankController::class, 'store'])->name('ranks.store');

   // 107. Route for rank management,  to edit
   Route::get('/ranks/{id}/edit', [RankController::class, 'edit']);

   // 108. Route for rank management,  to update
   Route::put('/ranks/{id}', [RankController::class, 'update']);

   // 109. Route for rank management,  to delete
   Route::delete('/ranks/{id}', [RankController::class, 'destroy']);

   // 110.  Route for viewing and editing profile
   Route::get('/profile', [UserController::class, 'profile'])->middleware('auth')->name('profile');

   // 111. Route for updating profile (e.g., password change)
   Route::post('/profile/update-password', [UserController::class, 'updatePassword'])->middleware('auth')->name('profile.update-password');


   // 112. a route to display commands
   Route::resource('commands', CommandController::class);

    // 113. a route to display district also Fetch districts based on region
    Route::get('/districts/{regionId}', function($regionId) {
    $districts = District::where('region_id', $regionId)->get();
    return response()->json($districts);});

    // 114. a route membership managemet
    Route::resource('members', MembershipController::class);

    // 115. a route for only Loan Officer  to export assigned Task
    Route::get('/export-loan-applications', function () {return Excel::download(new LoanOfficerApplicationsExport, 'loan_applications.csv');})->name('export.loan.applications');

    // 116. a route to show diferences of contributions  to determine whether contribution applications take place
    Route::get('/deductions/variance', [DeductionVarianceController::class, 'index'])->name('deductions.variance');

    // 117. a route to export CSV diferences of contributions  to determine whether contribution applications take place
    Route::get('/deductions/export-csv', [DeductionVarianceController::class, 'exportCsv'])->name('deductions.export_csv');
 
    // 118. a route to show Loan deduction amount differences to determine (correctness of deduction_amout)
    Route::get('/differences', [DeductionDifferencesController::class, 'index'])->name('deduction667.differences.index');

    // 119. a route to Export Loan deduction amount differences to determine (correctness of deduction_amout)
    Route::get('/differences/export', [DeductionDifferencesController::class, 'export'])->name('deduction667.differences.export');

    // 120. a route to analyse contributions  (who contribute what)
    Route::get('/de/analysis', [ContributionAnalysisController::class, 'index'])->name('deductions.contribution_analysis');

    // 121. a route to export analysed contributions 
    Route::get('/de/analysis/export', [ContributionAnalysisController::class, 'export'])->name('deductions.export_analysis');






Route::get('/bulk-sms-form', [BulkSMSController::class, 'showForm'])->name('bulk.sms.form');
Route::post('/bulk-sms-parse', [BulkSMSController::class, 'parseCSV'])->name('bulk.sms.parse');
Route::post('/bulk-sms-send', [BulkSMSController::class, 'sendBulkSMS'])->name('bulk.sms.send');
    Route::post('/bulk-sms/export-problematic', [BulkSMSController::class, 'exportProblematicCSV'])->name('bulk.sms.export-problematic');
Route::post('/bulk-sms/export-failed', [BulkSMSController::class, 'exportFailedSMSCSV'])->name('bulk.sms.export-failed');


use App\Http\Controllers\CardDetailManagerController;

// Route ya kuonyesha orodha ya card details kutoka local DB
Route::get('/card-details', [CardDetailManagerController::class, 'index'])->name('card-details.index');

// Route mpya ya kusync data kutoka API kwenda local DB
Route::post('/card-details/sync', [CardDetailManagerController::class, 'syncFromApi'])->name('card-details.sync');

// Route ya kuonyesha fomu ya kuedit card detail kutoka local DB
Route::get('/card-details/{id}/edit', [CardDetailManagerController::class, 'edit'])->name('card-details.edit');

// Route ya kuupdate card detail (local & API)
Route::patch('/card-details/{id}', [CardDetailManagerController::class, 'update'])->name('card-details.update');

// Route mpya ya kuonyesha fomu maalum ya update status
Route::get('/card-details/{id}/status', [CardDetailManagerController::class, 'showStatusUpdateForm'])->name('card-details.showStatusUpdateForm');

// Route ya kuupdate status (local & API)
Route::patch('/card-details/{id}/update-status', [CardDetailManagerController::class, 'updateStatus'])->name('card-details.updateStatus');

// Route ya kufuta card detail (local & API)
Route::delete('/card-details/{id}', [CardDetailManagerController::class, 'destroy'])->name('card-details.destroy');

// Route mpya ya kuonyesha maelezo kamili ya card (NEW)
Route::get('/card-details/{id}', [CardDetailManagerController::class, 'show'])->name('card-details.show');
Route::get('card-export/export-csv', [CardDetailManagerController::class, 'exportCsv'])->name('card-details.exportCsv');


//members
Route::resource('uramembers', MemberController::class);
Route::post('uramembers/import', [MemberController::class, 'import'])->name('uramembers.import');



// Add these routes to your web.php file




 



require __DIR__.'/auth.php';