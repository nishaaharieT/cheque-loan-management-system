@extends('layouts.app')

@section('content')

<h1>Dashboard</h1>

<p>Welcome to the Cheque Loan Management System.</p>

<div style="display:flex;gap:20px;margin-top:30px;flex-wrap:wrap;">

    <div style="background:white;padding:20px;border-radius:10px;width:220px;box-shadow:0 2px 10px rgba(0,0,0,.1);">
        <h3>Customers</h3>
        <h1>0</h1>
    </div>

    <div style="background:white;padding:20px;border-radius:10px;width:220px;box-shadow:0 2px 10px rgba(0,0,0,.1);">
        <h3>Active Loans</h3>
        <h1>0</h1>
    </div>

    <div style="background:white;padding:20px;border-radius:10px;width:220px;box-shadow:0 2px 10px rgba(0,0,0,.1);">
        <h3>Today's Collection</h3>
        <h1>Rs. 0</h1>
    </div>

    <div style="background:white;padding:20px;border-radius:10px;width:220px;box-shadow:0 2px 10px rgba(0,0,0,.1);">
        <h3>Employees</h3>
        <h1>0</h1>
    </div>

</div>

@endsection