<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        {{ $payment->receipt_number }}
    </title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 13px;
            line-height: 1.5;
            margin: 30px;
        }

        .header-table {
            width: 100%;
            margin-bottom: 25px;
        }

        .header-table td {
            vertical-align: top;
        }

        .brand {
            font-size: 26px;
            font-weight: bold;
            color: #1453a6;
        }

        .subtitle {
            color: #6b7280;
            margin-top: 4px;
        }

        .receipt-title {
            text-align: right;
            font-size: 22px;
            font-weight: bold;
        }

        .receipt-number {
            text-align: right;
            color: #6b7280;
            margin-top: 4px;
        }

        .divider {
            border-top: 1px solid #d1d5db;
            margin: 20px 0;
        }

        .details-table {
            width: 100%;
            margin-bottom: 25px;
        }

        .details-table td {
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }

        .section-title {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .info-row {
            margin-bottom: 6px;
        }

        .label {
            font-weight: bold;
        }

        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .payment-table th,
        .payment-table td {
            border: 1px solid #d1d5db;
            padding: 10px;
        }

        .payment-table th {
            background: #f3f4f6;
            text-align: left;
        }

        .amount {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
            background: #f9fafb;
        }

        .remarks {
            margin-top: 25px;
        }

        .footer {
            margin-top: 35px;
            border-top: 1px solid #d1d5db;
            padding-top: 15px;
            color: #6b7280;
            font-size: 11px;
        }

        .footer-table {
            width: 100%;
        }

        .footer-right {
            text-align: right;
        }

    </style>

</head>


<body>


<table class="header-table">

    <tr>

        <td>

            <div class="brand">
                🏦 Lendora
            </div>

            <div class="subtitle">
                Cheque Loan Management System
            </div>

        </td>


        <td>

            <div class="receipt-title">
                PAYMENT RECEIPT
            </div>

            <div class="receipt-number">
                {{ $payment->receipt_number }}
            </div>

        </td>

    </tr>

</table>


<div class="divider"></div>


<table class="details-table">

    <tr>

        <td>

            <div class="section-title">
                Customer Details
            </div>

            <div class="info-row">
                <span class="label">Name:</span>
                {{ $payment->loan->customer->full_name }}
            </div>

            <div class="info-row">
                <span class="label">Customer Code:</span>
                {{ $payment->loan->customer->customer_code }}
            </div>

            <div class="info-row">
                <span class="label">NIC:</span>
                {{ $payment->loan->customer->nic }}
            </div>

            <div class="info-row">
                <span class="label">Phone:</span>
                {{ $payment->loan->customer->phone }}
            </div>

        </td>


        <td>

            <div class="section-title">
                Loan Details
            </div>

            <div class="info-row">
                <span class="label">Loan No:</span>
                {{ $payment->loan->loan_number }}
            </div>

            <div class="info-row">
                <span class="label">Cheque No:</span>
                {{ $payment->loan->cheque_number }}
            </div>

            <div class="info-row">
                <span class="label">Payment Date:</span>
                {{ $payment->payment_date->format('Y-m-d') }}
            </div>

            <div class="info-row">
                <span class="label">Loan Status:</span>
                {{ $payment->loan->status }}
            </div>

        </td>

    </tr>

</table>


<table class="payment-table">

    <thead>

        <tr>

            <th>
                Description
            </th>

            <th class="amount">
                Amount
            </th>

        </tr>

    </thead>


    <tbody>

        <tr>

            <td>
                Capital Paid
            </td>

            <td class="amount">
                LKR {{ number_format($payment->capital_paid, 2) }}
            </td>

        </tr>


        <tr>

            <td>
                Interest Paid
            </td>

            <td class="amount">
                LKR {{ number_format($payment->interest_paid, 2) }}
            </td>

        </tr>


        <tr class="total-row">

            <td>
                Total Paid
            </td>

            <td class="amount">
                LKR {{ number_format($payment->total_paid, 2) }}
            </td>

        </tr>


        <tr>

            <td>
                Remaining Capital
            </td>

            <td class="amount">
                LKR {{ number_format($payment->remaining_balance, 2) }}
            </td>

        </tr>

    </tbody>

</table>


@if($payment->remarks)

    <div class="remarks">

        <div class="section-title">
            Remarks
        </div>

        <div>
            {{ $payment->remarks }}
        </div>

    </div>

@endif


<div class="footer">

    <table class="footer-table">

        <tr>

            <td>
                Generated by Lendora
            </td>

            <td class="footer-right">
                Receipt No:
                {{ $payment->receipt_number }}
            </td>

        </tr>

    </table>

</div>


</body>

</html>