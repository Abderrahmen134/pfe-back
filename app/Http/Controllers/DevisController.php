<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use Illuminate\Http\Request;

class DevisController extends Controller
{
    // GET /api/devis
    public function index()
    {
        return Devis::with('client')->get();
    }

    // POST /api/devis
    public function store(Request $request)
    {
        $request->merge([
            'status' => $request->input('status', 'untraited')
        ]);
    
        $validated = $request->validate([
            'status' => 'required|string|min:1',
            'societe' => 'required|string|max:255',
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
            'societe' => 'sometimes|string|max:255',
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

public function getDevisByClient($id)
{
    $devis = Devis::where('id_client', $id)->get();

    if ($devis->isEmpty()) {
        return response()->json(['message' => 'Aucun devis trouvé pour ce client.'], 404);
    }

    return response()->json($devis, 200);
}
public function demanderCommande($id)
{
    $devis = Devis::findOrFail($id);

    // Vérifie que le devis est accepté avant de permettre la demande
    if ($devis->status !== 'accepted') {
        return response()->json(['message' => 'Le devis doit être accepté avant de demander une commande.'], 403);
    }

    $devis->status = 'commande_demandee';
    $devis->save();

    return response()->json(['message' => 'Commande demandée avec succès.']);
}
public function gererCommande(Request $request, $id)
{
    $devis = Devis::findOrFail($id);

    if ($devis->status !== 'commande_demandee') {
        return response()->json(['message' => 'Ce devis n\'est pas en attente de commande.'], 403);
    }

    if ($request->status === 'commande_validee') {
        $devis->status = 'commande_validee';
    } elseif ($request->status === 'commande_refusee') {
        $devis->status = 'commande_refusee';
    } else {
        return response()->json(['message' => 'Status invalide'], 400);
    }

    $devis->save();
    return response()->json(['message' => 'Statut de la commande mis à jour.']);
}
public function devisCommandesDemandees()
{
    // Récupère tous les devis où le statut est "commande_demandee"
    $devis = Devis::where('status', 'commande_demandee')->get();

    return response()->json([
        'success' => true,
        'data' => $devis
    ], 200);
}

public function commandeValidee()
{
    // Récupère tous les devis où le statut est "commande_demandee"
    $devis = Devis::with('client')->where('status', 'commande_validee')->get();

    return response()->json([
        'success' => true,
        'data' => $devis
    ], 200);
}
 public function commandeLivree()
{
    // Récupère tous les devis où le statut est "commande_demandee"
    $devis = Devis::with('client')->where('status', 'commande_livree')->get();

    return response()->json([
        'success' => true,
        'data' => $devis
    ], 200);
}
}