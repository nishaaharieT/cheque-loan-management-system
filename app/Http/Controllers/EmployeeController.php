<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    // Display all employees
    public function index()
    {
        $employees = Employee::latest()->get();

        return view('employees.index', compact('employees'));
    }

    // Show Add Employee Form
    public function create()
    {
        return view('employees.create');
    }

    // Save New Employee
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_code' => 'required|unique:employees,employee_code',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:employees,username',
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:employees,email',
                'unique:users,email',
            ],
            'password' => 'required|string|min:6|confirmed',
            'status' => 'required|in:Active,Inactive',
        ]);

        $loggedInEmployeeId = auth()->user()->fresh()->employee_id;

        DB::transaction(function () use (
            $validated,
            $loggedInEmployeeId
        ) {
            $employee = Employee::create([
                'employee_code' => $validated['employee_code'],
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'status' => $validated['status'],
            ]);

            User::create([
                'employee_id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email,
                'password' => Hash::make($validated['password']),
            ]);

            ActivityLog::create([
                'employee_id' => $loggedInEmployeeId,
                'action' => 'Employee Created',
                'description' =>
                    'Created employee ' .
                    $employee->employee_code .
                    ' - ' .
                    $employee->name,
            ]);
        });

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee added successfully.');
    }

    // Show Edit Employee Form
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    // Update Employee
    public function update(Request $request, Employee $employee)
    {
        $user = User::where('employee_id', $employee->id)->first();

        $validated = $request->validate([
            'employee_code' =>
                'required|unique:employees,employee_code,' . $employee->id,

            'name' =>
                'required|string|max:255',

            'username' =>
                'required|string|max:255|unique:employees,username,' . $employee->id,

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:employees,email,' . $employee->id,
                'unique:users,email,' . ($user?->id ?? 'NULL'),
            ],

            'password' =>
                'nullable|string|min:6|confirmed',

            'status' =>
                'required|in:Active,Inactive',
        ]);

        $loggedInEmployeeId = auth()->user()->fresh()->employee_id;

        DB::transaction(function () use (
            $validated,
            $employee,
            $user,
            $loggedInEmployeeId
        ) {
            $employeeData = [
                'employee_code' => $validated['employee_code'],
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'status' => $validated['status'],
            ];

            if (!empty($validated['password'])) {
                $employeeData['password'] =
                    Hash::make($validated['password']);
            }

            $employee->update($employeeData);

            if ($user) {
                $userData = [
                    'name' => $employee->name,
                    'email' => $employee->email,
                ];

                if (!empty($validated['password'])) {
                    $userData['password'] =
                        Hash::make($validated['password']);
                }

                $user->update($userData);
            }

            ActivityLog::create([
                'employee_id' => $loggedInEmployeeId,
                'action' => 'Employee Updated',
                'description' =>
                    'Updated employee ' .
                    $employee->employee_code .
                    ' - ' .
                    $employee->name .
                    ' - status ' .
                    $employee->status,
            ]);
        });

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    // Employee deletion is not allowed
    public function destroy(Employee $employee)
    {
        abort(403, 'Employee deletion is not allowed.');
    }
}