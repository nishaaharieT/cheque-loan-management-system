@extends('layouts.app')

@section('content')

<div class="page-header">

    <div>
        <h2>Edit Customer</h2>
        <p>Update customer information</p>
    </div>

    <a href="{{ route('customers.index') }}" class="btn btn-primary">
        ← Back to Customers
    </a>

</div>


<div class="form-card">

    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following:</strong>

            <ul style="margin-top:8px; padding-left:20px;">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('customers.update', $customer->id) }}"
        method="POST"
    >

        @csrf
        @method('PUT')


        <div class="form-group">

            <label>Customer Code</label>

            <input
                type="text"
                name="customer_code"
                class="form-control"
                value="{{ old('customer_code', $customer->customer_code) }}"
                required
            >

        </div>


        <div class="form-group">

            <label>Full Name</label>

            <input
                type="text"
                name="full_name"
                class="form-control"
                value="{{ old('full_name', $customer->full_name) }}"
                required
            >

        </div>


        <div class="form-group">

            <label>NIC</label>

            <input
                type="text"
                name="nic"
                class="form-control"
                value="{{ old('nic', $customer->nic) }}"
                required
            >

        </div>


        <div class="form-group">

            <label>Phone</label>

            <input
                type="text"
                name="phone"
                class="form-control"
                value="{{ old('phone', $customer->phone) }}"
                required
            >

        </div>


        <div class="form-group">

            <label>Address</label>

            <textarea
                name="address"
                class="form-control"
                rows="4"
                required
            >{{ old('address', $customer->address) }}</textarea>

        </div>


        <div class="form-group">

            <label>Status</label>

            <select name="status" class="form-control" required>

                <option
                    value="Active"
                    {{ old('status', $customer->status) == 'Active' ? 'selected' : '' }}
                >
                    Active
                </option>

                <option
                    value="Inactive"
                    {{ old('status', $customer->status) == 'Inactive' ? 'selected' : '' }}
                >
                    Inactive
                </option>

            </select>

        </div>


        <div style="margin-top:25px;">

            <button type="submit" class="btn btn-primary">
                Update Customer
            </button>

            <a
                href="{{ route('customers.index') }}"
                class="btn"
                style="background:#e5e7eb; color:#374151; margin-left:8px;"
            >
                Cancel
            </a>

        </div>

    </form>

</div>

@endsection