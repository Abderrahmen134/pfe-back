<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DevisController extends Controller
{
    public function index()
    {
        return response()->json(
            Devis::with(['client', 'product'])->get()
        );
    }

    public function store(Request $request)
    {
            $validated = $request->validate([
                'client_id'   => 'required|exists:clients,id',
                'product_id'  => 'required|exists:products,id',
                'quantity'    => 'required|integer|min:1',
                'note'        => 'nullable|string',
                'status'      => 'sometimes|in:pending,accepted,rejected',
            ]);
        
            $validated['reference'] = 'DV-' . strtoupper(Str::random(8));
        
            $devis = Devis::create($validated);
        
            return response()->json([
                'message' => 'Devis créé avec succès.',
                'data'    => $devis
            ], 201);
        }
        

    public function show($id)
    {
        $devis = Devis::with(['client', 'product'])->findOrFail($id);

        return response()->json($devis);
    }

    public function update(Request $request, $id)
    {
        $devis = Devis::findOrFail($id);

        $validated = $request->validate([
            'quantity' => 'sometimes|integer|min:1',
            'status'   => 'sometimes|in:pending,accepted,rejected',
            'note'     => 'nullable|string',
        ]);

        $devis->update($validated);

        return response()->json([
            'message' => 'Devis mis à jour.',
            'data'    => $devis
        ]);
    }

    public function destroy($id)
    {
        $devis = Devis::findOrFail($id);
        $devis->delete();

        return response()->json([
            'message' => 'Devis supprimé.'
        ]);
    }
}
