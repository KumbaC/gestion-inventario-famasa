<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Supplier; // Assuming you have a Supplier model

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy("id","desc")->paginate(10);
        return view('backend.pages.suppliers.index', compact('suppliers'))->with([
            'breadcrumbs' => [
                'title' => __('Proveedores'),
            ],
        ]);
    }
    public function create()
    {
        
        return view('backend.pages.suppliers.create')->with([
            'breadcrumbs' => [
                'title' => __('Proveedores'),
            ],
        ]);
    }
    
    public function store(Request $request)
    {
        // Logic to store a new supplier
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'rif' => 'required|string|max:255|unique:suppliers,rif',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'required|boolean',
        ]);

        // Assuming Supplier is a model that handles the supplier data
        Supplier::create($data);

        return redirect()->route('admin.suppliers.index')->with('success', 'El proveedor se ha creado correctamente.');
    }
    /* public function show($id)
    {
        // Logic to display a specific supplier
        $supplier = Supplier::findOrFail($id);
        return view('backend.pages.suppliers.show', compact('supplier'));
    } */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('backend.pages.suppliers.edit', compact('supplier'))->with([
            'breadcrumbs' => [
                'title' => __('Proveedores'),
            ],
        ]);
    }
    public function update(Request $request, $id){
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'rif' => 'required|string|max:255|unique:suppliers,rif,' . $id,
            'phone' => 'nullable|string|max:20',
            'is_active' => 'required|boolean',
        ]);
        // Logic to update a specific supplier
        $supplier = Supplier::findOrFail($id);
        $supplier->update($data);
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated successfully.');
    }
}
