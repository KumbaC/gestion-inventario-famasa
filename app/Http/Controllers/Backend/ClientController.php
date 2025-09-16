<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('backend.pages.clients.index', compact('clients'))->with([
            'breadcrumbs' => [
                'title' => __('Clientes'),
            ],
        ]);
    }

    public function create()
    {
        return view('backend.pages.clients.create')->with([
            'breadcrumbs' => [
                'title' => __('Crear Cliente'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        // Logic to store a new client
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'identification' => 'required|string|max:255|unique:clients,identification',
            'phone' => 'nullable|string|max:20',
        ]);

        Client::create($data);

        return redirect()->route('admin.clients.index')->with('success', 'Cliente creado exitosamente.');
    }

    /* public function show($id)
    {
        // Logic to display a specific client
        $client = Client::findOrFail($id);
        return view('backend.pages.clients.show', compact('client'));
    } */

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('backend.pages.clients.edit', compact('client'))->with([
            'breadcrumbs' => [
                'title' => __('Editar Cliente'),
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        // Logic to update a specific client
        $client = Client::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'identification' => 'required|string|max:255|unique:clients,identification',
            'phone' => 'nullable|string|max:20',
        ]);

        $client->update($data);

        return redirect()->route('clients.index')->with('success', 'Cliente actualizado exitosamente.');
    }
    
    public function destroy($id)
    {
        // Logic to delete a specific client
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente eliminado exitosamente.');
    }

}
