<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $loanQuery = Loan::query();
        $paymentQuery = Payment::with('loan.customer');

        if ($fromDate) {
            $loanQuery->whereDate('loan_date', '>=', $fromDate);
            $paymentQuery->whereDate('payment_date', '>=', $fromDate);
        }

        if ($toDate) {
            $loanQuery->whereDate('loan_date', '<=', $toDate);
            $paymentQuery->whereDate('payment_date', '<=', $toDate);
        }

        $loans = $loanQuery
            ->with('customer')
            ->latest('loan_date')
            ->get();

        $payments = $paymentQuery
            ->latest('payment_date')
            ->latest('id')
            ->get();

        $totalLoansIssued = $loans->sum('loan_amount');

        $capitalCollected = $payments->sum('capital_paid');

        $interestCollected = $payments->sum('interest_paid');

        $totalCollected = $payments->sum('total_paid');

        $outstandingBalance = Loan::where('status', 'Active')
            ->sum('remaining_balance');

        return view('reports.index', compact(
            'fromDate',
            'toDate',
            'loans',
            'payments',
            'totalLoansIssued',
            'capitalCollected',
            'interestCollected',
            'totalCollected',
            'outstandingBalance'
        ));
    }
}