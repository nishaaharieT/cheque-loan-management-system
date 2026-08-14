@extends('layouts.app')

@section('content')

<div class="page-header">

    <div>
        <h2>Payment Receipt</h2>
        <p>View payment receipt details</p>
    </div>

    <div style="display:flex; gap:10px;">

        <!-- Download PDF -->
        <a
            href="{{ route('payments.download', $payment) }}"
            class="btn btn-primary"
        >
            Download PDF
        </a>

        <!-- Back -->
        <a
            href="{{ route('payments.index') }}"
            class="btn"
            style="background:#e5e7eb;color:#374151;"
        >
            ← Back to Payments
        </a>

    </div>

</div>


<div class="form-card">

    <!-- =====================================
         RECEIPT HEADER
    ====================================== -->

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        border-bottom:1px solid #e5e7eb;
        padding-bottom:20px;
        margin-bottom:25px;
    ">

        <div>

            <h2 style="
                margin:0;
                color:#1e5aad;
                font-size:28px;
            ">
                🏦 Lendora
            </h2>

            <p style="
                margin-top:5px;
                color:#6b7280;
            ">
                Cheque Loan Management System
            </p>

        </div>


        <div style="text-align:right;">

            <h2 style="margin:0;">
                PAYMENT RECEIPT
            </h2>

            <p style="
                margin-top:5px;
                color:#6b7280;
            ">
                {{ $payment->receipt_number }}
            </p>

        </div>

    </div>


    <!-- =====================================
         CUSTOMER + LOAN DETAILS
    ====================================== -->

    <div style="
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:40px;
        margin-bottom:30px;
    ">

        <!-- CUSTOMER DETAILS -->

        <div>

            <h3 style="margin-bottom:15px;">
                Customer Details
            </h3>

            <p>
                <strong>Name:</strong>
                {{ $payment->loan->customer->full_name }}
            </p>

            <p>
                <strong>Customer Code:</strong>
                {{ $payment->loan->customer->customer_code }}
            </p>

            <p>
                <strong>NIC:</strong>
                {{ $payment->loan->customer->nic }}
            </p>

            <p>
                <strong>Phone:</strong>
                {{ $payment->loan->customer->phone }}
            </p>

        </div>


        <!-- LOAN DETAILS -->

        <div>

            <h3 style="margin-bottom:15px;">
                Loan Details
            </h3>

            <p>
                <strong>Loan No:</strong>
                {{ $payment->loan->loan_number }}
            </p>

            <p>
                <strong>Cheque No:</strong>
                {{ $payment->loan->cheque_number }}
            </p>

            <p>
                <strong>Payment Date:</strong>
                {{ $payment->payment_date->format('Y-m-d') }}
            </p>

            <p>
                <strong>Loan Status:</strong>

                @if($payment->loan->status === 'Completed')

                    <span style="
                        background:#dbeafe;
                        color:#1d4ed8;
                        padding:5px 10px;
                        border-radius:20px;
                        font-weight:bold;
                    ">
                        Completed
                    </span>

                @else

                    <span style="
                        background:#dcfce7;
                        color:#15803d;
                        padding:5px 10px;
                        border-radius:20px;
                        font-weight:bold;
                    ">
                        Active
                    </span>

                @endif

            </p>

        </div>

    </div>


    <!-- =====================================
         PAYMENT DETAILS
    ====================================== -->

    <div class="table-card" style="margin-top:0;">

        <table>

            <thead>

                <tr>

                    <th>
                        Description
                    </th>

                    <th style="text-align:right;">
                        Amount
                    </th>

                </tr>

            </thead>


            <tbody>

                <tr>

                    <td>
                        Capital Paid
                    </td>

                    <td style="text-align:right;">
                        LKR {{ number_format($payment->capital_paid, 2) }}
                    </td>

                </tr>


                <tr>

                    <td>
                        Interest Paid
                    </td>

                    <td style="text-align:right;">
                        LKR {{ number_format($payment->interest_paid, 2) }}
                    </td>

                </tr>


                <tr>

                    <td>
                        <strong>
                            Total Paid
                        </strong>
                    </td>

                    <td style="
                        text-align:right;
                        font-weight:bold;
                        font-size:17px;
                    ">
                        LKR {{ number_format($payment->total_paid, 2) }}
                    </td>

                </tr>


                <tr>

                    <td>
                        Remaining Capital
                    </td>

                    <td style="text-align:right;">
                        LKR {{ number_format($payment->remaining_balance, 2) }}
                    </td>

                </tr>

            </tbody>

        </table>

    </div>


    <!-- =====================================
         REMARKS
    ====================================== -->

    @if($payment->remarks)

        <div style="margin-top:25px;">

            <h3 style="margin-bottom:10px;">
                Remarks
            </h3>

            <div style="
                background:#f9fafb;
                padding:15px;
                border-radius:8px;
            ">
                {{ $payment->remarks }}
            </div>

        </div>

    @endif


    <!-- =====================================
         FOOTER
    ====================================== -->

    <div style="
        display:flex;
        justify-content:space-between;
        border-top:1px solid #e5e7eb;
        margin-top:30px;
        padding-top:15px;
        color:#6b7280;
        font-size:13px;
    ">

        <span>
            Generated by Lendora
        </span>

        <span>
            Receipt No:
            {{ $payment->receipt_number }}
        </span>

    </div>

</div>

@endsection