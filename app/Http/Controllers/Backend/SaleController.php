<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Box;
use App\Models\Client;
use App\Models\Inventory;
use App\Models\Type_coin;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    public function index(){
        $sales = Sale::orderBy("id","desc")->paginate(10);
        return view('backend.pages.sales.index', compact('sales'))->with([
            'breadcrumbs' => [
                'title' => __('Ventas'),
            ],
        ]);
    }
    public function create(){
        
        $box    = Box::where('is_active', true)->get();
        $client = Client::all();
        $type_coins = Type_coin::where('is_active', true)->get();
        $products = Inventory::all();
        $code = Str::uuid();

        return view('backend.pages.sales.create', compact('box', 'client', 'type_coins', 'products', 'code'))->with([
            'breadcrumbs' => [
                'title' => __('Ventas'),
            ],
         ]);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        // Validar si hay stock suficiente para todos los productos seleccionados
        if ($request->has('products')) {
            $products = $request->input('products'); // array: [product_id => cantidad, ...]

            foreach ($products as $productId => $qty) {
                if ($qty > 0) {
                    $inventory = Inventory::find($productId);
                    if (!$inventory || $inventory->stock < $qty) {
                        return redirect()->back()->with('error', 'El producto "' . ($inventory->name ?? 'Desconocido') . '" no tiene stock suficiente.');
                    }
                }
            }
        }

        // Crear la venta
        $sale = Sale::create($request->all());

        // Guardar los productos asociados con la venta
        if ($request->has('products')) {
            $products = $request->input('products'); // array: [product_id => cantidad, ...]
            foreach ($products as $productId => $qty) {
                if ($qty > 0) {
                    // Asociar el producto con la venta
                    $sale->inventories()->attach($productId, ['quantity' => $qty]);

                    // Actualizar el stock del inventario
                    $inventory = Inventory::find($productId);
                    if ($inventory) {
                        $inventory->stock -= $qty; // Reducir el stock
                        $inventory->save();
                    }
                }
            }
        }

        // Actualizar el balance de la caja
        $box = Box::find($request->input('box_id'));
        if ($box) {
            if($box->id == 1) {
                $box->amount += $request->input('amount'); // Incrementar el balance de la caja
            }
            else{
                $box->amount += $request->input('amount_foreign_currency'); // Decrementar el balance de la caja
            }
            $box->save();
        }

        return redirect()->route('admin.sales.index')->with('success', 'Venta registrada correctamente.');
    }
    
    public function show($id){
        // Logic to display a specific sale
        $sale = Sale::findOrFail($id);
        return view('backend.pages.sales.show', compact('sale'));
    }   
    public function edit($id){
        // Logic to show the form for editing a specific sale
        $sale = Sale::findOrFail($id);
        return view('backend.pages.sales.edit', compact('sale'));
    }
    public function update(Request $request, $id){ 
        $this->validate($request,[
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);
        Sale::findOrFail($id)->update($request->all());
        return redirect()->route('
        ')->with('success','
        Sale updated successfully.');
    }
    public function destroy($id){
     
        $sale = Sale::findOrFail($id);
        // Actualizar el balance de la caja
        $box = Box::find($sale->box_id);
        if ($box) {
            // Asegúrate de que el monto sea positivo y correcto
            if ($sale->amount > 0) {
                $box->amount -= $sale->amount; // Restar el monto de la venta de la caja
                $box->save();
            } else {
                return redirect()->back()->with('error', 'El monto de la venta es inválido.');
            }
        }
        // Restaurar el stock de los productos asociados a la venta
        foreach ($sale->inventories as $inventory) {
            $inventory->stock += $inventory->pivot->quantity; // Incrementar el stock
            $inventory->save();
        }

        Sale::findOrFail($id)->delete();



        return redirect()->route('admin.sales.index')->with('success', 'Venta ha sido eliminada correctamente.');
    }
}
