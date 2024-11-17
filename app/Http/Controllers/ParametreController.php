<?php

namespace App\Http\Controllers;

use App\Models\Parametre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;
class ParametreController extends Controller
{
    public function index()
    {
        $settings = Parametre::first();
        return view('Parametre.add',compact('settings'));
    }

    public function create()
    {
        $settings = Parametre::first();
        return view('Parametre.add',compact('settings'));
    }

    public function store(Request $request)
    {
        // Valider les données reçues
        $data = $request->validate([
            'ferme_name' => 'required|string|max:255',
            'sigle' => 'required|string|max:50',
            'adresse' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'devise' => 'required|string|max:50',
            'Resume' => 'nullable|string',
            'titrelogo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'facturelogo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Récupérer les anciens logos si l'enregistrement existe
        $parametre = Parametre::find(1); // Utilise ID 1 si vous avez une seule ligne pour les paramètres

        // Sauvegarde des nouveaux logos si des fichiers sont chargés, sinon conserver les anciens
        if ($request->hasFile('titrelogo')) {
            $data['logo_titre'] = $request->file('titrelogo')->store('logos', 'public');
        } else {
            $data['logo_titre'] = $parametre->logo_titre ?? null;
        }

        if ($request->hasFile('facturelogo')) {
            $data['logo_facture'] = $request->file('facturelogo')->store('logos', 'public');
        } else {
            $data['logo_facture'] = $parametre->logo_facture ?? null;
        }

        // Utiliser updateOrCreate pour insérer ou mettre à jour les informations de la ferme
        Parametre::updateOrCreate(
            ['id' => 1],
            [
                'nomFerme' => $data['ferme_name'],
                'SigleFerme' => $data['sigle'],
                'adresse' => $data['adresse'],
                'phone_ferme' => $data['phone'],
                'email_ferme' => $data['email'] ?? null,
                'devise' => $data['devise'],
                'facture_message' => $data['Resume'] ?? null,
                'logo_titre' => $data['logo_titre'],
                'logo_facture' => $data['logo_facture'],
            ]
        );

        // Redirection avec message de succès
        return redirect()->route('settings.index')->with('success', 'Les paramètres ont été enregistrés avec succès.');
    }



}
