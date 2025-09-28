<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
//added
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PostController;
//end added
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\LoanTrendController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RepresentativeController;
use App\Http\Controllers\DashboardTrendsController;
use App\Http\Controllers\MortgageCalculatorController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
    Route::get('/enquiries/create', [EnquiryController::class, 'create'])->name('enquiries.create');
    Route::post('/enquiries', [EnquiryController::class, 'store'])->name('enquiries.store');
    Route::get('/enquiries/{enquiry}', [EnquiryController::class, 'show'])->name('enquiries.show');
    Route::get('/enquiries/{enquiry}/edit', [EnquiryController::class, 'edit'])->name('enquiries.edit');
    Route::put('/enquiries/{enquiry}', [EnquiryController::class, 'update'])->name('enquiries.update');
    Route::delete('/enquiries/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');


    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    //added jumamosi 12/10/24
    Route::get('/trends', [DashboardTrendsController::class, 'index'])->name('trends');
    Route::get('/loan_trends', [LoanTrendController::class, 'index'])->name('loan_trends');



Route::get('enquiries/{enquiry}/responses/create', [ResponseController::class, 'create'])->name('responses.create');
Route::post('enquiries/{enquiry}/responses', [ResponseController::class, 'store'])->name('responses.store');
Route::post('/enquiries/{enquiry}/change-status', [EnquiryController::class, 'changeStatus'])->name('enquiries.changeStatus');

Route::post('/notifications/{notification}/read', [NotificationController::class, 'markNotificationAsRead'])->name('notifications.read');
// Route for showing the form
Route::get('/mortgage-form', [MortgageCalculatorController::class, 'showForm'])->name('mortgage.form');

// Route for submitting the form
Route::post('/calculate-loanable-amount', [MortgageCalculatorController::class, 'calculateLoanableAmount'])->name('calculate.loanable.amount');
// routes/web.php
Route::post('upload-data', [MemberController::class, 'store'])->name('members.store');
// routes/web.php
Route::get('processed-loans', [MemberController::class, 'showProcessedLoans'])->name('members.processedLoans');
Route::get('upload-form', [MemberController::class, 'showUploadForm'])->name('members.uploadForm');

Route::get('loans/{member}/amortization-form', [LoanController::class, 'showAmortizationForm'])->name('loans.amortizationForm');
Route::post('loans/{member}/amortization', [LoanController::class, 'calculateAmortization'])->name('loans.calculate');
// web.php
Route::get('members/{member}/details', [MemberController::class, 'showDetails'] )->name('members.details');
Route::post('/members/{member}/status/{status}', [MemberController::class, 'updateStatus'] )->name('members.updateStatus');



Route::resource('roles', RoleController::class);
Route::resource('permissions', PermissionController::class);
Route::resource('users', UserController::class);

Route::post('/enquiries/{enquiry}/assign', [EnquiryController::class, 'assignUsersToEnquiry'])->name('enquiries.assign');

Route::get('/my-enquiries', [EnquiryController::class,'myAssignedEnquiries'])->name('enquiries.my');
Route::get('/enquiries/{enquiry}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
Route::post('/enquiries/{enquiry}/payments', [PaymentController::class, 'store'])->name('payments.store');
Route::get('/payments/type/{type}', [PaymentController::class, 'showByType'])->name('payments.type');
// Route::post('/payments/{id}/mark-paid', [PaymentController::class, 'markPaid'])->name('payments.markPaid');
// Route::post('/payments/{id}/mark-unpaid', [PaymentController::class, 'markUnpaid'])->name('payments.markUnpaid');
Route::post('/payment/initiate/{enquiryId}', [PaymentController::class, 'initiate'])->name('payment.initiate');
Route::post('/payment/approve/{paymentId}', [PaymentController::class, 'approve'])->name('payment.approve');
Route::post('/payment/pay/{paymentId}',[PaymentController::class, 'pay'] )->name('payment.pay');



Route::post('/payment/reject/{paymentId}',[PaymentController::class, 'reject'] )->name('payment.reject');

// Route::post('/send-otp/{paymentId}', [PaymentController::class, 'sendOtp'])->name('send.otp');
// Route::post('/verify-otp/{paymentId}', [PaymentController::class, 'verifyOtp'])->name('verify.otp');

Route::post('/send-otp-approve/{paymentId}', [PaymentController::class, 'sendOtpApprove'])->name('send.otp.approve');
Route::post('/verify-otp-approve/{paymentId}', [PaymentController::class, 'verifyOtpApprove'])->name('verify.otp.approve');

Route::post('/send-otp-pay/{paymentId}', [PaymentController::class, 'sendOtpPay'])->name('send.otp.pay');
Route::post('/verify-otp-pay/{paymentId}', [PaymentController::class, 'verifyOtpPay'])->name('verify.otp.pay');




// Payment Timeline
Route::get('/payments/{paymentId}/timeline', [PaymentController::class, 'showTimeline'])->name('payments.timeline');

Route::post('/loans/{loanApplication}/process', [LoanController::class, 'process'])->name('loans.process');
Route::post('/loans/{loanApplication}/approve', [LoanController::class, 'approve'])->name('loans.approve');
Route::post('/loans/{loanApplication}/reject', [LoanController::class, 'reject'])->name('loans.reject');
Route::post('/loans/{loanApplication}/send-otp-approve-loan', [LoanController::class, 'sendOtpApproveLoan'])->name('loans.send-otp-approve');
Route::post('/loans/{loanApplication}/verify-otp-approve-loan', [LoanController::class, 'verifyOtpApproveLoan'])->name('loans.verify-otp-approve');

Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
Route::get('/branches/create', [BranchController::class, 'create'])->name('branches.create');
Route::post('/branches', [BranchController::class, 'store'])->name('branches.store'); // Ensure this line is correct
Route::get('/branches/{branch}', [BranchController::class, 'show'])->name('branches.show');
Route::get('/branches/{branch}/edit', [BranchController::class, 'edit'])->name('branches.edit');
Route::put('/branches/{branch}', [BranchController::class, 'update'])->name('branches.update');
Route::delete('/branches/{branch}', [BranchController::class, 'destroy'])->name('branches.destroy');
Route::resource('departments', DepartmentController::class);
Route::resource('representatives', RepresentativeController::class);

Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');

});

Route::get('/', [AuthenticatedSessionController::class, 'create']);

// Public Routes
// Route::get('/', function () {
//     return view('auth.login');
// });








require __DIR__.'/auth.php';
