<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Client; // Assuming you have a Client model
use App\Models\Inventory; // Assuming you have an Inventory model
use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function index()
    {
        // Logic to display the purchase index page
        return view('backend.pages.purchases.index');
    }
    public function create()
    {
        // Logic to show the form for creating a new purchase
        return view('backend.pages.purchases.create');
    }
    public function store(Request $request)
    {
        // Logic to store a new purchase
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        // Assuming Purchase is a model that handles the purchase data
        Purchase::create($data);

        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully.');
    }
    /* public function show($id)
    {
        // Logic to display a specific purchase
        $purchase = Purchase::findOrFail($id);
        return view('backend.pages.purchases.show', compact('purchase'));
    } */
    public function edit($id)
    {
        // Logic to show the form for editing a specific purchase
        $purchase = Purchase::findOrFail($id);
        return view('backend.pages.purchases.edit', compact('purchase'));
    }
    public function update(Request $request, $id)
    {
        // Logic to update a specific purchase
        $purchase = Purchase::findOrFail($id);
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        $purchase->update($data);

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }
    public function destroy($id)
    {
        // Logic to delete a specific purchase
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();

        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}
