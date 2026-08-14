@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h2>Dashboard</h2>
        <p>Overview of customers, cheque loans and payments</p>
    </div>
</div>


<!-- First Row -->
<div class="cards">

    <div class="card">
        <div class="card-body">
            <div class="stat-title">
                Total Customers
            </div>

            <div class="stat-number">
                {{ $totalCustomers }}
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="stat-title">
                Active Loans
            </div>

            <div class="stat-number" style="color:#198754;">
                {{ $activeLoans }}
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="stat-title">
                Employees
            </div>

            <div class="stat-number">
                {{ $employees }}
            </div>
        </div>
    </div>

</div>


<!-- Second Row -->
<div class="cards">

    <div class="card">
        <div class="card-body">
            <div class="stat-title">
                Total Loan Amount
            </div>

            <div class="stat-number" style="font-size:22px;">
                LKR {{ number_format($totalLoanAmount, 2) }}
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="stat-title">
                Outstanding Capital
            </div>

            <div class="stat-number"
                 style="font-size:22px;color:#dc3545;">
                LKR {{ number_format($outstandingBalance, 2) }}
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="stat-title">
                Today's Collection
            </div>

            <div class="stat-number"
                 style="font-size:22px;color:#1453a6;">
                LKR {{ number_format($todayCollection, 2) }}
            </div>
        </div>
    </div>

</div>


<!-- Third Row -->
<div class="cards">

    <div class="card">
        <div class="card-body">
            <div class="stat-title">
                Capital Collected
            </div>

            <div class="stat-number" style="font-size:22px;">
                LKR {{ number_format($capitalCollected, 2) }}
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="stat-title">
                Interest Income
            </div>

            <div class="stat-number"
                 style="font-size:22px;color:#198754;">
                LKR {{ number_format($interestCollected, 2) }}
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="stat-title">
                Total Collections
            </div>

            <div class="stat-number" style="font-size:22px;">
                LKR {{ number_format($totalPayments, 2) }}
            </div>
        </div>
    </div>

</div>


<!-- Recent Payments -->
<div class="table-card">

    <div class="table-header">
        <div>
            <h3>Recent Payments</h3>

            <p style="color:#6b7280;font-size:13px;margin-top:4px;">
                Latest cheque loan payment transactions
            </p>
        </div>
    </div>


    @if($recentPayments->count() > 0)

        <div style="overflow-x:auto;">

            <table>

                <thead>
                    <tr>
                        <th>Receipt</th>
                        <th>Customer</th>
                        <th>Loan No</th>
                        <th>Cheque No</th>
                        <th>Capital</th>
                        <th>Interest</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($recentPayments as $payment)

                        <tr>

                            <td>
                                <strong>
                                    {{ $payment->receipt_number }}
                                </strong>
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

                            <td>
                                {{ $payment->payment_date->format('Y-m-d') }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div style="padding:40px;text-align:center;color:#6b7280;">
            No payments recorded yet.
        </div>

    @endif

</div>

@endsection