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
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'email'        => $data['email'],
            'mot_de_passe' => Hash::make($data['mot_de_passe']),
            'role'         => 'admin',
            'api_token'    => Str::random(60),
        ]);

        // Création du admin lié à l'utilisateur
        $admin = Admin::create([
            'prenom'      => $data['prenom'],
            'nom'         => $data['nom'],
            'email'       => $data['email'],
            'mot_de_passe'=> $user->mot_de_passe, // facultatif si pas nécessaire dans admin
            'phone'       => $data['phone'],
            'gouvernorat' => $data['gouvernorat'],
            'user_id'     => $user->id, // 💡 Clé étrangère
        ]);

        return response()->json([
            'user'   => $user,
            'admin' => $admin,
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
        // ✅ Liste des admins
    public function index()
    {
        $admins = Admin::with('user')->get();
        return response()->json($admins);
    }

    // ✅ Créer un nouvel admin
    public function store(Request $request)
    {
        $data = $request->validate([
            'prenom'       => 'required|string|max:255',
            'nom'          => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'mot_de_passe' => 'required|string|min:6',
            'phone'        => 'nullable|string',
            'gouvernorat'  => 'nullable|string',
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
        ]);

        return response()->json($admin, 201);
    }

    // ✅ Voir un admin
    public function show($id)
    {
        $admin = Admin::with('user')->find($id);

        if (! $admin) {
            return response()->json(['message' => 'Admin non trouvé'], 404);
        }

        return response()->json($admin);
    }

    // ✅ Modifier un admin
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

        // Mettre à jour les champs liés au user
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

    // ✅ Supprimer un admin
    public function destroy($id)
    {
        $admin = Admin::find($id);

        if (! $admin) {
            return response()->json(['message' => 'Admin non trouvé'], 404);
        }

        // Supprime le user lié
        $admin->user()->delete();
        $admin->delete();

        return response()->json(['message' => 'Admin supprimé avec succès']);
    }
}