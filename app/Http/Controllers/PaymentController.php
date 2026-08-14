<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Payment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    // =========================================
    // SHOW ALL PAYMENTS
    // =========================================
    public function index()
    {
        $payments = Payment::with('loan.customer')
            ->latest('payment_date')
            ->latest('id')
            ->get();

        return view('payments.index', compact('payments'));
    }


    // =========================================
    // SHOW PAYMENT FORM
    // =========================================
    public function create(Request $request)
    {
        $loans = Loan::with('customer')
            ->where('status', 'Active')
            ->orderBy('loan_number')
            ->get();

        $selectedLoanId = $request->query('loan_id');

        return view(
            'payments.create',
            compact('loans', 'selectedLoanId')
        );
    }


    // =========================================
    // SAVE PAYMENT
    // =========================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'loan_id' => [
                'required',
                'exists:loans,id',
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'capital_paid' => [
                'required',
                'numeric',
                'min:0',
            ],

            'interest_paid' => [
                'required',
                'numeric',
                'min:0',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        $capitalPaid = round(
            (float) $validated['capital_paid'],
            2
        );

        $interestPaid = round(
            (float) $validated['interest_paid'],
            2
        );


        // At least one payment component must be greater than zero
        if ($capitalPaid <= 0 && $interestPaid <= 0) {

            return back()
                ->withInput()
                ->withErrors([
                    'capital_paid' =>
                        'Enter a capital or interest payment amount.',
                ]);
        }


        $employeeId =
            auth()->user()
                ->fresh()
                ->employee_id;


        $payment = DB::transaction(function () use (
            $validated,
            $capitalPaid,
            $interestPaid,
            $employeeId
        ) {

            /*
             * Lock loan while payment is being recorded.
             */
            $loan = Loan::with('customer')
                ->where('id', $validated['loan_id'])
                ->lockForUpdate()
                ->firstOrFail();


            // Payment allowed only for active loans
            if ($loan->status !== 'Active') {

                throw ValidationException::withMessages([
                    'loan_id' =>
                        'Payments can only be recorded for active loans.',
                ]);
            }


            $currentBalance = round(
                (float) $loan->remaining_balance,
                2
            );


            // Prevent capital overpayment
            if ($capitalPaid > $currentBalance) {

                throw ValidationException::withMessages([
                    'capital_paid' =>
                        'Capital payment cannot be greater than the remaining capital balance of LKR ' .
                        number_format($currentBalance, 2) .
                        '.',
                ]);
            }


            // Only capital reduces remaining principal
            $newBalance = max(
                0,
                round(
                    $currentBalance - $capitalPaid,
                    2
                )
            );


            $totalPaid = round(
                $capitalPaid + $interestPaid,
                2
            );


            // =========================================
            // CREATE PAYMENT
            // =========================================

            $payment = Payment::create([
                'loan_id' =>
                    $loan->id,

                'receipt_number' =>
                    'TEMP-' . uniqid(),

                'payment_date' =>
                    $validated['payment_date'],

                'capital_paid' =>
                    $capitalPaid,

                'interest_paid' =>
                    $interestPaid,

                'total_paid' =>
                    $totalPaid,

                'remaining_balance' =>
                    $newBalance,

                'remarks' =>
                    $validated['remarks'] ?? null,
            ]);


            // =========================================
            // GENERATE FINAL RECEIPT NUMBER
            // =========================================

            $receiptNumber =
                'RCPT-' .
                date(
                    'Ymd',
                    strtotime($validated['payment_date'])
                ) .
                '-' .
                str_pad(
                    $payment->id,
                    6,
                    '0',
                    STR_PAD_LEFT
                );


            $payment->update([
                'receipt_number' =>
                    $receiptNumber,
            ]);


            // =========================================
            // UPDATE LOAN BALANCE + STATUS
            // =========================================

            $loan->update([
                'remaining_balance' =>
                    $newBalance,

                'status' =>
                    $newBalance <= 0
                        ? 'Completed'
                        : 'Active',
            ]);


            // =========================================
            // ACTIVITY LOG
            // =========================================

            ActivityLog::create([
                'employee_id' =>
                    $employeeId,

                'action' =>
                    'Payment Recorded',

                'description' =>
                    'Recorded payment ' .
                    $payment->receipt_number .
                    ' for loan ' .
                    $loan->loan_number .
                    ' - customer ' .
                    $loan->customer->full_name .
                    ' - capital LKR ' .
                    number_format($capitalPaid, 2) .
                    ' - interest LKR ' .
                    number_format($interestPaid, 2) .
                    ' - total LKR ' .
                    number_format($totalPaid, 2) .
                    ' - remaining LKR ' .
                    number_format($newBalance, 2),
            ]);


            return $payment;
        });


        return redirect()
            ->route('payments.index')
            ->with(
                'success',
                'Payment recorded successfully.'
            );
    }


    // =========================================
    // VIEW ONE PAYMENT / RECEIPT
    // =========================================
    public function show(Payment $payment)
    {
        $payment->load('loan.customer');

        return view(
            'payments.show',
            compact('payment')
        );
    }


    // =========================================
    // DOWNLOAD PAYMENT RECEIPT AS PDF
    // =========================================
    public function downloadPdf(Payment $payment)
    {
        $payment->load('loan.customer');

        $pdf = Pdf::loadView(
            'payments.receipt-pdf',
            compact('payment')
        );

        $pdf->setPaper(
            'A4',
            'portrait'
        );

        return $pdf->download(
            $payment->receipt_number . '.pdf'
        );
    }


    // =========================================
    // PAYMENT DELETION NOT ALLOWED
    // =========================================
    public function destroy(Payment $payment)
    {
        abort(
            403,
            'Payment deletion is not allowed.'
        );
    }
}