@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3>Add New Customer</h3>
        </div>

        <div class="card-body">

            {{-- Show validation errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the following:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Success message --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('customers.store') }}" method="POST">

                @csrf

                <div class="mb-3">
                    <label>Customer Code</label>
                    <input
                        type="text"
                        name="customer_code"
                        class="form-control"
                        value="{{ old('customer_code') }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label>Full Name</label>
                    <input
                        type="text"
                        name="full_name"
                        class="form-control"
                        value="{{ old('full_name') }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label>NIC</label>
                    <input
                        type="text"
                        name="nic"
                        class="form-control"
                        value="{{ old('nic') }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label>Phone</label>
                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="{{ old('phone') }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label>Address</label>
                    <textarea
                        name="address"
                        class="form-control"
                        rows="3"
                        required
                    >{{ old('address') }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Status</label>

                    <select name="status" class="form-control" required>
                        <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Save Customer
                </button>

            </form>

        </div>
    </div>

</div>

@endsection