<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'prenom'       => 'required|string|max:255',
            'nom'          => 'required|string|max:255',
            'email'        => 'required|email|unique:admins,email',
            'mot_de_passe' => 'required|string|min:6',
            'phone'        => 'nullable|string',
            'gouvernorat'  => 'nullable|string',
        ]);

        $admin = Admin::create([
            'prenom'       => $data['prenom'],
            'nom'          => $data['nom'],
            'email'        => $data['email'],
            'mot_de_passe' => Hash::make($data['mot_de_passe']),
            'phone'        => $data['phone'] ?? null,
            'gouvernorat'  => $data['gouvernorat'] ?? null,
            'api_token'    => Str::random(60),
        ]);

        return response()->json([
            'admin' => $admin,
            'token' => $admin->api_token,
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'        => 'required|email',
            'mot_de_passe' => 'required|string',
        ]);

        $admin = Admin::where('email', $data['email'])->first();

        if (! $admin || ! Hash::check($data['mot_de_passe'], $admin->mot_de_passe)) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        $admin->api_token = Str::random(60);
        $admin->save();

        return response()->json([
            'admin' => $admin,
            'token' => $admin->api_token,
        ]);
    }
}