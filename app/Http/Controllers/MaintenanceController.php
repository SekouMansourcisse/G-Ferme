<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Parametre;
use App\Models\Voiture;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MaintenanceController extends Controller
{
    // Afficher toutes les maintenances d'une voiture
    public function index()
    {
        $maintenances = Maintenance::all();
        //$voiture = Voiture::find($voiture_id);
        return view('maintenances.index', compact('maintenances'));
    }
    public function exportList()
    {
        $maintenances = Maintenance::all();
        $settings = Parametre::first();
        $pdf = Pdf::loadView('maintenances.pdf', compact('maintenances','settings'));

        return $pdf->download('List_maintenances.pdf');
    }

    // Formulaire pour ajouter une nouvelle maintenance
    public function create()
    {
        $voitures=Voiture::all();
        return view('maintenances.add', compact('voitures'));
    }

    // Enregistrer une nouvelle maintenance
    public function store(Request $request)
    {
        $request->validate([
            'date_maintenance' => 'required|date',
            'type_maintenance' => 'required',
            'cout' => 'required',
            'voiture_id' => 'required',
            'commentaire' => 'nullable',
        ]);

        Maintenance::create([
            'voiture_id' =>  $request->voiture_id,
            'date_maintenance' => $request->date_maintenance,
            'type_maintenance' => $request->type_maintenance,
            'cout' => $request->cout,
            'commentaire' => $request->commentaire,
        ]);

        return redirect()->route('maintenances.index')->with('success', 'Maintenance ajoutée avec succès.');
    }

    // Formulaire pour éditer une maintenance
    public function edit($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        return response()->json($maintenance);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date_maintenance' => 'required|date',
            'type_maintenance' => 'required|string',
            'cout' => 'required|numeric',
            'commentaire' => 'nullable|string',
        ]);

        $maintenance = Maintenance::findOrFail($id);
        $maintenance->update($request->all());

        return response()->json(['success' => true, 'message' => 'Maintenance mise à jour avec succès.']);
    }

    public function destroy($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $maintenance->delete();

        return response()->json(['success' => true, 'message' => 'Maintenance supprimée avec succès.']);
    }
}
