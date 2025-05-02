<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;


class ClientController extends Controller
{
    // Afficher la liste des clients
    public function index()
    {
        return response()->json(Client::all());
    }

    // Créer un nouveau client
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'prenom'  => 'required|string|max:255',
            'nom'  => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'mot de passe' => 'required|string|min:6',
            'phone' => 'nullable|string',
            'gouvernorat' => 'nullable|string',
        ]);

        $client = Client::create($validatedData);

        return response()->json($client, 201);
    }

    // Afficher un client spécifique
    public function show($id)
    {
        $client = Client::findOrFail($id);
        return response()->json($client);
    }

    // Mettre à jour un client
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validatedData = $request->validate([
            'prenom'  => 'required|string|max:255',
            'nom'  => 'required|string|max:255',
            'email' => 'sometimes|required|email|unique:clients,email,'.$client->id,
            'mot de passe' => 'required|string|min:6',
            'phone' => 'nullable|string',
            'gouvernorat' => 'nullable|string',
        ]);

        $client->update($validatedData);

        return response()->json($client);
    }

    // Supprimer un client
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json(null, 204);
    }
}