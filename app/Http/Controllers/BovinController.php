<?php

namespace App\Http\Controllers;

use App\Models\AlimentationBetail;
use App\Models\Bovin;
use App\Models\Parametre;
use App\Models\Produit;
use App\Models\Traitement;
use App\Models\Vache;
use App\Models\VacheLaitiere;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BovinController extends Controller
{
    public function index()
    {
        $vaches = Vache::where('type_elevage','viande')->where('etat',1)
        ->get();
        return view('bovins.list', compact('vaches'));
    }
    public function exportList()
    {
        $vaches = Vache::where('type_elevage','viande')->where('etat',1)
        ->get();
        $settings = Parametre::first();
        $pdf = Pdf::loadView('bovins.pdf', compact('vaches','settings'));

        return $pdf->download('List_retourVente.pdf');
    }

    public function Operation($id)
    {

        $vache=Vache::findOrFail($id);
        $bovins=Bovin::where('vache_id',$id)->get();
        $soins=Traitement::where('vache_id',$id)->get();
        $produits=Produit::where('typeProduit','Autres produits')->get();
        $alimentations=AlimentationBetail::where('vache_id',$id)->get();
        return view('vaches.operation',compact('vache','bovins','produits','alimentations','soins'));
    }
    public function statistique($id)
    {
        $vache=Vache::findOrFail($id);
        $bovins=Bovin::where('vache_id',$id)->get();
        return view('vaches.statistique',compact('vache','bovins'));
    }

    public function create()
    {
        return view('bovins.create');
    }

    public function store(Request $request)
    {
        Bovin::create($request->all());
        return redirect()->route('bovins.index')->with('success', 'Bovin ajouté avec succès.');
    }

    public function show(Bovin $bovin)
    {
        return view('bovins.show', compact('bovin'));
    }

    public function edit(Bovin $bovin)
    {
        return view('bovins.edit', compact('bovin'));
    }

    public function update(Request $request, Bovin $bovin)
    {
        $bovin->update($request->all());
        return redirect()->route('bovins.index')->with('success', 'Bovin mis à jour avec succès.');
    }

    public function destroy(Bovin $bovin)
    {
        $bovin->delete();
        return redirect()->route('bovins.index')->with('success', 'Bovin supprimé avec succès.');
    }
}

