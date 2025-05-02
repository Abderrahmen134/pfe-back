<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Client;

class AuthController extends Controller
{
    /**
     * POST /api/register
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'prenom'       => 'required|string|max:255',
            'nom'          => 'required|string|max:255',
            'email'        => 'required|email|unique:clients,email',
            'mot_de_passe' => 'required|string|min:6',
            'phone'        => 'required|string',
            'gouvernorat'  => 'required|string',
        ]);

        $client = Client::create([
            'prenom'       => $data['prenom'],
            'nom'          => $data['nom'],
            'email'        => $data['email'],
            'mot_de_passe' => Hash::make($data['mot_de_passe']),
            'phone'        => $data['phone'],
            'gouvernorat'  => $data['gouvernorat'],
            'api_token'    => Str::random(60),
        ]);

        return response()->json([
            'client' => $client,
            'token'  => $client->api_token
        ], 201);
    }

    /**
     * POST /api/login
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'        => 'required|email',
            'mot_de_passe' => 'required|string',
        ]);

        $client = Client::where('email', $data['email'])->first();

        if (! $client || ! Hash::check($data['mot_de_passe'], $client->mot_de_passe)) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        // (Re)génère un nouveau token si souhaité
        $client->api_token = Str::random(60);
        $client->save();

        return response()->json([
            'client' => $client,
            'token'  => $client->api_token
        ]);
    }
}
