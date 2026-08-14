@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h2>Create New Loan</h2>
        <p>Enter cheque loan details and review the automatic calculation.</p>
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

    <form action="{{ route('loans.store') }}" method="POST">

        @csrf


        <!-- Customer -->
        <div class="form-group">
            <label for="customer_id">Customer</label>

            <select
                name="customer_id"
                id="customer_id"
                class="form-control"
                required
            >
                <option value="">
                    Select Customer
                </option>

                @foreach($customers as $customer)

                    <option
                        value="{{ $customer->id }}"
                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}
                    >
                        {{ $customer->customer_code }}
                        -
                        {{ $customer->full_name }}
                    </option>

                @endforeach

            </select>
        </div>


        <!-- Cheque Number -->
        <div class="form-group">

            <label for="cheque_number">
                Cheque Number
            </label>

            <input
                type="text"
                id="cheque_number"
                name="cheque_number"
                class="form-control"
                value="{{ old('cheque_number') }}"
                placeholder="Enter cheque number"
                required
            >

        </div>


        <!-- Loan Amount -->
        <div class="form-group">

            <label for="loan_amount">
                Loan Amount (LKR)
            </label>

            <input
                type="number"
                step="0.01"
                min="1"
                id="loan_amount"
                name="loan_amount"
                class="form-control"
                value="{{ old('loan_amount') }}"
                placeholder="Enter loan amount"
                required
            >

        </div>


        <!-- Custom Duration -->
        <div class="form-group">

            <label for="duration_months">
                Duration (Months)
            </label>

            <input
                type="number"
                min="1"
                step="1"
                id="duration_months"
                name="duration_months"
                class="form-control"
                value="{{ old('duration_months') }}"
                placeholder="Example: 4"
                required
            >

            <small style="color:#6b7280;">
                Enter the agreed loan duration in months.
            </small>

        </div>


        <!-- Custom Interest -->
        <div class="form-group">

            <label for="interest_rate">
                Interest Rate (%)
            </label>

            <input
                type="number"
                step="0.01"
                min="0"
                max="100"
                id="interest_rate"
                name="interest_rate"
                class="form-control"
                value="{{ old('interest_rate') }}"
                placeholder="Example: 6.5"
                required
            >

            <small style="color:#6b7280;">
                Enter the agreed monthly interest percentage for this loan.
            </small>

        </div>


        <!-- Loan Date -->
        <div class="form-group">

            <label for="loan_date">
                Loan Date
            </label>

            <input
                type="date"
                id="loan_date"
                name="loan_date"
                class="form-control"
                value="{{ old('loan_date', date('Y-m-d')) }}"
                required
            >

        </div>


        <!-- Calculation Preview -->
        <div class="card" style="margin-top:25px;">

            <div class="card-body">

                <h3 style="margin-bottom:18px;">
                    Calculation Preview
                </h3>

                <table>

                    <tr>
                        <th>Monthly Capital</th>
                        <td id="preview_capital">
                            LKR 0.00
                        </td>
                    </tr>

                    <tr>
                        <th>Monthly Interest</th>
                        <td id="preview_interest">
                            LKR 0.00
                        </td>
                    </tr>

                    <tr>
                        <th>Monthly Payment</th>
                        <td>
                            <strong id="preview_payment">
                                LKR 0.00
                            </strong>
                        </td>
                    </tr>

                    <tr>
                        <th>Total Interest</th>
                        <td id="preview_total_interest">
                            LKR 0.00
                        </td>
                    </tr>

                    <tr>
                        <th>Total Payable</th>
                        <td>
                            <strong id="preview_total_payable">
                                LKR 0.00
                            </strong>
                        </td>
                    </tr>

                </table>

            </div>

        </div>


        <!-- Buttons -->
        <div style="margin-top:25px; display:flex; gap:10px;">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Confirm & Create Loan
            </button>

            <a
                href="{{ route('loans.index') }}"
                class="btn"
                style="background:#e5e7eb;color:#374151;"
            >
                Cancel
            </a>

        </div>

    </form>

</div>


<script>

function formatMoney(value) {

    return 'LKR ' + Number(value || 0).toLocaleString(
        'en-US',
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }
    );
}


function calculateLoan() {

    const amount =
        parseFloat(
            document.getElementById('loan_amount').value
        ) || 0;

    const duration =
        parseInt(
            document.getElementById('duration_months').value
        ) || 0;

    const rate =
        parseFloat(
            document.getElementById('interest_rate').value
        ) || 0;


    let monthlyCapital = 0;
    let monthlyInterest = 0;
    let monthlyPayment = 0;
    let totalInterest = 0;
    let totalPayable = 0;


    if (
        amount > 0 &&
        duration > 0 &&
        rate >= 0
    ) {

        // Capital divided equally across months
        monthlyCapital =
            amount / duration;

        // Monthly interest based on original loan amount
        monthlyInterest =
            amount * (rate / 100);

        // Monthly amount customer should pay
        monthlyPayment =
            monthlyCapital + monthlyInterest;

        // Total interest over whole duration
        totalInterest =
            monthlyInterest * duration;

        // Full amount payable
        totalPayable =
            amount + totalInterest;
    }


    document.getElementById(
        'preview_capital'
    ).innerText =
        formatMoney(monthlyCapital);


    document.getElementById(
        'preview_interest'
    ).innerText =
        formatMoney(monthlyInterest);


    document.getElementById(
        'preview_payment'
    ).innerText =
        formatMoney(monthlyPayment);


    document.getElementById(
        'preview_total_interest'
    ).innerText =
        formatMoney(totalInterest);


    document.getElementById(
        'preview_total_payable'
    ).innerText =
        formatMoney(totalPayable);
}


document
    .getElementById('loan_amount')
    .addEventListener(
        'input',
        calculateLoan
    );


document
    .getElementById('duration_months')
    .addEventListener(
        'input',
        calculateLoan
    );


document
    .getElementById('interest_rate')
    .addEventListener(
        'input',
        calculateLoan
    );


calculateLoan();

</script>

@endsection