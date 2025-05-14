<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'prenom'       => 'required|string|max:255',
            'nom'          => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'mot_de_passe' => 'required|string|min:6',
            'phone'        => 'required|string',
            'gouvernorat'  => 'required|string',
            'statutad'     => 'in:actif,non actif', // facultatif
        ]);

        $user = User::create([
            'email'        => $data['email'],
            'mot_de_passe' => Hash::make($data['mot_de_passe']),
            'role'         => 'admin',
            'api_token'    => Str::random(60),
        ]);

        $admin = Admin::create([
            'prenom'      => $data['prenom'],
            'nom'         => $data['nom'],
            'email'       => $data['email'],
            'mot_de_passe'=> $user->mot_de_passe,
            'phone'       => $data['phone'],
            'gouvernorat' => $data['gouvernorat'],
            'user_id'     => $user->id,
            'statutad'    => $data['statutad'] ?? 'actif',
        ]);

        return response()->json([
            'user'   => $user,
            'admin'  => $admin,
            'token'  => $user->api_token
        ], 201);
    }

    public function login(Request $request)
{
    $data = $request->validate([
        'email'        => 'required|email',
        'mot_de_passe' => 'required|string',
    ]);

    $admin = Admin::where('email', $data['email'])->first();

    // Vérifie si l'admin existe et que le mot de passe est correct
    if (! $admin || ! Hash::check($data['mot_de_passe'], $admin->mot_de_passe)) {
        return response()->json(['message' => 'Identifiants invalides'], 401);
    }

    // ✅ Vérifie si l'admin est actif
    if ($admin->statutad === 'non actif') {
        return response()->json(['message' => 'Votre compte administrateur est désactivé.'], 403);
    }

    $admin->api_token = Str::random(60);
    $admin->save();

    return response()->json([
        'admin' => $admin,
        'token' => $admin->api_token,
    ]);
}


    public function index()
    {
        $admins = Admin::with('user')->get();
        return response()->json($admins);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'prenom'       => 'required|string|max:255',
            'nom'          => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'mot_de_passe' => 'required|string|min:6',
            'phone'        => 'nullable|string',
            'gouvernorat'  => 'nullable|string',
            'statutad'     => 'in:actif,non actif', // facultatif
        ]);

        $user = User::create([
            'email'        => $data['email'],
            'mot_de_passe' => Hash::make($data['mot_de_passe']),
            'role'         => 'admin',
            'api_token'    => Str::random(60),
        ]);

        $admin = Admin::create([
            'prenom'       => $data['prenom'],
            'nom'          => $data['nom'],
            'email'        => $data['email'],
            'mot_de_passe' => $user->mot_de_passe,
            'phone'        => $data['phone'],
            'gouvernorat'  => $data['gouvernorat'],
            'user_id'      => $user->id,
            'statutad'     => $data['statutad'] ?? 'actif',
        ]);

        return response()->json($admin, 201);
    }

    public function show($id)
    {
        $admin = Admin::with('user')->find($id);

        if (! $admin) {
            return response()->json(['message' => 'Admin non trouvé'], 404);
        }

        return response()->json($admin);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::find($id);

        if (! $admin) {
            return response()->json(['message' => 'Admin non trouvé'], 404);
        }

        $data = $request->validate([
            'prenom'       => 'sometimes|string',
            'nom'          => 'sometimes|string',
            'email'        => 'sometimes|email|unique:users,email,' . $admin->user_id,
            'mot_de_passe' => 'sometimes|string|min:6',
            'phone'        => 'sometimes|string',
            'gouvernorat'  => 'sometimes|string',
        ]);

        if (isset($data['email'])) {
            $admin->user->update(['email' => $data['email']]);
        }

        if (isset($data['mot_de_passe'])) {
            $hashedPassword = Hash::make($data['mot_de_passe']);
            $admin->user->update(['mot_de_passe' => $hashedPassword]);
            $data['mot_de_passe'] = $hashedPassword;
        }

        $admin->update($data);

        return response()->json($admin);
    }

    public function destroy($id)
    {
        $admin = Admin::find($id);

        if (! $admin) {
            return response()->json(['message' => 'Admin non trouvé'], 404);
        }

        $admin->user()->delete();
        $admin->delete();

        return response()->json(['message' => 'Admin supprimé avec succès']);
    }

    // ✅ Nouvelle méthode pour mettre à jour le statut
    public function updateStatut(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $validatedData = $request->validate([
            'statutad' => 'required|in:actif,non actif',
        ]);

        $admin->statutad = $validatedData['statutad'];
        $admin->save();

        return response()->json($admin);
    }
}
