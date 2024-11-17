<?php

namespace App\Http\Controllers;

use App\Models\Abbatoire;
use Illuminate\Http\Request;

class AbbatoireController extends Controller
{
    public function index()
    {
        $abattoires = Abbatoire::all();
        return view('abbatoire.index', compact('abattoires'));
    }

    public function create()
    {
        return view('abbatoire.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'denomination' => 'required',
            'quantite_sujet' => 'required|integer',
            'adresse' => 'required',
        ]);

        Abbatoire::create($validated);

        return redirect()->route('abbatoires.index')->with('success', 'Abbatoire ajouté avec succès.');
    }

    public function show(Abbatoire $abbatoire)
    {
        return view('abbatoires.show', compact('abbatoire'));
    }

    public function edit(Abbatoire $abbatoire)
    {
        return view('abbatoires.edit', compact('abbatoire'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'denomination' => 'required|string|max:255',
            'quantite_sujet' => 'required|integer',
            'adresse' => 'required|string|max:255',
        ]);

        $abattoire = Abbatoire::findOrFail($id);
        $abattoire->update([
            'denomination' => $request->denomination,
            'quantite_sujet' => $request->quantite_sujet,
            'adresse' => $request->adresse,
        ]);

        return response()->json(['success' => 'Abattoir modifié avec succès.']);
    }

    public function destroy($id)
    {
        $abattoire = Abbatoire::findOrFail($id);
        $abattoire->delete();

        return response()->json(['success' => 'Abattoir supprimé avec succès.']);
    }

}

