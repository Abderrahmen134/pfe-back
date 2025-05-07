<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return response()->json(Admin::all());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'prenom'  => 'required|string|max:255',
            'nom'  => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'mot_de_passe' => 'required|string|min:6',
            'phone' => 'nullable|string',
            'gouvernorat' => 'nullable|string',
        ]);

        $admin = Admin::create($validatedData);

        return response()->json($admin, 201);
    }

    public function show($id)
    {
        $admin = Admin::findOrFail($id);
        return response()->json($admin);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $validatedData = $request->validate([
            'prenom'  => 'required|string|max:255',
            'nom'  => 'required|string|max:255',
            'email' => 'sometimes|required|email|unique:admins,email,' . $admin->id,
            'mot_de_passe' => 'required|string|min:6',
            'phone' => 'nullable|string',
            'gouvernorat' => 'nullable|string',
        ]);

        $admin->update($validatedData);

        return response()->json($admin);
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return response()->json(null, 204);
    }
}

