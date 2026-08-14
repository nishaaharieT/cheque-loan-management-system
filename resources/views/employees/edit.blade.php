@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h2>Edit Employee</h2>
        <p>Update employee details and login information</p>
    </div>

    <a href="{{ route('employees.index') }}"
       class="btn"
       style="background:#e5e7eb;color:#374151;">
        Back to Employees
    </a>
</div>


@if($errors->any())

    <div class="alert alert-danger">

        <strong>Please fix the following errors:</strong>

        <ul style="margin-top:8px;margin-left:20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

    </div>

@endif


<div class="form-card">

    <form action="{{ route('employees.update', $employee->id) }}"
          method="POST">

        @csrf
        @method('PUT')


        <!-- Employee Code -->
        <div class="form-group">

            <label for="employee_code">
                Employee Code
            </label>

            <input
                type="text"
                id="employee_code"
                name="employee_code"
                class="form-control"
                value="{{ old('employee_code', $employee->employee_code) }}"
                required
            >

        </div>


        <!-- Employee Name -->
        <div class="form-group">

            <label for="name">
                Employee Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                class="form-control"
                value="{{ old('name', $employee->name) }}"
                required
            >

        </div>


        <!-- Username -->
        <div class="form-group">

            <label for="username">
                Username
            </label>

            <input
                type="text"
                id="username"
                name="username"
                class="form-control"
                value="{{ old('username', $employee->username) }}"
                required
            >

        </div>


        <!-- Email -->
        <div class="form-group">

            <label for="email">
                Email Address
            </label>

            <input
                type="email"
                id="email"
                name="email"
                class="form-control"
                value="{{ old('email', $employee->email) }}"
                placeholder="example@gmail.com"
                required
            >

            <small style="color:#6b7280;">
                This email will be used for employee login.
            </small>

        </div>


        <!-- New Password -->
        <div class="form-group">

            <label for="password">
                New Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                class="form-control"
                placeholder="Leave blank to keep current password"
            >

            <small style="color:#6b7280;">
                Only enter a password if you want to change it.
            </small>

        </div>


        <!-- Confirm Password -->
        <div class="form-group">

            <label for="password_confirmation">
                Confirm New Password
            </label>

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="form-control"
                placeholder="Re-enter new password"
            >

        </div>


        <!-- Status -->
        <div class="form-group">

            <label for="status">
                Status
            </label>

            <select
                id="status"
                name="status"
                class="form-control"
                required
            >

                <option
                    value="Active"
                    {{ old('status', $employee->status) === 'Active' ? 'selected' : '' }}
                >
                    Active
                </option>

                <option
                    value="Inactive"
                    {{ old('status', $employee->status) === 'Inactive' ? 'selected' : '' }}
                >
                    Inactive
                </option>

            </select>

        </div>


        <!-- Buttons -->
        <div style="margin-top:25px;">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Update Employee
            </button>

            <a
                href="{{ route('employees.index') }}"
                class="btn"
                style="background:#e5e7eb;color:#374151;margin-left:8px;"
            >
                Cancel
            </a>

        </div>

    </form>

</div>

@endsection