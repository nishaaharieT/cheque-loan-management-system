@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h2>Customers</h2>
        <p>Manage all customers in the system</p>
    </div>

    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        + Add New Customer
    </a>
</div>

<div class="cards">

    <div class="card">
        <div class="card-body">
            <div class="stat-title">Total Customers</div>
            <div class="stat-number">
                {{ $customers->count() }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="stat-title">Active Customers</div>
            <div class="stat-number" style="color:#198754;">
                {{ $customers->where('status', 'Active')->count() }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="stat-title">Inactive Customers</div>
            <div class="stat-number" style="color:#dc3545;">
                {{ $customers->where('status', 'Inactive')->count() }}
            </div>
        </div>
    </div>

</div>

<div class="table-card">

    <div class="table-header">

        <div>
            <h3>Customer List</h3>
            <p style="color:#6b7280;font-size:13px;margin-top:4px;">
                All registered customers
            </p>
        </div>

        <input
            type="text"
            id="customerSearch"
            class="search-box"
            placeholder="Search customer..."
        >

    </div>

    @if($customers->count() > 0)

        <div style="overflow-x:auto;">

            <table id="customersTable">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer Code</th>
                        <th>Full Name</th>
                        <th>NIC</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($customers as $customer)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <strong>{{ $customer->customer_code }}</strong>
                            </td>

                            <td>{{ $customer->full_name }}</td>

                            <td>{{ $customer->nic }}</td>

                            <td>{{ $customer->phone }}</td>

                            <td>{{ $customer->address }}</td>

                            <td>
                                @if($customer->status === 'Active')
                                    <span class="badge badge-success">
                                        Active
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div style="display:flex;gap:6px;">

                                    <a
                                        href="{{ route('customers.show', $customer->id) }}"
                                        class="btn"
                                        style="background:#0f766e;color:white;padding:7px 12px;font-size:12px;"
                                    >
                                        View
                                    </a>

                                    <a
                                        href="{{ route('customers.edit', $customer->id) }}"
                                        class="btn btn-primary"
                                        style="padding:7px 12px;font-size:12px;"
                                    >
                                        Edit
                                    </a>

                                </div>
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div style="padding:50px;text-align:center;">
            <h3>No Customers Found</h3>

            <p style="color:#6b7280;margin:10px 0 20px;">
                There are currently no customers in the system.
            </p>

            <a href="{{ route('customers.create') }}"
               class="btn btn-primary">
                + Add First Customer
            </a>
        </div>

    @endif

</div>

<script>
document
    .getElementById('customerSearch')
    .addEventListener('keyup', function () {

        const search = this.value.toLowerCase();

        document
            .querySelectorAll('#customersTable tbody tr')
            .forEach(function(row) {

                row.style.display =
                    row.textContent.toLowerCase().includes(search)
                        ? ''
                        : 'none';
            });
    });
</script>

@endsection