<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\SujetAbbatu;
use App\Models\Abbatoire;
use Illuminate\Http\Request;

class SujetAbbatuController extends Controller
{
    public function index()
    {
        $sujetsAbbatus = SujetAbbatu::with('abbatoire')->get();
        $abbatoires = Abbatoire::all();
        return view('abbatoire.abbatu', compact('sujetsAbbatus','abbatoires'));
    }

    public function create()
    {
        $abbatoires = Abbatoire::all();
        return view('abbatoire.addBatu', compact('abbatoires'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'abbatoire_id' => 'required|exists:abbatoire,id',
            'nombre_sujet' => 'required|integer',
            'poids_abbatu' => 'required|numeric',
            'date_abbatage' => 'required|date',
        ]);

        $abbatoire=Abbatoire::find($request->input('abbatoire_id'));
        $abbatoire->quantite_sujet-=$request->input('nombre_sujet');
        $abbatoire->save();
        SujetAbbatu::create($validated);
        $poulet = Produit::where('Denomination', 'Poulet de chair')->first(); // Utilisation de 'first()' pour obtenir un seul produit
        if ($poulet) { // Assurez-vous que le produit existe avant de faire des modifications
            $poulet->qte_stock += $request->input('poids_abbatu'); // Incrémentation du stock
            $poulet->save(); // Sauvegarde des changements
        }


        return redirect()->route('sujetsAbbatus.index')->with('success', 'Sujet abbatu ajouté avec succès.');
    }

    public function show(SujetAbbatu $sujetAbbatu)
    {
        return view('sujetsAbbatus.show', compact('sujetAbbatu'));
    }

    public function edit(SujetAbbatu $sujetAbbatu)
    {
        $abbatoires = Abbatoire::all();
        return view('sujetsAbbatus.edit', compact('sujetAbbatu', 'abbatoires'));
    }

    public function update(Request $request,$id)
    {
        $validated = $request->validate([
            'abbatoire_id' => 'required',
            'nombre_sujet' => 'required|integer',
            'poids_abbatu' => 'required|numeric',
            'date_abbatage' => 'required|date',
        ]);
        $sujetAbbatu=SujetAbbatu::find($id);
        $sujetAbbatu->update($validated);

        return response()->json(['success' => 'Abattage modifié avec succès.']);
    }

    public function destroy($id)
    {
        $abattoire = SujetAbbatu::findOrFail($id);
        $abattoire->delete();

        return response()->json(['success' => 'Abattage supprimé avec succès.']);
    }
}

