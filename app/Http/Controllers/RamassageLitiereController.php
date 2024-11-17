<?php

namespace App\Http\Controllers;

use App\Models\Bande;
use App\Models\RamassageLitiere;
use Illuminate\Http\Request;

class RamassageLitiereController extends Controller
{
    public function index()
    {
        $ramassageLitières = RamassageLitiere::all();
        return view('ramassagelitières.index', compact('ramassageLitières'));
    }

    public function create()
    {
        return view('ramassagelitières.create');
    }

    public function store(Request $request)
    {
        // Valider les données
        $request->validate([
            'Date' => 'required|date',
            'qte' => 'required|array',
            'qte.*' => 'required|numeric',
            'Resume' => 'required|string',
            'bande_id' => 'required|integer|exists:bande,id'
        ]);

        // Récupérer les données
        $date = $request->input('Date');
        $resume = $request->input('Resume');
        $bande_id = $request->input('bande_id');
        $quantites = $request->input('qte');

        // Récupérer les poulaillers associés à la bande
        $bande = Bande::find($bande_id);
        $poulaillers = explode(',', $bande->poulailler);

        $poulaillerData = [];
        $totalQuantite = 0;

        foreach ($poulaillers as $index => $poulailler) {
            list($id, $cheptel) = explode('*', $poulailler);
            $poulaillerData[] = "$id*{$quantites[$index]}";
            $totalQuantite += $quantites[$index];
        }

        $poulaillerString = implode(',', $poulaillerData);

        // Créer une nouvelle instance de RamassageLitiere
        RamassageLitiere::create([
            'poulailler' => $poulaillerString,
            'qte_ramasser' => $totalQuantite,
            'date' => $date,
            'commentaire' => $resume,
            'bande_id' => $bande_id
        ]);

        // Rediriger vers la vue 'operation' avec l'onglet 'ramassagelitiere' actif
        session()->flash('active_tab', 'ramassage-litiere');

        return redirect()->route('operation', ['id' => $bande_id])->with('success', 'Rassamage effectué avec succès.');
    }


    public function show($id)
    {
        $ramassageLitiere = RamassageLitiere::find($id);
        return view('ramassagelitières.show', compact('ramassageLitiere'));
    }

    public function edit($id)
    {
        $ramassageLitiere = RamassageLitiere::find($id);
        return view('ramassagelitières.edit', compact('ramassageLitiere'));
    }

    public function update(Request $request, $id)
    {
        $ramassageLitiere = RamassageLitiere::find($id);
        $ramassageLitiere->update($request->all());
        return redirect()->route('ramassagelitières.index');
    }

    public function destroy($id)
    {
        $ramassageLitiere = RamassageLitiere::find($id);
        $ramassageLitiere->delete();
        return redirect()->route('ramassagelitières.index');
    }
}

