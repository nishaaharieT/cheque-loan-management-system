@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h2>Employees</h2>
        <p>Manage system employees</p>
    </div>

    <a href="{{ route('employees.create') }}" class="btn btn-primary">
        + Add Employee
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="cards">

    <div class="card">
        <div class="card-body">
            <div class="stat-title">Total Employees</div>
            <div class="stat-number">
                {{ $employees->count() }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="stat-title">Active Employees</div>
            <div class="stat-number" style="color:#198754;">
                {{ $employees->where('status', 'Active')->count() }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="stat-title">Inactive Employees</div>
            <div class="stat-number" style="color:#dc3545;">
                {{ $employees->where('status', 'Inactive')->count() }}
            </div>
        </div>
    </div>

</div>

<div class="table-card">

    <div class="table-header">
        <div>
            <h3>Employee List</h3>
            <p style="color:#6b7280;font-size:13px;margin-top:4px;">
                All registered employees
            </p>
        </div>

        <input
            type="text"
            id="employeeSearch"
            class="search-box"
            placeholder="Search employee..."
        >
    </div>

    @if($employees->count() > 0)

        <div style="overflow-x:auto;">

            <table id="employeesTable">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Code</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($employees as $employee)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <strong>
                                    {{ $employee->employee_code }}
                                </strong>
                            </td>

                            <td>
                                {{ $employee->name }}
                            </td>

                            <td>
                                {{ $employee->username }}
                            </td>

                            <td>

                                @if($employee->status === 'Active')

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

                                <a
                                    href="{{ route('employees.edit', $employee->id) }}"
                                    class="btn btn-primary"
                                    style="padding:7px 12px;font-size:12px;"
                                >
                                    Edit
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div style="padding:50px;text-align:center;">

            <h3>No Employees Found</h3>

            <p style="color:#6b7280;margin:10px 0 20px;">
                No employees have been added yet.
            </p>

            <a href="{{ route('employees.create') }}"
               class="btn btn-primary">
                + Add First Employee
            </a>

        </div>

    @endif

</div>

<script>

document
    .getElementById('employeeSearch')
    .addEventListener('keyup', function () {

        const search = this.value.toLowerCase();

        document
            .querySelectorAll('#employeesTable tbody tr')
            .forEach(function(row) {

                row.style.display =
                    row.textContent.toLowerCase().includes(search)
                        ? ''
                        : 'none';

            });

    });

</script>

@endsection