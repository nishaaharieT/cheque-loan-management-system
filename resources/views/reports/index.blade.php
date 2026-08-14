@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h2>Reports</h2>
        <p>Financial overview of cheque loans and collections</p>
    </div>
</div>


<!-- Date Filter -->
<div class="form-card" style="margin-bottom:25px;">

    <form method="GET"
          action="{{ route('reports.index') }}"
          style="display:flex;gap:15px;align-items:end;flex-wrap:wrap;">

        <div class="form-group" style="margin-bottom:0;">
            <label>From Date</label>

            <input
                type="date"
                name="from_date"
                class="form-control"
                value="{{ $fromDate }}"
            >
        </div>


        <div class="form-group" style="margin-bottom:0;">
            <label>To Date</label>

            <input
                type="date"
                name="to_date"
                class="form-control"
                value="{{ $toDate }}"
            >
        </div>


        <button type="submit" class="btn btn-primary">
            Filter Report
        </button>


        <a href="{{ route('reports.index') }}"
           class="btn"
           style="background:#e5e7eb;color:#374151;">
            Clear
        </a>

    </form>

</div>


<!-- Financial Summary -->
<div class="cards">

    <div class="card">
        <div class="card-body">
            <div class="stat-title">Loans Issued</div>

            <div class="stat-number" style="font-size:21px;">
                LKR {{ number_format($totalLoansIssued, 2) }}
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="stat-title">Capital Collected</div>

            <div class="stat-number"
                 style="font-size:21px;color:#1453a6;">
                LKR {{ number_format($capitalCollected, 2) }}
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="stat-title">Interest Income</div>

            <div class="stat-number"
                 style="font-size:21px;color:#198754;">
                LKR {{ number_format($interestCollected, 2) }}
            </div>
        </div>
    </div>

</div>


<div class="cards">

    <div class="card">
        <div class="card-body">
            <div class="stat-title">Total Collection</div>

            <div class="stat-number" style="font-size:21px;">
                LKR {{ number_format($totalCollected, 2) }}
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="stat-title">Current Outstanding Capital</div>

            <div class="stat-number"
                 style="font-size:21px;color:#dc3545;">
                LKR {{ number_format($outstandingBalance, 2) }}
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="stat-title">Payments Recorded</div>

            <div class="stat-number">
                {{ $payments->count() }}
            </div>
        </div>
    </div>

</div>


<!-- Payment Report -->
<div class="table-card" style="margin-bottom:25px;">

    <div class="table-header">

        <div>
            <h3>Payment Report</h3>

            <p style="color:#6b7280;font-size:13px;margin-top:4px;">
                Payments recorded within the selected period
            </p>
        </div>

    </div>


    @if($payments->count() > 0)

        <div style="overflow-x:auto;">

            <table>

                <thead>
                    <tr>
                        <th>Receipt No</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Loan No</th>
                        <th>Cheque No</th>
                        <th>Capital</th>
                        <th>Interest</th>
                        <th>Total</th>
                    </tr>
                </thead>


                <tbody>

                    @foreach($payments as $payment)

                        <tr>

                            <td>
                                <strong>
                                    {{ $payment->receipt_number }}
                                </strong>
                            </td>

                            <td>
                                {{ $payment->payment_date->format('Y-m-d') }}
                            </td>

                            <td>
                                {{ $payment->loan->customer->full_name }}
                            </td>

                            <td>
                                {{ $payment->loan->loan_number }}
                            </td>

                            <td>
                                {{ $payment->loan->cheque_number }}
                            </td>

                            <td>
                                LKR {{ number_format($payment->capital_paid, 2) }}
                            </td>

                            <td>
                                LKR {{ number_format($payment->interest_paid, 2) }}
                            </td>

                            <td>
                                <strong>
                                    LKR {{ number_format($payment->total_paid, 2) }}
                                </strong>
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div style="padding:35px;text-align:center;color:#6b7280;">
            No payments found for the selected period.
        </div>

    @endif

</div>


<!-- Loan Report -->
<div class="table-card">

    <div class="table-header">

        <div>
            <h3>Loan Report</h3>

            <p style="color:#6b7280;font-size:13px;margin-top:4px;">
                Cheque loans issued within the selected period
            </p>
        </div>

    </div>


    @if($loans->count() > 0)

        <div style="overflow-x:auto;">

            <table>

                <thead>
                    <tr>
                        <th>Loan No</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Cheque No</th>
                        <th>Loan Amount</th>
                        <th>Interest</th>
                        <th>Remaining</th>
                        <th>Status</th>
                    </tr>
                </thead>


                <tbody>

                    @foreach($loans as $loan)

                        <tr>

                            <td>
                                <strong>
                                    {{ $loan->loan_number }}
                                </strong>
                            </td>

                            <td>
                                {{ $loan->loan_date->format('Y-m-d') }}
                            </td>

                            <td>
                                {{ $loan->customer->full_name }}
                            </td>

                            <td>
                                {{ $loan->cheque_number }}
                            </td>

                            <td>
                                LKR {{ number_format($loan->loan_amount, 2) }}
                            </td>

                            <td>
                                {{ number_format($loan->interest_rate, 2) }}%
                            </td>

                            <td>
                                LKR {{ number_format($loan->remaining_balance, 2) }}
                            </td>

                            <td>
                                @if($loan->status === 'Active')
                                    <span class="badge badge-success">
                                        Active
                                    </span>
                                @elseif($loan->status === 'Completed')
                                    <span class="badge"
                                          style="background:#dbeafe;color:#1d4ed8;">
                                        Completed
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        {{ $loan->status }}
                                    </span>
                                @endif
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div style="padding:35px;text-align:center;color:#6b7280;">
            No loans found for the selected period.
        </div>

    @endif

</div>

@endsection