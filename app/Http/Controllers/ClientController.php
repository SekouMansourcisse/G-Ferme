<?php

namespace App\Http\Controllers;

use App\Models\Parametre;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('client.list', ['clients' => $clients]);
    }
    public function exportPdf()
    {
        $clients = Client::all();
        $settings = Parametre::first();
        $pdf = Pdf::loadView('client.pdf', compact('clients','settings'));

        return $pdf->download('liste_clients.pdf');
    }
    public function create()
    {
        return view('client.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'dette_initiale' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'num_whatsapp' => 'required|string|max:255',
            'email' => 'nullable|string|max:255',
            'adresse_physique' => 'required|string|max:255',
            'infos_supp' => 'nullable|string',
            // Ajoutez d'autres règles de validation au besoin
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $client = new Client();
            $client->nom = $request->input('nom');
            $client->prenom = $request->input('prenom');
            $client->dette_initiale = $request->input('dette_initiale');
            $client->phone = $request->input('phone');
            $client->num_whatsapp = $request->input('num_whatsapp');
            $client->email = $request->input('email');
            $client->adresse_physique = $request->input('adresse_physique');
            $client->infos_supp = $request->input('infos_supp');

            $client->save();

            return response()->json(['success' => true, 'client' => $client], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout du client: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'edit-nom' => 'required|string|max:255',
                'edit-prenom' => 'required|string|max:255',
                'edit-dette-initiale' => 'required|string|max:255',
                'edit-telephone' => 'required|string|max:255',
                'edit-num-whatsapp' => 'required|string|max:255',
                'edit-email' => 'nullable|string|max:255',
                'edit-adresse' => 'required|string|max:255',
                'edit-infos-supplementaires' => 'nullable|string',
                // Ajoutez d'autres règles de validation au besoin
            ]);

            $client = Client::findOrFail($id);
            $data = [
                'nom' => $request->input('edit-nom'),
                'prenom' => $request->input('edit-prenom'),
                'dette_initiale' => $request->input('edit-dette-initiale'),
                'phone' => $request->input('edit-telephone'),
                'num_whatsapp' => $request->input('edit-num-whatsapp'),
                'email' => $request->input('edit-email'),
                'adresse_physique' => $request->input('edit-adresse'),
                'infos_supp' => $request->input('edit-infos-supplementaires'),
            ];

            $client->update($data);

            return response()->json(['success' => true, 'message' => 'Client mis à jour avec succès']);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du client: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour du client', 'error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();
            return response()->json(['success' => true, 'message' => 'Client supprimé avec succès']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression du client', 'error' => $e->getMessage()]);
        }
    }

    public function exportClientList()
    {
        // Récupérer la liste des clients
        $clients = Client::all();

        // Chemin vers le logo de l'entreprise
        $logoPath = public_path('assets/img/logo-gferme.png');

        // Les informations de l'entreprise
        $companyInfo = [
            'name' => 'La fermière',
            'address' => 'Banankabougou , Bamako',
            'phone' => '+223 20 25 25 20',
            'email' => 'lafermière@gmail.com',
        ];

        // Générer le PDF
        $pdf = PDF::loadView('client.clients_pdf', compact('clients', 'logoPath', 'companyInfo'));

        // Retourner le PDF téléchargeable
        return $pdf->download('liste_clients.pdf');
    }
    // Ajoutez les autres méthodes comme edit, update, show, destroy selon vos besoins
}
