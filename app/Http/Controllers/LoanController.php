<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Loan;
use App\Models\LoanType;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    // Display all loans
    public function index()
    {
        $loans = Loan::with(['customer', 'loanType'])
            ->latest()
            ->get();

        return view('loans.index', compact('loans'));
    }


    // Show Create Loan Form
    public function create()
    {
        $customers = Customer::where('status', 'Active')
            ->orderBy('full_name')
            ->get();

        $loanTypes = LoanType::where('status', 'Active')
            ->orderBy('duration_months')
            ->get();

        return view('loans.create', compact(
            'customers',
            'loanTypes'
        ));
    }


    // Store New Loan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' =>
                'required|exists:customers,id',

            'cheque_number' =>
                'required|string|max:255|unique:loans,cheque_number',

            'loan_amount' =>
                'required|numeric|min:1',

            'duration_months' =>
                'required|integer|min:1',

            'interest_rate' =>
                'required|numeric|min:0|max:100',

            'loan_date' =>
                'required|date',
        ]);


        $loanAmount =
            round((float) $validated['loan_amount'], 2);

        $duration =
            (int) $validated['duration_months'];

        $interestRate =
            (float) $validated['interest_rate'];


        // Calculations
        $monthlyCapital =
            round($loanAmount / $duration, 2);

        $monthlyInterest =
            round(
                $loanAmount * ($interestRate / 100),
                2
            );

        $monthlyPayment =
            round(
                $monthlyCapital + $monthlyInterest,
                2
            );


        $employeeId =
            auth()->user()->fresh()->employee_id;


        try {

            DB::transaction(function () use (
                $validated,
                $loanAmount,
                $duration,
                $interestRate,
                $monthlyCapital,
                $monthlyInterest,
                $monthlyPayment,
                $employeeId
            ) {

                /*
                 * Lock customer while checking/creating loan.
                 * Helps prevent two employees creating
                 * two active loans at the same time.
                 */
                $customer = Customer::where(
                    'id',
                    $validated['customer_id']
                )
                    ->lockForUpdate()
                    ->firstOrFail();


                // One active loan per customer
                $customerHasActiveLoan =
                    Loan::where(
                        'customer_id',
                        $customer->id
                    )
                    ->whereIn(
                        'status',
                        ['Pending', 'Active']
                    )
                    ->exists();


                if ($customerHasActiveLoan) {

                    throw new \Exception(
                        'CUSTOMER_HAS_ACTIVE_LOAN'
                    );
                }


                /*
                 * Create with temporary unique number.
                 * After database gives us ID,
                 * create final LN number from that ID.
                 */
                $loan = Loan::create([
                    'customer_id' =>
                        $customer->id,

                    'loan_type_id' =>
                        null,

                    'loan_number' =>
                        'TEMP-' . uniqid(),

                    'cheque_number' =>
                        $validated['cheque_number'],

                    'loan_amount' =>
                        $loanAmount,

                    'interest_rate' =>
                        $interestRate,

                    'duration_months' =>
                        $duration,

                    'monthly_capital' =>
                        $monthlyCapital,

                    'monthly_interest' =>
                        $monthlyInterest,

                    'monthly_payment' =>
                        $monthlyPayment,

                    'remaining_balance' =>
                        $loanAmount,

                    'loan_date' =>
                        $validated['loan_date'],

                    'status' =>
                        'Active',
                ]);


                // Safe final loan number
                $loanNumber =
                    'LN-' .
                    str_pad(
                        $loan->id,
                        6,
                        '0',
                        STR_PAD_LEFT
                    );


                $loan->update([
                    'loan_number' =>
                        $loanNumber,
                ]);


                // Activity Log
                ActivityLog::create([
                    'employee_id' =>
                        $employeeId,

                    'action' =>
                        'Loan Created',

                    'description' =>
                        'Created loan ' .
                        $loan->loan_number .
                        ' for customer ' .
                        $customer->customer_code .
                        ' - ' .
                        $customer->full_name .
                        ' with cheque ' .
                        $loan->cheque_number .
                        ' - duration ' .
                        $duration .
                        ' months - interest ' .
                        number_format(
                            $interestRate,
                            2
                        ) .
                        '% - amount LKR ' .
                        number_format(
                            $loanAmount,
                            2
                        ),
                ]);

            });

        } catch (\Exception $e) {

            if (
                $e->getMessage()
                === 'CUSTOMER_HAS_ACTIVE_LOAN'
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'customer_id' =>
                            'This customer already has an active loan.',
                    ]);
            }

            throw $e;
        }


        return redirect()
            ->route('loans.index')
            ->with(
                'success',
                'Loan created successfully.'
            );
    }


    // Display One Loan
    public function show(Loan $loan)
    {
        $loan->load([
            'customer',
            'payments'
        ]);

        return view(
            'loans.show',
            compact('loan')
        );
    }


    // Show Edit Loan Form
    public function edit(Loan $loan)
    {
        $customers =
            Customer::where('status', 'Active')
                ->orderBy('full_name')
                ->get();

        return view(
            'loans.edit',
            compact(
                'loan',
                'customers'
            )
        );
    }


    // Update Loan
    public function update(
        Request $request,
        Loan $loan
    ) {

        $validated = $request->validate([
            'cheque_number' =>
                'required|string|max:255|unique:loans,cheque_number,' .
                $loan->id,

            'loan_amount' =>
                'required|numeric|min:1',

            'duration_months' =>
                'required|integer|min:1',

            'interest_rate' =>
                'required|numeric|min:0|max:100',

            'loan_date' =>
                'required|date',

            'status' =>
                'required|in:Pending,Active,Completed,Cancelled',
        ]);


        /*
         * Once payments exist, do not allow
         * financial terms to be changed.
         */
        if ($loan->payments()->exists()) {

            return back()
                ->withInput()
                ->withErrors([
                    'loan_amount' =>
                        'This loan already has payments. Financial terms cannot be changed directly.',
                ]);
        }


        $loanAmount =
            round(
                (float) $validated['loan_amount'],
                2
            );

        $duration =
            (int) $validated['duration_months'];

        $interestRate =
            (float) $validated['interest_rate'];


        $monthlyCapital =
            round(
                $loanAmount / $duration,
                2
            );

        $monthlyInterest =
            round(
                $loanAmount *
                ($interestRate / 100),
                2
            );

        $monthlyPayment =
            round(
                $monthlyCapital +
                $monthlyInterest,
                2
            );


        DB::transaction(function () use (
            $loan,
            $validated,
            $loanAmount,
            $duration,
            $interestRate,
            $monthlyCapital,
            $monthlyInterest,
            $monthlyPayment
        ) {

            $loan->update([
                'cheque_number' =>
                    $validated['cheque_number'],

                'loan_amount' =>
                    $loanAmount,

                'interest_rate' =>
                    $interestRate,

                'duration_months' =>
                    $duration,

                'monthly_capital' =>
                    $monthlyCapital,

                'monthly_interest' =>
                    $monthlyInterest,

                'monthly_payment' =>
                    $monthlyPayment,

                'remaining_balance' =>
                    $loanAmount,

                'loan_date' =>
                    $validated['loan_date'],

                'status' =>
                    $validated['status'],
            ]);


            $employeeId =
                auth()->user()
                    ->fresh()
                    ->employee_id;


            ActivityLog::create([
                'employee_id' =>
                    $employeeId,

                'action' =>
                    'Loan Updated',

                'description' =>
                    'Updated loan ' .
                    $loan->loan_number .
                    ' - cheque ' .
                    $loan->cheque_number .
                    ' - duration ' .
                    $loan->duration_months .
                    ' months - interest ' .
                    number_format(
                        $loan->interest_rate,
                        2
                    ) .
                    '% - status ' .
                    $loan->status,
            ]);

        });


        return redirect()
            ->route('loans.index')
            ->with(
                'success',
                'Loan updated successfully.'
            );
    }


    // Loan deletion is not allowed
    public function destroy(Loan $loan)
    {
        abort(
            403,
            'Loan deletion is not allowed.'
        );
    }
}