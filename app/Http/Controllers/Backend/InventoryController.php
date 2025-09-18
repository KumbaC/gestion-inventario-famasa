<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Box;
use Illuminate\Http\Request;
use App\Models\Client; // Assuming you have a Client model
use App\Models\Inventory; // Assuming you have an Inventory model
use App\Models\Purchase; // Assuming you have a Purchase model
use App\Models\Type_coin; // Assuming you have a Type_coin model    
use App\Models\Supplier; // Assuming you have a Supplier model


class InventoryController extends Controller
{
    public function index()
    {
        // Logic to display the inventory index page
        $items = Inventory::orderBy("created_at","desc")->paginate(10);
        return view('backend.pages.inventory.index', compact('items'))->with([
            'breadcrumbs' => [
                'title' => __('Inventario'),
            ],
        ]);
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        return view('backend.pages.inventory.create', compact('suppliers'))->with([
            'breadcrumbs' => [
                'title' => __('Registrar compra'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        // Logic to store a new inventory item
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'code' => 'required|string|max:50|unique:inventories,code',
            //'typeCoin' => 'required|exists:type_coins,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $data = [
            'name'         => $request->input('name'),
            'stock'        => $request->input('stock'),
            'code'         => $request->input('code'),
            'type_coin_id' => 1, // Assuming typeCoin is the ID of the type coin
            'amount'       => $request->input('amount'),
            'amount_total' => $request->input('amount_total'), // Calculate total amount
            'supplier_id'  => $request->input('supplier') // Assuming supplier_id is passed in the request
        ];  


       $box = Box::find(1); // Encuentra la caja con ID 1

        if($box->amount <= 0){
            return redirect()->back()->with('error', 'No hay suficiente dinero en la caja.');
        }
        
        if ($box) {
            $box->amount -= $request->input('amount_total'); // Resta el monto de la compra
            $box->save(); // Guarda los cambios en la base de datos
        }

        Inventory::create($data);

        return redirect()->route('admin.inventories.index')->with('success', 'Compra registrada correctamente en el inventario.');
    }

    public function show($id)
    {
        // Logic to display a specific inventory item
        $inventory = Inventory::findOrFail($id);
        return view('backend.pages.inventory.show', compact('inventory'));
    }
    public function edit($id)
    {
        $item = Inventory::findOrFail($id);
        $suppliers = Supplier::all(); 
        $typeCoins = Type_coin::all();

        return view('backend.pages.inventory.edit', compact('item', 'typeCoins', 'suppliers'))->with([
            'breadcrumbs' => [
                'title' => __('Editar Inventario'),
            ],
        ]);
    }
    public function update(Request $request, $id)
    {
        // Logic to update a specific inventory item
        $inventory = Inventory::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            //'code' => 'required|string|max:50|unique:inventories,code',
            'stock' => 'required|integer|min:0',
            //'type_coin_id' => 'required|exists:type_coins,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $data = ['name' => $request->input('name'),'stock' => $request->input('stock'),'code' => $request->input('code'),'type_coin_id' => 1,'amount' => $request->input('amount'), 'amount_total' => $request->input('amount_total')];

        $inventory->update($data);

        return redirect()->route('admin.inventories.index')->with('success', 'Inventario actualizado correctamente.');
    }
    public function destroy($id)
    {
        // Logic to delete a specific inventory item
        $inventory = Inventory::findOrFail($id);

        if($inventory->stock <= 0) {
            $inventory->delete();
            return redirect()->route('admin.inventories.index')->with('success', 'Inventario eliminado correctamente.');
        } else {
            return redirect()->route('admin.inventories.index')->with('error', 'No se puede eliminar el inventario porque hay stock disponible.');
        }
    }

}
