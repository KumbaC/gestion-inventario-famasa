<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client; // Assuming you have a Client model
use App\Models\Position; // Assuming you have an Inventory model

class PositionController extends Controller
{
    public function index()
    {
        $positions = Client::orderBy("created_at","desc")->paginate(10);
        return view("backend.pages.position.index", compact("positions"));
    }
    public function create()
    {
        return view("backend.pages.position.create");
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'inventory_id' => 'required|exists:inventories,id',
        ]);

        Position::create($data);

        return redirect()->route("admin.position.index")->with("success", "Position created successfully.");
    }
    public function show($id)
    {
        $position = Position::findOrFail($id);
        return view("backend.pages.position.show", compact("position"));
    }
    public function edit($id)
    {
        $position = Position::findOrFail($id);
        return view("backend.pages.position.edit", compact("position"));
    }
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'inventory_id' => 'required|exists:inventories,id',
        ]);

        $position = Position::findOrFail($id);
        $position->update($data);

        return redirect()->route("admin.position.index")->with("success", "Position updated successfully.");
    }

    public function destroy($id)
    {
        $position = Position::findOrFail($id);
        $position->delete();

        return redirect()->route("admin.position.index")->with("success", "Position deleted successfully.");
    }

}