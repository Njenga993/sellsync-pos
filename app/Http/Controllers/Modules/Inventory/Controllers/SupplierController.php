<?php

namespace App\Http\Controllers\Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::where('tenant_id', auth()->user()->tenant_id)
            ->withCount('purchaseOrders')
            ->latest()
            ->paginate(20);

        return view('modules.inventory.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('modules.inventory.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'email'          => ['nullable', 'email'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'address'        => ['nullable', 'string'],
            'city'           => ['nullable', 'string', 'max:100'],
            'notes'          => ['nullable', 'string'],
            'status'         => ['required', 'in:active,inactive'],
        ]);

        Supplier::create([
            'tenant_id'      => auth()->user()->tenant_id,
            'name'           => $request->name,
            'contact_person' => $request->contact_person,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'city'           => $request->city,
            'notes'          => $request->notes,
            'status'         => $request->status,
        ]);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier added successfully.');
    }

    public function edit(Supplier $supplier)
    {
        return view('modules.inventory.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'email'          => ['nullable', 'email'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'address'        => ['nullable', 'string'],
            'city'           => ['nullable', 'string', 'max:100'],
            'notes'          => ['nullable', 'string'],
            'status'         => ['required', 'in:active,inactive'],
        ]);

        $supplier->update($request->only([
            'name', 'contact_person', 'email',
            'phone', 'address', 'city', 'notes', 'status',
        ]));

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->purchaseOrders()->count() > 0) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Cannot delete supplier with purchase orders.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}