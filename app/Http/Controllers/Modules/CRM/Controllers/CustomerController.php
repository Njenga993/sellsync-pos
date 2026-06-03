<?php

namespace App\Http\Controllers\Modules\CRM\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::where('tenant_id', auth()->user()->tenant_id)
            ->latest()
            ->paginate(20);

        return view('modules.crm.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('modules.crm.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['nullable', 'email', 'max:255'],
            'phone'        => ['nullable', 'string', 'max:20'],
            'address'      => ['nullable', 'string'],
            'city'         => ['nullable', 'string', 'max:100'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
            'status'       => ['required', 'in:active,inactive'],
        ]);

        Customer::create([
            'tenant_id'    => auth()->user()->tenant_id,
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'address'      => $request->address,
            'city'         => $request->city,
            'credit_limit' => $request->credit_limit ?? 0,
            'status'       => $request->status,
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Customer added successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load('sales');
        $recentSales = $customer->sales()->latest()->take(10)->get();
        return view('modules.crm.customers.show', compact('customer', 'recentSales'));
    }

    public function edit(Customer $customer)
    {
        return view('modules.crm.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['nullable', 'email', 'max:255'],
            'phone'        => ['nullable', 'string', 'max:20'],
            'address'      => ['nullable', 'string'],
            'city'         => ['nullable', 'string', 'max:100'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
            'status'       => ['required', 'in:active,inactive'],
        ]);

        $customer->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'address'      => $request->address,
            'city'         => $request->city,
            'credit_limit' => $request->credit_limit ?? 0,
            'status'       => $request->status,
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}