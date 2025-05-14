<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    // Afficher la liste de tous les types
    public function index()
    {
        return response()->json(Type::all(), 200);
    }

    // Créer un nouveau type
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:types,name',
        ]);

        $type = Type::create([
            'name' => $request->name,
        ]);

        return response()->json($type, 201);
    }

    // Afficher un type spécifique
    public function show($id)
    {
        $type = Type::find($id);

        if (!$type) {
            return response()->json(['message' => 'Type non trouvé'], 404);
        }

        return response()->json($type, 200);
    }

    // Mettre à jour un type
    public function update(Request $request, $id)
    {
        $type = Type::find($id);

        if (!$type) {
            return response()->json(['message' => 'Type non trouvé'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:types,name,' . $id,
        ]);

        $type->update([
            'name' => $request->name,
        ]);

        return response()->json($type, 200);
    }

    // Supprimer un type
    public function destroy($id)
    {
        $type = Type::find($id);

        if (!$type) {
            return response()->json(['message' => 'Type non trouvé'], 404);
        }

        $type->delete();

        return response()->json(['message' => 'Type supprimé avec succès'], 200);
    }
}
