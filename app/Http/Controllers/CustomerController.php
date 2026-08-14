<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // =========================================
    // DISPLAY ALL CUSTOMERS
    // =========================================
    public function index()
    {
        $customers = Customer::latest()->get();

        return view('customers.index', compact('customers'));
    }


    // =========================================
    // SHOW ADD CUSTOMER FORM
    // =========================================
    public function create()
    {
        return view('customers.create');
    }


    // =========================================
    // SAVE NEW CUSTOMER
    // =========================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_code' =>
                'required|unique:customers,customer_code',

            'full_name' =>
                'required|string|max:255',

            'nic' =>
                'required|unique:customers,nic',

            'phone' =>
                'required|string|max:20',

            'address' =>
                'required|string',

            'status' =>
                'required|in:Active,Inactive',
        ]);


        $customer = Customer::create($validated);


        $employeeId =
            auth()->user()
                ->fresh()
                ->employee_id;


        ActivityLog::create([
            'employee_id' =>
                $employeeId,

            'action' =>
                'Customer Created',

            'description' =>
                'Created customer ' .
                $customer->customer_code .
                ' - ' .
                $customer->full_name,
        ]);


        return redirect()
            ->route('customers.index')
            ->with(
                'success',
                'Customer added successfully.'
            );
    }


    // =========================================
    // SHOW CUSTOMER DETAILS
    // =========================================
    public function show(Customer $customer)
    {
        $customer->load([
            'loans' => function ($query) {
                $query
                    ->latest()
                    ->with('payments');
            }
        ]);


        return view(
            'customers.show',
            compact('customer')
        );
    }


    // =========================================
    // SHOW EDIT CUSTOMER FORM
    // =========================================
    public function edit(Customer $customer)
    {
        return view(
            'customers.edit',
            compact('customer')
        );
    }


    // =========================================
    // UPDATE CUSTOMER
    // =========================================
    public function update(
        Request $request,
        Customer $customer
    ) {
        $validated = $request->validate([
            'customer_code' =>
                'required|unique:customers,customer_code,' .
                $customer->id,

            'full_name' =>
                'required|string|max:255',

            'nic' =>
                'required|unique:customers,nic,' .
                $customer->id,

            'phone' =>
                'required|string|max:20',

            'address' =>
                'required|string',

            'status' =>
                'required|in:Active,Inactive',
        ]);


        /*
         * Keep original values before update.
         */
        $original = [
            'customer_code' =>
                $customer->customer_code,

            'full_name' =>
                $customer->full_name,

            'nic' =>
                $customer->nic,

            'phone' =>
                $customer->phone,

            'address' =>
                $customer->address,

            'status' =>
                $customer->status,
        ];


        /*
         * Detect changed fields.
         */
        $changes = [];


        $fieldLabels = [
            'customer_code' => 'Customer Code',
            'full_name' => 'Full Name',
            'nic' => 'NIC',
            'phone' => 'Phone',
            'address' => 'Address',
            'status' => 'Status',
        ];


        foreach ($validated as $field => $newValue) {

            $oldValue =
                $original[$field] ?? null;


            if ((string) $oldValue !== (string) $newValue) {

                $changes[] =
                    $fieldLabels[$field] .
                    ': ' .
                    $oldValue .
                    ' → ' .
                    $newValue;
            }
        }


        /*
         * Update customer.
         */
        $customer->update($validated);


        $employeeId =
            auth()->user()
                ->fresh()
                ->employee_id;


        /*
         * Build detailed activity description.
         */
        if (count($changes) > 0) {

            $description =
                'Updated customer ' .
                $customer->customer_code .
                ' - ' .
                $customer->full_name .
                ' | ' .
                implode(
                    ' | ',
                    $changes
                );

        } else {

            $description =
                'Updated customer ' .
                $customer->customer_code .
                ' - ' .
                $customer->full_name .
                ' | No field values changed';
        }


        ActivityLog::create([
            'employee_id' =>
                $employeeId,

            'action' =>
                'Customer Updated',

            'description' =>
                $description,
        ]);


        return redirect()
            ->route('customers.index')
            ->with(
                'success',
                'Customer updated successfully.'
            );
    }
}