<?php

namespace App\Observers;

use App\Models\Produit;
use App\Models\Notification;

class ProduitObserver
{
    /**
     * Handle the Produit "created" event.
     */
    public function created(Produit $produit): void
    {
        //
    }

    /**
     * Handle the Produit "updated" event.
     */
    // Cette méthode est appelée chaque fois qu'un produit est mis à jour
    public function updated(Produit $produit)
    {
        // Vérifier si le stock est inférieur ou égal au seuil
        if ($produit->qte_stock <= $produit->stock_seuil) {
            // Créer une notification si le seuil est atteint
            Notification::create([
                'produit_id' => $produit->id,
                'message' => 'Le stock de ' . $produit->Denomination . ' a atteint ou dépassé le seuil critique.',
            ]);
        }
    }

    /**
     * Handle the Produit "deleted" event.
     */
    public function deleted(Produit $produit): void
    {
        //
    }

    /**
     * Handle the Produit "restored" event.
     */
    public function restored(Produit $produit): void
    {
        //
    }

    /**
     * Handle the Produit "force deleted" event.
     */
    public function forceDeleted(Produit $produit): void
    {
        //
    }
}
