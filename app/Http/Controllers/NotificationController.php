<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Produit;
use Illuminate\Http\Request;

class NotificationController extends Controller
{


        // Afficher les notifications
        public function index()
        {
            $notifications = Notification::where('lu', false)->get();
            return view('notification.index', compact('notifications'));
        }

        // Créer une notification
        public function create(Request $request)
        {
            $produit = Produit::find($request->produit_id);

            if ($produit->qte_stock <= $produit->stock_seuil) {
                Notification::create([
                    'produit_id' => $produit->id,
                    'message' => 'Le stock de ' . $produit->Denomination . ' a atteint le seuil critique.',
                ]);
            }

            return redirect()->back()->with('success', 'Notification créée.');
        }
            // Afficher les détails d'une notification
    public function show($id)
    {
        $notification = Notification::findOrFail($id);

        // Marquer la notification comme lue
        if (!$notification->read) {
            $notification->update(['read' => true]);
        }

        return view('notification.show', compact('notification'));
    }
    public function update(Request $request, $id)
    {
        $produit = Produit::find($id);
        $produit->update($request->all());

        // Vérifier le stock après mise à jour
        $produit->checkStock();

        return redirect()->back()->with('success', 'Produit mis à jour avec succès.');
    }

    public function getNotifications()
    {
        $notifications = Notification::where('lu', false)->get();

        return view('your-view', compact('notifications'));
    }

    public function markAsRead()
    {
        Notification::where('lu', false)->update(['lu' => true]);

        return redirect()->back();
    }
    // Marquer toutes les notifications comme lues
    public function markAllRead()
    {
        Notification::where('lu', false)->update(['lu' => true]);
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

}
