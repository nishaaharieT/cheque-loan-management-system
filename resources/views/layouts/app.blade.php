<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Lendora - Cheque Loan Management System
    </title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }


        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: #1453a6;
            color: white;
            padding: 25px 15px;
            z-index: 1000;
        }


        .logo {
            text-align: center;
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 40px;
        }


        .logo span {
            margin-right: 8px;
        }


        .menu-title {
            font-size: 11px;
            color: #b9d0f0;
            text-transform: uppercase;
            margin: 0 15px 10px;
            letter-spacing: 1px;
        }


        .sidebar a {
            display: flex;
            align-items: center;
            gap: 13px;
            color: white;
            text-decoration: none;
            padding: 13px 15px;
            margin-bottom: 6px;
            border-radius: 8px;
            font-size: 15px;
            transition: 0.2s;
        }


        .sidebar a:hover {
            background: #2168c4;
        }


        .sidebar a.active {
            background: white;
            color: #1453a6;
            font-weight: bold;
        }


        .icon {
            width: 25px;
            text-align: center;
            font-size: 18px;
        }



        /* =========================
           MAIN
        ========================= */

        .main {
            margin-left: 250px;
            min-height: 100vh;
        }



        /* =========================
           NAVBAR
        ========================= */

        .navbar {
            height: 75px;
            background: white;
            padding: 0 35px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }


        .navbar h1 {
            font-size: 24px;
            color: #111827;
        }


        .user-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }


        .user-name {
            font-weight: 600;
            font-size: 14px;
            color: #374151;
        }


        .logout-btn {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 13px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }


        .logout-btn:hover {
            background: #dc2626;
        }



        /* =========================
           CONTENT
        ========================= */

        .content {
            padding: 35px;
        }


        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }


        .page-header h2 {
            font-size: 28px;
            margin-bottom: 5px;
            color: #111827;
        }


        .page-header p {
            color: #6b7280;
            font-size: 14px;
        }



        /* =========================
           BUTTONS
        ========================= */

        .btn {
            display: inline-block;
            padding: 10px 17px;
            border-radius: 7px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }


        .btn-primary {
            background: #1453a6;
            color: white;
        }


        .btn-primary:hover {
            background: #0d4288;
        }


        .btn-success {
            background: #198754;
            color: white;
        }


        .btn-danger {
            background: #dc3545;
            color: white;
        }



        /* =========================
           CARDS
        ========================= */

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }


        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }


        .card-body {
            padding: 22px;
        }


        .stat-title {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 8px;
        }


        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #111827;
        }



        /* =========================
           TABLES
        ========================= */

        .table-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }


        .table-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }


        .table-header h3 {
            font-size: 18px;
        }


        .search-box {
            padding: 9px 12px;
            width: 260px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            outline: none;
        }


        .search-box:focus {
            border-color: #1453a6;
        }


        table {
            width: 100%;
            border-collapse: collapse;
        }


        th {
            background: #f8fafc;
            color: #374151;
            font-size: 13px;
            text-align: left;
            padding: 14px 16px;
            border-bottom: 1px solid #e5e7eb;
        }


        td {
            padding: 14px 16px;
            border-bottom: 1px solid #eef0f3;
            font-size: 14px;
        }


        tr:hover {
            background: #f8fafc;
        }



        /* =========================
           BADGES
        ========================= */

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }


        .badge-success {
            background: #dcfce7;
            color: #15803d;
        }


        .badge-danger {
            background: #fee2e2;
            color: #b91c1c;
        }



        /* =========================
           FORMS
        ========================= */

        .form-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }


        .form-group {
            margin-bottom: 18px;
        }


        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 7px;
        }


        .form-control {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 14px;
            outline: none;
        }


        .form-control:focus {
            border-color: #1453a6;
        }


        textarea.form-control {
            resize: vertical;
        }



        /* =========================
           ALERTS
        ========================= */

        .alert {
            padding: 14px 17px;
            border-radius: 7px;
            margin-bottom: 20px;
        }


        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }


        .alert-success {
            background: #dcfce7;
            color: #166534;
        }



        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 900px) {

            .sidebar {
                width: 210px;
            }


            .main {
                margin-left: 210px;
            }


            .cards {
                grid-template-columns: 1fr;
            }
        }


        @media (max-width: 650px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }


            .main {
                margin-left: 0;
            }


            .content {
                padding: 20px;
            }


            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }


            .search-box {
                width: 100%;
            }


            .navbar {
                height: auto;
                padding: 18px;
                gap: 10px;
                flex-direction: column;
                align-items: flex-start;
            }
        }

    </style>

</head>


<body>


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">

    <div class="logo">
        <span>🏦</span>
        Lendora
    </div>


    <div class="menu-title">
        Main Menu
    </div>


    <!-- Dashboard -->

    <a
        href="{{ route('dashboard') }}"
        class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
    >

        <span class="icon">🏠</span>

        Dashboard

    </a>


    <!-- Customers -->

    <a
        href="{{ route('customers.index') }}"
        class="{{ request()->is('customers*') ? 'active' : '' }}"
    >

        <span class="icon">👥</span>

        Customers

    </a>


    <!-- Loans -->

    <a
        href="{{ route('loans.index') }}"
        class="{{ request()->is('loans*') ? 'active' : '' }}"
    >

        <span class="icon">💰</span>

        Loans

    </a>


    <!-- Payments -->

    <a
        href="{{ route('payments.index') }}"
        class="{{ request()->is('payments*') ? 'active' : '' }}"
    >

        <span class="icon">💳</span>

        Payments

    </a>


    <!-- Employees -->

    <a
        href="{{ route('employees.index') }}"
        class="{{ request()->is('employees*') ? 'active' : '' }}"
    >

        <span class="icon">👨‍💼</span>

        Employees

    </a>


    <!-- Reports -->

    <a
        href="{{ route('reports.index') }}"
        class="{{ request()->is('reports*') ? 'active' : '' }}"
    >

        <span class="icon">📊</span>

        Reports

    </a>


    <!-- Activity Logs -->

    <a
        href="{{ route('activity-logs.index') }}"
        class="{{ request()->is('activity-logs*') ? 'active' : '' }}"
    >

        <span class="icon">📋</span>

        Activity Logs

    </a>

</div>



<!-- =========================
     MAIN CONTENT
========================= -->

<div class="main">


    <!-- NAVBAR -->

    <div class="navbar">


        <h1>
            Lendora - Cheque Loan Management System
        </h1>


        <div class="user-area">

            @auth

                <span class="user-name">

                    👤 {{ Auth::user()->name }}

                </span>


                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf


                    <button
                        type="submit"
                        class="logout-btn"
                    >

                        Logout

                    </button>

                </form>

            @endauth

        </div>

    </div>



    <!-- PAGE CONTENT -->

    <div class="content">


        {{-- Lendora pages --}}

        @yield('content')


        {{-- Breeze component pages --}}

        @isset($slot)

            {{ $slot }}

        @endisset


    </div>

</div>


</body>

</html>