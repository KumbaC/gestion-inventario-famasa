<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Box;
use App\Models\Type_coin; 
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;


class BoxController extends Controller
{

    public function index()
    {
       $box = Box::orderBy("created_at","desc")->paginate(10);
        return view('backend.pages.box.index', compact('box'))->with([
                'breadcrumbs' => [
                    'title' => __('Cajas'),
                ],
            ]);
    }

    public function create()
    {
        // Logic to show the form for creating a new box
        $type_coins = Type_coin::all();
        return view('backend.pages.box.create', compact('type_coins'))->with([
                'breadcrumbs' => [
                    'title' => __('Crear Cajas'),
                ],
            ]);
    }

    public function store(Request $request)
    {
        //dd($request->all());
        // Logic to store a new box
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'amount'       => 'nullable|string|max:500',
            'type_coin_id' => 'required|exists:type_coins,id',
            'is_active'    => 'boolean',
        ]);


        $box = new Box();
        $box ->name         = $data['name'];
        $box ->amount       = $data['amount'];
        $box ->type_coin_id = $data['type_coin_id'];
        $box ->is_active    = $data['is_active'];
        $box ->user_id      = Auth::user()->id;
        $box->save();

        return redirect()->route('admin.box.index')->with('success', 'La caja ha sido creada correctamente.');
    }
   

    public function edit($id){
        // Logic to show the form for editing a specific box
        $box = Box::findOrFail($id);
        $type_coins = Type_coin::all();
        return view('backend.pages.box.edit', compact('box', 'type_coins'))->with([
                'breadcrumbs' => [
                    'title' => __('Editar Cajas'),
                ],
            ]);
    }

    public function update(Request $request, $id)
    {
        // Logic to update a specific box
        $box = Box::findOrFail($id);
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'amount'       => 'nullable|string|max:500',
            'type_coin_id' => 'required|exists:type_coins,id',
            'is_active'    => 'boolean',
        ]);

        $box->update($data);

        return redirect()->route('admin.box.index')->with('success', 'La caja ha sido actualizada correctamente.');
    }

    public function destroy($id)
    {
        $box = Box::findOrFail($id);
        $sales = Sale::where('box_id', $box->id)->get();
        
        if ($sales->count() > 0) {
            return redirect()->route('admin.box.index')->with('error', 'No se puede eliminar la caja porque hay ventas asociadas a ella.');
        }

        $box->delete();
        return redirect()->route('admin.box.index')->with('success', 'La caja ha sido eliminada correctamente.');
    }

}
