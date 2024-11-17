<?php

namespace App\Http\Controllers;

use App\Models\ProduitLaitier;
use Illuminate\Http\Request;

class ProduitLaitierController extends Controller
{
    public function index()
    {
        $produitsLaitiers = ProduitLaitier::all();
        return view('produits_laitiers.index', compact('produitsLaitiers'));
    }

    public function create()
    {
        return view('produits_laitiers.create');
    }

    public function store(Request $request)
    {
        ProduitLaitier::create($request->all());
        return redirect()->route('produits_laitiers.index')->with('success', 'Produit laitier ajouté.');
    }

    public function show(ProduitLaitier $produitLaitier)
    {
        return view('produits_laitiers.show', compact('produitLaitier'));
    }

    public function edit(ProduitLaitier $produitLaitier)
    {
        return view('produits_laitiers.edit', compact('produitLaitier'));
    }

    public function update(Request $request, ProduitLaitier $produitLaitier)
    {
        $produitLaitier->update($request->all());
        return redirect()->route('produits_laitiers.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(ProduitLaitier $produitLaitier)
    {
        $produitLaitier->delete();
        return redirect()->route('produits_laitiers.index')->with('success', 'Produit supprimé.');
    }
}

