@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h2>Record Payment</h2>
        <p>Record a payment for an active cheque loan</p>
    </div>
</div>


@if ($errors->any())

    <div class="alert alert-danger">

        <strong>Please fix the following:</strong>

        <ul style="margin-top:8px; margin-left:20px;">

            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


<div class="form-card">

    <form
        action="{{ route('payments.store') }}"
        method="POST"
    >

        @csrf


        <!-- =========================================
             ACTIVE LOAN
        ========================================== -->

        <div class="form-group">

            <label>Active Loan</label>

            <select
                name="loan_id"
                id="loan_id"
                class="form-control"
                required
            >

                <option value="">
                    Select Active Loan
                </option>


                @foreach($loans as $loan)

                    <option
                        value="{{ $loan->id }}"

                        data-capital="{{ number_format((float) $loan->monthly_capital, 2, '.', '') }}"

                        data-interest="{{ number_format((float) $loan->monthly_interest, 2, '.', '') }}"

                        data-payment="{{ number_format((float) $loan->monthly_payment, 2, '.', '') }}"

                        data-balance="{{ number_format((float) $loan->remaining_balance, 2, '.', '') }}"

                        {{ old('loan_id', $selectedLoanId) == $loan->id ? 'selected' : '' }}
                    >

                        {{ $loan->loan_number }}
                        -
                        {{ $loan->customer->full_name }}
                        -
                        {{ $loan->cheque_number }}

                    </option>

                @endforeach

            </select>

        </div>



        <!-- =========================================
             LOAN SUMMARY
        ========================================== -->

        <div class="card" style="margin:20px 0;">

            <div class="card-body">

                <h3 style="margin-bottom:15px;">
                    Loan Summary
                </h3>


                <table>

                    <tr>

                        <th>
                            Expected Monthly Capital
                        </th>

                        <td id="expectedCapital">
                            LKR 0.00
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Expected Monthly Interest
                        </th>

                        <td id="expectedInterest">
                            LKR 0.00
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Expected Monthly Payment
                        </th>

                        <td id="expectedPayment">
                            <strong>
                                LKR 0.00
                            </strong>
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Current Remaining Capital
                        </th>

                        <td id="currentBalance">

                            <strong>
                                LKR 0.00
                            </strong>

                        </td>

                    </tr>

                </table>

            </div>

        </div>



        <!-- =========================================
             PAYMENT DATE
        ========================================== -->

        <div class="form-group">

            <label>
                Payment Date
            </label>

            <input
                type="date"
                name="payment_date"
                class="form-control"
                value="{{ old('payment_date', date('Y-m-d')) }}"
                required
            >

        </div>



        <!-- =========================================
             CAPITAL PAID
        ========================================== -->

        <div class="form-group">

            <label>
                Capital Paid (LKR)
            </label>

            <input
                type="number"
                step="0.01"
                min="0"
                id="capital_paid"
                name="capital_paid"
                class="form-control"
                value="{{ old('capital_paid') }}"
                required
            >

        </div>



        <!-- =========================================
             INTEREST PAID
        ========================================== -->

        <div class="form-group">

            <label>
                Interest Paid (LKR)
            </label>

            <input
                type="number"
                step="0.01"
                min="0"
                id="interest_paid"
                name="interest_paid"
                class="form-control"
                value="{{ old('interest_paid') }}"
                required
            >

        </div>



        <!-- =========================================
             TOTAL RECEIVED
        ========================================== -->

        <div class="form-group">

            <label>
                Total Received
            </label>

            <input
                type="text"
                id="total_received"
                class="form-control"
                value="LKR 0.00"
                readonly
            >

        </div>



        <!-- =========================================
             REMARKS
        ========================================== -->

        <div class="form-group">

            <label>
                Remarks
            </label>

            <textarea
                name="remarks"
                class="form-control"
                rows="3"
                placeholder="Optional notes"
            >{{ old('remarks') }}</textarea>

        </div>



        <!-- =========================================
             BUTTONS
        ========================================== -->

        <div style="display:flex; gap:10px;">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Record Payment
            </button>


            <a
                href="{{ route('payments.index') }}"
                class="btn"
                style="background:#e5e7eb;color:#374151;"
            >
                Cancel
            </a>

        </div>

    </form>

</div>



<script>

const loanSelect =
    document.getElementById('loan_id');

const capitalInput =
    document.getElementById('capital_paid');

const interestInput =
    document.getElementById('interest_paid');

const totalInput =
    document.getElementById('total_received');


/*
|--------------------------------------------------------------------------
| OLD INPUT DETECTION
|--------------------------------------------------------------------------
|
| If Laravel returned the form because of validation,
| preserve the user's entered values on the initial load.
|
*/

const hasOldCapital =
    @json(old('capital_paid') !== null);

const hasOldInterest =
    @json(old('interest_paid') !== null);



/*
|--------------------------------------------------------------------------
| MONEY FORMAT
|--------------------------------------------------------------------------
*/

function money(value)
{
    const number =
        Number(value || 0);

    return 'LKR ' +
        number.toLocaleString(
            'en-US',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
}



/*
|--------------------------------------------------------------------------
| ROUND TO 2 DECIMAL PLACES
|--------------------------------------------------------------------------
*/

function roundMoney(value)
{
    return Math.round(
        (Number(value) + Number.EPSILON) * 100
    ) / 100;
}



/*
|--------------------------------------------------------------------------
| UPDATE LOAN SUMMARY
|--------------------------------------------------------------------------
|
| forceAutoFill = true
|
| When employee selects another loan, payment fields are
| always reset using the selected loan's correct values.
|
| forceAutoFill = false
|
| On initial validation-error load, old input is preserved.
|
*/

function updateLoanSummary(forceAutoFill = false)
{
    const selected =
        loanSelect.options[
            loanSelect.selectedIndex
        ];


    if (!selected || !selected.value)
    {
        document
            .getElementById('expectedCapital')
            .innerText =
            'LKR 0.00';

        document
            .getElementById('expectedInterest')
            .innerText =
            'LKR 0.00';

        document
            .getElementById('expectedPayment')
            .innerText =
            'LKR 0.00';

        document
            .getElementById('currentBalance')
            .innerText =
            'LKR 0.00';


        if (forceAutoFill)
        {
            capitalInput.value = '';
            interestInput.value = '';
        }


        calculateTotal();

        return;
    }


    const expectedCapital =
        roundMoney(
            parseFloat(
                selected.dataset.capital
            ) || 0
        );


    const expectedInterest =
        roundMoney(
            parseFloat(
                selected.dataset.interest
            ) || 0
        );


    const expectedPayment =
        roundMoney(
            parseFloat(
                selected.dataset.payment
            ) || 0
        );


    const remainingBalance =
        roundMoney(
            parseFloat(
                selected.dataset.balance
            ) || 0
        );


    /*
     * Final instalment protection:
     *
     * If remaining capital is less than the
     * normal monthly capital, use remaining capital.
     */
    const suggestedCapital =
        roundMoney(
            Math.min(
                expectedCapital,
                remainingBalance
            )
        );


    /*
     * Display loan summary.
     */
    document
        .getElementById('expectedCapital')
        .innerText =
        money(expectedCapital);


    document
        .getElementById('expectedInterest')
        .innerText =
        money(expectedInterest);


    document
        .getElementById('expectedPayment')
        .innerText =
        money(expectedPayment);


    document
        .getElementById('currentBalance')
        .innerText =
        money(remainingBalance);



    /*
     * When employee selects a loan manually,
     * always insert clean calculated values.
     */
    if (forceAutoFill)
    {
        capitalInput.value =
            suggestedCapital.toFixed(2);

        interestInput.value =
            expectedInterest.toFixed(2);
    }
    else
    {
        /*
         * Initial page load:
         *
         * Keep Laravel old values after validation.
         * Otherwise use the calculated values.
         */
        if (!hasOldCapital)
        {
            capitalInput.value =
                suggestedCapital.toFixed(2);
        }


        if (!hasOldInterest)
        {
            interestInput.value =
                expectedInterest.toFixed(2);
        }
    }


    calculateTotal();
}



/*
|--------------------------------------------------------------------------
| CALCULATE TOTAL RECEIVED
|--------------------------------------------------------------------------
*/

function calculateTotal()
{
    const capital =
        roundMoney(
            parseFloat(
                capitalInput.value
            ) || 0
        );


    const interest =
        roundMoney(
            parseFloat(
                interestInput.value
            ) || 0
        );


    const total =
        roundMoney(
            capital + interest
        );


    totalInput.value =
        money(total);
}



/*
|--------------------------------------------------------------------------
| EVENTS
|--------------------------------------------------------------------------
*/

loanSelect.addEventListener(
    'change',
    function ()
    {
        /*
         * Loan changed manually:
         * force correct auto-fill values.
         */
        updateLoanSummary(true);
    }
);


capitalInput.addEventListener(
    'input',
    calculateTotal
);


interestInput.addEventListener(
    'input',
    calculateTotal
);



/*
|--------------------------------------------------------------------------
| INITIAL PAGE LOAD
|--------------------------------------------------------------------------
*/

updateLoanSummary(false);

calculateTotal();

</script>

@endsection