@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h2>Customer Details</h2>
        <p>View customer information, cheque loan details and payment history</p>
    </div>

    <a href="{{ route('customers.index') }}"
       class="btn"
       style="background:#e5e7eb;color:#374151;">
        Back to Customers
    </a>
</div>


<!-- Customer Summary -->
<div class="cards">

    <div class="card">
        <div class="card-body">
            <div class="stat-title">Customer Code</div>

            <div class="stat-number" style="font-size:22px;">
                {{ $customer->customer_code }}
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="stat-title">Full Name</div>

            <div class="stat-number" style="font-size:22px;">
                {{ $customer->full_name }}
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">

            <div class="stat-title">Status</div>

            @if($customer->status === 'Active')
                <span class="badge badge-success">
                    Active
                </span>
            @else
                <span class="badge badge-danger">
                    Inactive
                </span>
            @endif

        </div>
    </div>

</div>


<!-- Customer Information -->
<div class="table-card" style="margin-bottom:25px;">

    <div class="table-header">
        <h3>Customer Information</h3>
    </div>

    <table>

        <tbody>

            <tr>
                <th>NIC</th>
                <td>{{ $customer->nic }}</td>
            </tr>

            <tr>
                <th>Phone</th>
                <td>{{ $customer->phone }}</td>
            </tr>

            <tr>
                <th>Address</th>
                <td>{{ $customer->address }}</td>
            </tr>

        </tbody>

    </table>

</div>


<!-- Cheque Loan History -->
<div class="table-card">

    <div class="table-header">

        <div>
            <h3>Cheque Loan History</h3>

            <p style="color:#6b7280;font-size:13px;margin-top:4px;">
                All cheque loans taken by this customer
            </p>
        </div>

    </div>


    @if($customer->loans->count() > 0)

        <div style="overflow-x:auto;">

            <table>

                <thead>

                    <tr>
                        <th>Loan No</th>
                        <th>Cheque No</th>
                        <th>Amount</th>
                        <th>Duration</th>
                        <th>Interest</th>
                        <th>Monthly Capital</th>
                        <th>Monthly Interest</th>
                        <th>Monthly Payment</th>
                        <th>Remaining</th>
                        <th>Status</th>
                    </tr>

                </thead>


                <tbody>

                    @foreach($customer->loans as $loan)

                        <tr>

                            <td>
                                <strong>
                                    {{ $loan->loan_number }}
                                </strong>
                            </td>

                            <td>
                                {{ $loan->cheque_number }}
                            </td>

                            <td>
                                LKR {{ number_format($loan->loan_amount, 2) }}
                            </td>

                            <td>
                                {{ $loan->duration_months }} Months
                            </td>

                            <td>
                                {{ number_format($loan->interest_rate, 2) }}%
                            </td>

                            <td>
                                LKR {{ number_format($loan->monthly_capital, 2) }}
                            </td>

                            <td>
                                LKR {{ number_format($loan->monthly_interest, 2) }}
                            </td>

                            <td>
                                LKR {{ number_format($loan->monthly_payment, 2) }}
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

                                @elseif($loan->status === 'Pending')

                                    <span class="badge"
                                          style="background:#fef3c7;color:#92400e;">
                                        Pending
                                    </span>

                                @else

                                    <span class="badge badge-danger">
                                        Cancelled
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div style="padding:40px;text-align:center;color:#6b7280;">
            This customer has no cheque loans yet.
        </div>

    @endif

</div>


<!-- Payment History -->
<div class="table-card" style="margin-top:25px;">

    <div class="table-header">

        <div>
            <h3>Payment History</h3>

            <p style="color:#6b7280;font-size:13px;margin-top:4px;">
                All payments made by this customer
            </p>
        </div>

    </div>


    @php
        $allPayments = $customer->loans
            ->flatMap(function ($loan) {
                return $loan->payments;
            })
            ->sortByDesc('payment_date');
    @endphp


    @if($allPayments->count() > 0)

        <div style="overflow-x:auto;">

            <table>

                <thead>

                    <tr>
                        <th>Receipt No</th>
                        <th>Loan No</th>
                        <th>Cheque No</th>
                        <th>Date</th>
                        <th>Capital Paid</th>
                        <th>Interest Paid</th>
                        <th>Total Paid</th>
                        <th>Remaining</th>
                    </tr>

                </thead>


                <tbody>

                    @foreach($allPayments as $payment)

                        <tr>

                            <td>
                                <strong>
                                    {{ $payment->receipt_number }}
                                </strong>
                            </td>

                            <td>
                                {{ $payment->loan->loan_number }}
                            </td>

                            <td>
                                {{ $payment->loan->cheque_number }}
                            </td>

                            <td>
                                {{ $payment->payment_date->format('Y-m-d') }}
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
                                LKR {{ number_format($payment->remaining_balance, 2) }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div style="padding:40px;text-align:center;color:#6b7280;">
            No payments recorded for this customer yet.
        </div>

    @endif

</div>

@endsection