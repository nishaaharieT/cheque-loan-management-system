@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h2>Add Employee</h2>
        <p>Create a new employee account</p>
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

    <form action="{{ route('employees.store') }}" method="POST">

        @csrf


        <div class="form-group">

            <label for="employee_code">
                Employee Code
            </label>

            <input
                type="text"
                id="employee_code"
                name="employee_code"
                class="form-control"
                value="{{ old('employee_code') }}"
                placeholder="Example: EMP001"
                required
            >

        </div>


        <div class="form-group">

            <label for="name">
                Employee Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                class="form-control"
                value="{{ old('name') }}"
                placeholder="Enter employee full name"
                required
            >

        </div>


        <div class="form-group">

            <label for="username">
                Username
            </label>

            <input
                type="text"
                id="username"
                name="username"
                class="form-control"
                value="{{ old('username') }}"
                placeholder="Enter login username"
                required
            >

        </div>


        <div class="form-group">

            <label for="email">
                Email Address
            </label>

            <input
                type="email"
                id="email"
                name="email"
                class="form-control"
                value="{{ old('email') }}"
                placeholder="example@gmail.com"
                required
            >

        </div>


        <div class="form-group">

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                class="form-control"
                placeholder="Minimum 6 characters"
                required
            >

        </div>


        <div class="form-group">

            <label for="password_confirmation">
                Confirm Password
            </label>

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="form-control"
                placeholder="Enter password again"
                required
            >

        </div>


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
                    {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}
                >
                    Active
                </option>

                <option
                    value="Inactive"
                    {{ old('status') === 'Inactive' ? 'selected' : '' }}
                >
                    Inactive
                </option>

            </select>

        </div>


        <div style="margin-top:25px;">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Employee
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