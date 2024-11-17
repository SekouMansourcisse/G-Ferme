<?php

namespace App\Http\Controllers;

use App\Models\Ferme;
use App\Models\Entreprise;
use Illuminate\Http\Request;

class FermeController extends Controller
{
    public function index()
    {
        $entreprises = Entreprise::all();
        $fermes = Ferme::with('entreprise')->get();
        return view('ferme.list', compact('fermes','entreprises'));
    }

    public function create()
    {
        $entreprises = Entreprise::all();
        return view('ferme.add', compact('entreprises'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'typeFerme' => 'nullable|string|max:255',
            'adresse' => 'required|string',
            'entreprise_id' => 'required|exists:entreprises,id',
        ]);

        Ferme::create($validated);

        return redirect()->route('fermes.index');
    }

    public function edit(Ferme $ferme)
    {
        $entreprises = Entreprise::all();
        return view('fermes.edit', compact('ferme', 'entreprises'));
    }

    public function update(Request $request,$id)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'typeFerme' => 'nullable|string|max:255',
            'adresse' => 'required|string',
            'entreprise_id' => 'required|exists:entreprises,id',
        ]);
        $ferme=Ferme::find($id);
        $ferme->update($validated);

        return response()->json(['success' => true, 'message' => 'Ferme mise à jour avec succès']);
    }

    public function destroy($id)
    {
        $ferme = Ferme::findOrFail($id);
        $ferme->delete();

        return response()->json(['success' => true, 'message' => 'ferme supprimée avec succès.']);
    }
}
