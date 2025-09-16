<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Type_coin as TypeCoin;

class TypeCoinController extends Controller
{
    public function index()
    {
        $typeCoins = TypeCoin::orderBy('created_at', 'desc')->paginate(10);
        return view('backend.pages.typecoins.index', compact('typeCoins'))->with([
            'breadcrumbs' => [
                'title' => __('Tipo de monedas'),
            ],
        ]);
    }
    public function create()
    {
        // Logic to show the form for creating a new type coin
        return view('backend.pages.typecoins.create')->with([
            'breadcrumbs' => [
                'title' => __('Crear Type Coin'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        // Logic to store a new type coin
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'symbol'       => 'required|string|max:10|unique:type_coins,symbol',
            'description'  => 'required|string|max:1000',
            'is_active'    => 'boolean',

        ]);

        // Assuming TypeCoin is a model that handles the type coin data
        TypeCoin::create($data);

        return redirect()->route('admin.type-coins.index')->with('success', 'Type coin created successfully.');
    }

    public function edit($id){
        $typeCoin = TypeCoin::findOrFail($id);
        return view('backend.pages.typecoins.edit', compact('typeCoin'))->with([
            'breadcrumbs' => [
                'title' => __('Editar Tipo de moneda'),
            ],
        ]);
    }

    public function update(Request $request, $id){
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'symbol'       => 'required|string|max:10|unique:type_coins,symbol,' . $id,
            'description'  => 'required|string|max:1000',
            'is_active'    => 'boolean',
        ]);

        // Logic to update a specific type coin
        $typeCoin = TypeCoin::findOrFail($id);
        $typeCoin->update($data);
        return redirect()->route('admin.type-coins.index')->with('success', 'Type coin updated successfully.');
    }

    public function destroy($id)
    {
        try {
            TypeCoin::destroy($id);
            return redirect()->route('admin.type-coins.index')
                ->with('success', 'Type coin deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) { // Código de error para restricción de clave foránea
                return redirect()->route('admin.type-coins.index')
                    ->with('error', 'No se puede eliminar el tipo de moneda porque está siendo utilizado en una caja.');
            }
            // Otros errores
            return redirect()->route('admin.type-coins.index')
                ->with('error', 'Ocurrió un error al intentar eliminar el tipo de moneda.');
        }
    }

}
