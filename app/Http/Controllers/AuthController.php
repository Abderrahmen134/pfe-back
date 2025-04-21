<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;

class AuthController extends Controller
{
    public function register(Request $request)
{
    $validated = $request->validate([
        'prénom' => 'required|string|max:255',
        'nom' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'mot_de_passe' => 'required|min:6',
        'phone' => 'required',
        'gouvernorat' => 'required|string',
    ]);

    $user = Client::create([
        'prénom' => $validated['prénom'],
        'nom' => $validated['nom'],
        'email' => $validated['email'],
        'mot_de_passe' => Hash::make($validated['mot_de_passe']),
        'phone' => $validated['phone'],
        'gouvernorat' => $validated['gouvernorat'],
    ]);

    return response()->json(['message' => 'Utilisateur enregistré avec succès'], 201);
}

}
