<?php

namespace App\Http\Controllers;

use App\Models\LigneDevis;
use App\Models\Product;

use Illuminate\Http\Request;

class LigneDevisController extends Controller
{
    public function index()
    {
        return LigneDevis::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_devis' => 'required|integer|exists:devis,id',
            'id_product' => 'required|integer|exists:products,id',
            'quantite' => 'required|integer|min:1',
            //'remise' => 'nullable|numeric|min:0|max:100',
        ]);
    
        // Récupérer le produit pour connaître le prix
        $product = Product::findOrFail($validated['id_product']);
        $prixUnitaire = $product->price;
        $quantite = $validated['quantite'];
        $remise = $validated['remise'] ?? 0;
        $tva = 19;
    
        // Calcul du total HT après remise
        $total_ht = ($prixUnitaire * $quantite) * (1 - ($remise / 100));
        $total_ttc = $total_ht * (1 + ($tva / 100));
    
        $ligne = LigneDevis::create([
            'id_devis' => $validated['id_devis'],
            'id_product' => $validated['id_product'],
            'quantite' => $quantite,
            'remise' => $remise,
            'total_ht' => $total_ht,
            'tva' => $tva,
            'total_ttc' => $total_ttc,
        ]);
    
        return response()->json([
            'message' => 'Ligne de devis créée avec succès',
            'ligne_devis' => $ligne
        ], 201);
    }

    public function show($id)
    {
        return LigneDevis::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $ligne = LigneDevis::findOrFail($id);
    
        // Validation (remise et quantite au minimum)
        $validated = $request->validate([
            'quantite' => 'sometimes|integer|min:1',
            'remise' => 'sometimes|numeric|min:0|max:100',
        ]);
    
        // Met à jour les champs s’ils sont présents
        if (isset($validated['quantite'])) {
            $ligne->quantite = $validated['quantite'];
        }
    
        if (isset($validated['remise'])) {
            $ligne->remise = $validated['remise'];
        }
    
        // Recalculer les totaux
        $product = Product::findOrFail($ligne->id_product);
        $prixUnitaire = $product->price;
        $quantite = $ligne->quantite;
        $remise = $ligne->remise;
        $tva = 19;
    
        $total_ht = ($prixUnitaire * $quantite) * (1 - ($remise / 100));
        $total_ttc = $total_ht * (1 + ($tva / 100));
    
        // Met à jour les totaux
        $ligne->total_ht = $total_ht;
        $ligne->tva = $tva;
        $ligne->total_ttc = $total_ttc;
    
        // Sauvegarde finale
        $ligne->save();
    
        return response()->json([
            'message' => 'Ligne mise à jour avec recalcul des totaux',
            'ligne_devis' => $ligne
        ]);
    }
    

    public function destroy($id)
    {
        return LigneDevis::destroy($id);
    }

    public function getByDevis($id)
{
    $lignes = LigneDevis::with('product')->where('id_devis', $id)->get();

    if ($lignes->isEmpty()) {
        return response()->json(['message' => 'Aucune ligne trouvée pour ce devis'], 404);
    }

    return response()->json($lignes);
}


}
