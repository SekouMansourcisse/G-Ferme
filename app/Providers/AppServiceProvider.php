<?php

namespace App\Providers;
use App\Models\Produit;
use App\Observers\ProduitObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Enregistrer l'observer pour le modèle Produit
        Produit::observe(ProduitObserver::class);

        View::composer('*', function ($view) {
            $notifications = Notification::where('lu', false)->get();
            $view->with('notifications', $notifications);
        });

    }
}
