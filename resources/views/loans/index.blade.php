@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h2>Loans</h2>
        <p>Manage all cheque loans</p>
    </div>

    <a href="{{ route('loans.create') }}" class="btn btn-primary">
        + Create New Loan
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


<!-- Statistics -->
<div class="cards">

    <div class="card">
        <div class="card-body">
            <div class="stat-title">Total Loans</div>
            <div class="stat-number">
                {{ $loans->count() }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="stat-title">Active Loans</div>
            <div class="stat-number" style="color:#198754;">
                {{ $loans->where('status', 'Active')->count() }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="stat-title">Completed Loans</div>
            <div class="stat-number" style="color:#1453a6;">
                {{ $loans->where('status', 'Completed')->count() }}
            </div>
        </div>
    </div>

</div>


<!-- Loan Table -->
<div class="table-card">

    <div class="table-header">

        <div>
            <h3>Cheque Loan List</h3>

            <p style="color:#6b7280; font-size:13px; margin-top:4px;">
                All registered cheque loans
            </p>
        </div>

        <input
            type="text"
            id="loanSearch"
            class="search-box"
            placeholder="Search loan..."
        >

    </div>


    @if($loans->count() > 0)

        <div style="overflow-x:auto;">

            <table id="loansTable">

                <thead>
                    <tr>
                        <th>Loan No</th>
                        <th>Customer</th>
                        <th>Cheque No</th>
                        <th>Amount</th>
                        <th>Duration</th>
                        <th>Interest</th>
                        <th>Monthly Payment</th>
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
                                {{ $loan->customer->full_name }}
                                <br>
                                <small style="color:#6b7280;">
                                    {{ $loan->customer->customer_code }}
                                </small>
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

        <div style="padding:50px; text-align:center;">

            <h3>No Loans Found</h3>

            <p style="color:#6b7280; margin:10px 0 20px;">
                No cheque loans have been created yet.
            </p>

            <a href="{{ route('loans.create') }}"
               class="btn btn-primary">
                + Create First Loan
            </a>

        </div>

    @endif

</div>


<script>

document
    .getElementById('loanSearch')
    .addEventListener('keyup', function () {

        let search = this.value.toLowerCase();

        let rows =
            document.querySelectorAll('#loansTable tbody tr');

        rows.forEach(function(row) {

            let text = row.textContent.toLowerCase();

            row.style.display =
                text.includes(search) ? '' : 'none';

        });

    });

</script>

@endsection