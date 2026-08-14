<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ActivityLogController;

use App\Models\Customer;
use App\Models\Loan;
use App\Models\Payment;
use App\Models\Employee;


// =========================================
// ROOT
// =========================================

Route::get('/', function () {

    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');

});


// =========================================
// AUTHENTICATED + VERIFIED ROUTES
// =========================================

Route::middleware(['auth', 'verified'])->group(function () {


    // =====================================
    // DASHBOARD
    // =====================================

    Route::get('/dashboard', function () {

        $totalCustomers = Customer::count();

        $activeLoans = Loan::where('status', 'Active')->count();

        $totalLoanAmount = Loan::sum('loan_amount');

        $outstandingBalance = Loan::where('status', 'Active')
            ->sum('remaining_balance');

        $todayCollection = Payment::whereDate(
            'payment_date',
            now()->toDateString()
        )->sum('total_paid');

        $capitalCollected = Payment::sum('capital_paid');

        $interestCollected = Payment::sum('interest_paid');

        $totalPayments = Payment::sum('total_paid');

        $employees = Employee::count();

        $recentPayments = Payment::with('loan.customer')
            ->latest('payment_date')
            ->latest('id')
            ->take(5)
            ->get();


        return view('dashboard.index', compact(
            'totalCustomers',
            'activeLoans',
            'totalLoanAmount',
            'outstandingBalance',
            'todayCollection',
            'capitalCollected',
            'interestCollected',
            'totalPayments',
            'employees',
            'recentPayments'
        ));

    })->name('dashboard');


    // =====================================
    // CUSTOMERS
    // =====================================

    Route::resource(
        'customers',
        CustomerController::class
    );


    // =====================================
    // LOANS
    // =====================================

    Route::resource(
        'loans',
        LoanController::class
    );


    // =====================================
    // PAYMENTS
    // =====================================

    Route::resource(
        'payments',
        PaymentController::class
    )->only([
        'index',
        'create',
        'store',
        'show'
    ]);


    // =====================================
    // DOWNLOAD PAYMENT RECEIPT PDF
    // =====================================

    Route::get(
        '/payments/{payment}/download',
        [PaymentController::class, 'downloadPdf']
    )->name('payments.download');


    // =====================================
    // EMPLOYEES
    // =====================================

    Route::resource(
        'employees',
        EmployeeController::class
    )->except([
        'show'
    ]);


    // =====================================
    // REPORTS
    // =====================================

    Route::get(
        '/reports',
        [ReportController::class, 'index']
    )->name('reports.index');


    // =====================================
    // ACTIVITY LOGS
    // =====================================

    Route::get(
        '/activity-logs',
        [ActivityLogController::class, 'index']
    )->name('activity-logs.index');


    // =====================================
    // PROFILE
    // =====================================

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');


    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');


    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

});


// =========================================
// AUTH / LOGIN / EMAIL VERIFICATION
// =========================================

require __DIR__.'/auth.php';