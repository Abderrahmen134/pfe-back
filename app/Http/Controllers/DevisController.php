<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use Illuminate\Http\Request;

class DevisController extends Controller
{
    // GET /api/devis
    public function index()
    {
        return Devis::all();
    }

    // POST /api/devis
    public function store(Request $request)
    {
        $request->merge([
            'status' => $request->input('status', 'untraited')
        ]);
    
        $validated = $request->validate([
            'status' => 'required|string|min:1',
            'société' => 'required|string|max:255',
            'id_client' => 'required|exists:clients,id',
            
        ]);

        $devis = Devis::create($validated);

        return response()->json([
            'message' => 'Devis créé avec succès',
            'devis' => $devis
        ], 201);
    }

    // GET /api/devis/{id}
    public function show($id)
    {
        $devis = Devis::find($id);
        if (!$devis) {
            return response()->json(['message' => 'Devis non trouvé'], 404);
        }
        return $devis;
    }

    // PUT /api/devis/{id}
    public function update(Request $request, $id)
    {
        $devis = Devis::find($id);
        if (!$devis) {
            return response()->json(['message' => 'Devis non trouvé'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|string|min:1',
            'société' => 'sometimes|string|max:255',
            'id_client' => 'sometimes|exists:clients,id',
            
        ]);

        $devis->update($validated);

        return response()->json(['message' => 'Devis mis à jour avec succès', 'devis' => $devis]);
    }

    // DELETE /api/devis/{id}
    public function destroy($id)
    {
        $devis = Devis::find($id);
        if (!$devis) {
            return response()->json(['message' => 'Devis non trouvé'], 404);
        }

        $devis->delete();

        return response()->json(['message' => 'Devis supprimé avec succès']);
    }
}
