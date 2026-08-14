@extends('layouts.app')

@section('content')

<div class="page-header">

    <div>
        <h2>Payments</h2>
        <p>View and manage cheque loan payments</p>
    </div>

    <a
        href="{{ route('payments.create') }}"
        class="btn btn-primary"
    >
        + Record Payment
    </a>

</div>


@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


<!-- =========================================
     PAYMENT STATISTICS
========================================== -->

<div class="cards">


    <div class="card">

        <div class="card-body">

            <div class="stat-title">
                Total Payments
            </div>

            <div class="stat-number">
                {{ $payments->count() }}
            </div>

        </div>

    </div>



    <div class="card">

        <div class="card-body">

            <div class="stat-title">
                Capital Collected
            </div>

            <div
                class="stat-number"
                style="font-size:22px;"
            >
                LKR {{ number_format($payments->sum('capital_paid'), 2) }}
            </div>

        </div>

    </div>



    <div class="card">

        <div class="card-body">

            <div class="stat-title">
                Interest Collected
            </div>

            <div
                class="stat-number"
                style="font-size:22px;color:#198754;"
            >
                LKR {{ number_format($payments->sum('interest_paid'), 2) }}
            </div>

        </div>

    </div>


</div>



<!-- =========================================
     PAYMENT HISTORY
========================================== -->

<div class="table-card">


    <div class="table-header">

        <div>

            <h3>
                Payment History
            </h3>

            <p
                style="
                    color:#6b7280;
                    font-size:13px;
                    margin-top:4px;
                "
            >
                All recorded cheque loan payments
            </p>

        </div>


        <input
            type="text"
            id="paymentSearch"
            class="search-box"
            placeholder="Search payment..."
        >

    </div>



    @if($payments->count() > 0)


        <div style="overflow-x:auto;">


            <table id="paymentsTable">


                <thead>

                    <tr>

                        <th>
                            Receipt No
                        </th>

                        <th>
                            Date
                        </th>

                        <th>
                            Loan No
                        </th>

                        <th>
                            Customer
                        </th>

                        <th>
                            Cheque No
                        </th>

                        <th>
                            Capital Paid
                        </th>

                        <th>
                            Interest Paid
                        </th>

                        <th>
                            Total Paid
                        </th>

                        <th>
                            Remaining
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>



                <tbody>


                    @foreach($payments as $payment)


                        <tr>


                            <!-- RECEIPT NUMBER -->

                            <td>

                                <strong>
                                    {{ $payment->receipt_number }}
                                </strong>

                            </td>



                            <!-- PAYMENT DATE -->

                            <td>

                                {{ $payment->payment_date->format('Y-m-d') }}

                            </td>



                            <!-- LOAN NUMBER -->

                            <td>

                                {{ $payment->loan->loan_number }}

                            </td>



                            <!-- CUSTOMER -->

                            <td>

                                {{ $payment->loan->customer->full_name }}

                                <br>

                                <small style="color:#6b7280;">

                                    {{ $payment->loan->customer->customer_code }}

                                </small>

                            </td>



                            <!-- CHEQUE NUMBER -->

                            <td>

                                {{ $payment->loan->cheque_number }}

                            </td>



                            <!-- CAPITAL -->

                            <td>

                                LKR
                                {{ number_format($payment->capital_paid, 2) }}

                            </td>



                            <!-- INTEREST -->

                            <td>

                                LKR
                                {{ number_format($payment->interest_paid, 2) }}

                            </td>



                            <!-- TOTAL PAID -->

                            <td>

                                <strong>

                                    LKR
                                    {{ number_format($payment->total_paid, 2) }}

                                </strong>

                            </td>



                            <!-- REMAINING BALANCE -->

                            <td>

                                LKR
                                {{ number_format($payment->remaining_balance, 2) }}

                            </td>



                            <!-- ACTIONS -->

                            <td>

                                <a
                                    href="{{ route('payments.show', $payment) }}"
                                    class="btn btn-primary"
                                    style="
                                        padding:7px 12px;
                                        white-space:nowrap;
                                    "
                                >
                                    View Receipt
                                </a>

                            </td>


                        </tr>


                    @endforeach


                </tbody>


            </table>


        </div>


    @else


        <div
            style="
                padding:50px;
                text-align:center;
            "
        >

            <h3>
                No Payments Found
            </h3>


            <p
                style="
                    color:#6b7280;
                    margin:10px 0 20px;
                "
            >
                No loan payments have been recorded yet.
            </p>


            <a
                href="{{ route('payments.create') }}"
                class="btn btn-primary"
            >
                + Record First Payment
            </a>


        </div>


    @endif


</div>



<script>

const paymentSearch =
    document.getElementById('paymentSearch');


if (paymentSearch) {

    paymentSearch.addEventListener(
        'keyup',
        function () {

            const search =
                this.value.toLowerCase();


            const rows =
                document.querySelectorAll(
                    '#paymentsTable tbody tr'
                );


            rows.forEach(function(row) {

                const text =
                    row.textContent.toLowerCase();


                row.style.display =
                    text.includes(search)
                        ? ''
                        : 'none';

            });

        }
    );

}

</script>

@endsection