@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h2>Activity Logs</h2>
        <p>View employee actions recorded in the system</p>
    </div>
</div>


<!-- FILTER SECTION -->
<div class="form-card" style="margin-bottom:25px;">

    <form method="GET" action="{{ route('activity-logs.index') }}">

        <div style="
            display:grid;
            grid-template-columns:1fr 1fr auto;
            gap:15px;
            align-items:end;
        ">

            <!-- Employee Filter -->
            <div class="form-group" style="margin-bottom:0;">

                <label for="employee_id">
                    Employee
                </label>

                <select
                    name="employee_id"
                    id="employee_id"
                    class="form-control"
                >

                    <option value="">All Employees</option>

                    @foreach($employees as $employee)

                        <option
                            value="{{ $employee->id }}"
                            {{ request('employee_id') == $employee->id ? 'selected' : '' }}
                        >
                            {{ $employee->employee_code }}
                            - {{ $employee->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <!-- Action Filter -->
            <div class="form-group" style="margin-bottom:0;">

                <label for="action">
                    Action
                </label>

                <select
                    name="action"
                    id="action"
                    class="form-control"
                >

                    <option value="">All Actions</option>

                    @foreach($actions as $action)

                        <option
                            value="{{ $action }}"
                            {{ request('action') == $action ? 'selected' : '' }}
                        >
                            {{ $action }}
                        </option>

                    @endforeach

                </select>

            </div>


            <!-- Buttons -->
            <div>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Filter
                </button>

                <a
                    href="{{ route('activity-logs.index') }}"
                    class="btn"
                    style="
                        background:#e5e7eb;
                        color:#374151;
                        margin-left:5px;
                    "
                >
                    Reset
                </a>

            </div>

        </div>

    </form>

</div>


<!-- ACTIVITY LOG TABLE -->
<div class="table-header">

    <div>
        <h3>System Activity</h3>

        <p style="
            color:#6b7280;
            font-size:13px;
            margin-top:4px;
        ">
            {{ $activityLogs->count() }} activity records
        </p>
    </div>

</div>


@if($activityLogs->count() > 0)

    <div style="overflow-x:auto;">

        <table>

            <thead>

                <tr>
                    <th>#</th>
                    <th>Date & Time</th>
                    <th>Employee</th>
                    <th>Action</th>
                    <th>Description</th>
                </tr>

            </thead>

            <tbody>

                @foreach($activityLogs as $log)

                    <tr>

                        <td>
                            {{ $log->id }}
                        </td>


                        <td style="white-space:nowrap;">

                            {{ $log->created_at->format('d M Y') }}

                            <br>

                            <span style="
                                color:#6b7280;
                                font-size:12px;
                            ">
                                {{ $log->created_at->format('h:i A') }}
                            </span>

                        </td>


                        <td>

                            @if($log->employee)

                                <strong>
                                    {{ $log->employee->name }}
                                </strong>

                                <br>

                                <span style="
                                    color:#6b7280;
                                    font-size:12px;
                                ">
                                    {{ $log->employee->employee_code }}
                                </span>

                            @else

                                <span style="color:#9ca3af;">
                                    Unknown Employee
                                </span>

                            @endif

                        </td>


                        <td>

                            @if($log->action === 'Customer Created')

                                <span class="badge badge-success">
                                    Customer Created
                                </span>

                            @elseif($log->action === 'Customer Updated')

                                <span
                                    class="badge"
                                    style="
                                        background:#dbeafe;
                                        color:#1d4ed8;
                                    "
                                >
                                    Customer Updated
                                </span>

                            @elseif($log->action === 'Loan Created')

                                <span
                                    class="badge"
                                    style="
                                        background:#ede9fe;
                                        color:#6d28d9;
                                    "
                                >
                                    Loan Created
                                </span>

                            @elseif($log->action === 'Loan Updated')

                                <span
                                    class="badge"
                                    style="
                                        background:#fef3c7;
                                        color:#92400e;
                                    "
                                >
                                    Loan Updated
                                </span>

                            @elseif($log->action === 'Payment Recorded')

                                <span
                                    class="badge"
                                    style="
                                        background:#dcfce7;
                                        color:#166534;
                                    "
                                >
                                    Payment Recorded
                                </span>

                            @elseif($log->action === 'Employee Created')

                                <span
                                    class="badge"
                                    style="
                                        background:#e0e7ff;
                                        color:#3730a3;
                                    "
                                >
                                    Employee Created
                                </span>

                            @elseif($log->action === 'Employee Updated')

                                <span
                                    class="badge"
                                    style="
                                        background:#fce7f3;
                                        color:#9d174d;
                                    "
                                >
                                    Employee Updated
                                </span>

                            @else

                                <span class="badge">
                                    {{ $log->action }}
                                </span>

                            @endif

                        </td>


                        <td>
                            {{ $log->description }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

@else

    <div style="
        padding:50px;
        text-align:center;
        color:#6b7280;
    ">

        No activity logs found.

    </div>

@endif

@endsection