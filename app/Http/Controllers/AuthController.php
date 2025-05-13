<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Client;
use App\Models\Admin;

class AuthController extends Controller
{
    /**
     * POST /api/register
     * Enregistrement d'un client
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'prenom'       => 'required|string|max:255',
            'nom'          => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'mot_de_passe' => 'required|string|min:6',
            'phone'        => 'required|string',
            'gouvernorat'  => 'required|string',
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'email'        => $data['email'],
            'mot_de_passe' => Hash::make($data['mot_de_passe']),
            'role'         => 'client',
            'api_token'    => Str::random(60),
        ]);

        // Création du client lié à l'utilisateur
        $client = Client::create([
            'prenom'      => $data['prenom'],
            'nom'         => $data['nom'],
            'email'       => $data['email'],
            'mot_de_passe'=> $user->mot_de_passe, // facultatif si pas nécessaire dans clients
            'phone'       => $data['phone'],
            'gouvernorat' => $data['gouvernorat'],
            'user_id'     => $user->id, // 💡 Clé étrangère
        ]);

        return response()->json([
            'user'   => $user,
            'client' => $client,
            'token'  => $user->api_token
        ], 201);
    }

    /**
     * POST /api/login
     * Connexion d’un utilisateur (admin ou client)
     */
    public function login(Request $request)
{
    $data = $request->validate([
        'email'        => 'required|email',
        'mot_de_passe' => 'required|string',
    ]);

    // Recherche du client
    $client = Client::where('email', $data['email'])->first();

    // Vérifie si le client existe
    if (! $client || ! Hash::check($data['mot_de_passe'], $client->mot_de_passe)) {
        return response()->json(['message' => 'Identifiants invalides'], 401);
    }

    // Vérifie si le client est actif
    if ($client->statut !== 'actif') {
        return response()->json(['message' => 'Votre compte est désactivé. Veuillez contacter l\'administrateur.'], 403);
    }

    // Génère un nouveau token
    $client->api_token = Str::random(60);
    $client->save();

    return response()->json([
        'client' => $client,
        'token'  => $client->api_token,
    ]);
}

}
