<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $table = 'produit';
    protected $fillable = [
        'reference_produit',
        'Denomination',
        'stock_seuil',
        'prix_unitaire',
        'qte_stock',
        'infos_supp',
        'typeProduit',
    ];

    // In Produit.php
public function operations()
{
    return $this->hasMany(Operation::class, 'Produit', 'id');
}

public function checkStock()
{
    // Vérifie si le stock est inférieur ou égal au seuil
    if ($this->qte_stock <= $this->stock_seuil) {
        // Appeler la fonction de création de notification
        $this->createLowStockNotification();
    }
}

protected function createLowStockNotification()
{
    // Créer une notification dans la base de données ou autre méthode
    Notification::create([
        'produit_id' => $this->id,
        'message' => 'Le stock de ' . $this->Denomination . ' a atteint le seuil critique.',
    ]);
}

}
