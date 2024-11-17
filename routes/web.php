<?php

use App\Http\Controllers\AbbatoireController;
use App\Http\Controllers\AlimentationBetailController;
use App\Http\Controllers\AssuranceController;
use App\Http\Controllers\BovinController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\FermeController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OperationRetourController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\PerteOeufController;
use App\Http\Controllers\PerteProduitOEufController;
use App\Http\Controllers\ProvenderieController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\ReproductionController;
use App\Http\Controllers\RolePermissionsController;
use App\Http\Controllers\SoucheController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\SujetAbbatuController;
use App\Http\Controllers\VacheController;
use App\Http\Controllers\VacheLaitiereController;
use App\Http\Controllers\VenteAutreController;
use App\Http\Controllers\VenteBovinController;
use App\Http\Controllers\VenteOeufController;
use App\Http\Controllers\VenteSujetController;
use App\Http\Controllers\VignetteController;
use App\Http\Controllers\VoitureController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PoulaillerController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\RemboursementController;
use App\Http\Controllers\TransfertController;
use App\Http\Controllers\DepotController;
use App\Http\Controllers\BandeController;
use App\Http\Controllers\AlimentationController;
use App\Http\Controllers\JournalisationController;
use App\Http\Controllers\RamassageOeufController;
use App\Http\Controllers\RamassageLitiereController;
use App\Http\Controllers\TraitementController;
use App\Http\Controllers\PesageController;
use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\OperationCategorisationController;
use App\Http\Controllers\CategorieOeufController;
use App\Http\Controllers\EquipementController;
use App\Http\Controllers\MouvementEquipementController;
use App\Http\Controllers\TypeDepenseController;
use App\Http\Controllers\DepenseController;
// Routes pour les utilisateurs
Route::middleware('auth')->group(function () {

Route::get('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/listuser', [UserController::class, 'index']); // Afficher tous les utilisateurs
Route::get('/adduser', [UserController::class, 'create']);
Route::post('/ajoutUser', [UserController::class, 'store']); // Créer un nouvel utilisateur
Route::get('/users/{id}', [UserController::class, 'show']); // Afficher un utilisateur spécifique
Route::put('/users/{id}', [UserController::class, 'update']); // Mettre à jour un utilisateur
Route::delete('/users/{id}', [UserController::class, 'destroy']); // Supprimer un utilisateur
Route::post('/verifEmail', [UserController::class, 'verifEmail']);
Route::get('/profil',[UserController::class,'profil'] )->name('profil-user');
Route::post('/edit-profile', [UserController::class, 'editProfile'])->name('edit-profil');
Route::post('/updatePhoto', [UserController::class, 'updatePhoto'])->name('edit-photo');
Route::post('/user/{id}', [UserController::class, 'update'])->name('user.update');
Route::delete('/deleteuser/{id}', [UserController::class, 'destroy'])->name('user.destroy');
Route::get('/Roles&P',[UserController::class,'RolesForm'] )->name('RolesForm');
Route::get('/AllRoles',[UserController::class,'ListPermission'] )->name('RolesForm');
// Route pour les poulailler
Route::get('/poulailler/{id}/nom', [PoulaillerController::class, 'getNom']);

Route::get('/poulailler', [PoulaillerController::class, 'create'])->name('poulailler.create');
Route::post('/addpoulailler', [PoulaillerController::class, 'store'])->name('poulailler.store');
Route::get('/listpoulailler', [PoulaillerController::class, 'index'])->name('poulailler.index');
Route::post('/poulailler/{id}', [PoulaillerController::class, 'update'])->name('poulailler.update');
Route::delete('/deletepoulailler/{id}', [PoulaillerController::class, 'destroy'])->name('poulailler.destroy');

// Route pour les bandes
Route::resource('bandes', BandeController::class);
Route::get('/bande', [BandeController::class, 'create'])->name('bande.create');
Route::get('/bandeOperation/{id}', [BandeController::class, 'Operation'])->name('operation');
Route::get('/bandeStat/{id}', [BandeController::class, 'Statistique'])->name('statistique');
Route::get('/calendrier', [BandeController::class, 'calendrierT'])->name('calendrier');
Route::post('/addbande', [BandeController::class, 'store'])->name('bande.store');
Route::get('/listbande', [BandeController::class, 'index'])->name('bande.index');
Route::post('/bande/{id}', [BandeController::class, 'update'])->name('bande.update');
Route::delete('/deletebande/{id}', [BandeController::class, 'destroy'])->name('bande.destroy');

// Route pour les produits
Route::get('/ravitaillement/excel', [ProduitController::class, 'exportExcel'])->name('ravitaillement.export-excel');
Route::get('/produits/export-pdf', [ProduitController::class, 'exportPdf'])->name('produits.export-pdf');
Route::get('/ravitaillement/pdf', [ProduitController::class, 'exportRavitaillementPDF'])->name('ravitaillement.pdf');
Route::get('/produit', [ProduitController::class, 'create'])->name('produit.create');
Route::post('/addproduit', [ProduitController::class, 'store'])->name('produit.store');
Route::get('/listproduit', [ProduitController::class, 'index'])->name('produit.index');
Route::post('/produit/{id}', [ProduitController::class, 'updateProduit'])->name('produit.update');
Route::get('/add_viewravi', [ProduitController::class, 'AddViewRavi'])->name('showRaviform');
Route::post('/addravitaillement', [ProduitController::class, 'enregistrerRavitaillement'])->name('addRavi');
Route::get('/ravitaillements/{id}', [ProduitController::class, 'show']);
Route::delete('/Deleteravitaillements/{id}', [ProduitController::class, 'destroyOperation']);
Route::put('/Editravitaillements/{id}', [ProduitController::class, 'update']);
Route::get('/ravitaillement', [ProduitController::class, 'RavitailleProduit'])->name('Ravitaillement');
Route::delete('/deleteproduit/{id}', [ProduitController::class, 'destroy'])->name('produit.destroy');
Route::delete('/suppravitaillement/{id}', [ProduitController::class, 'supprimerRavitaillement'])->name('ravitaillement.supprimer');

// Route pour les fournisseur
Route::get('/fournisseurs/export-pdf', [FournisseurController::class, 'exportPdf'])->name('fournisseurs.export-pdf');
Route::get('/fournisseur', [FournisseurController::class, 'create'])->name('fournisseur.create');
Route::post('/addfournisseur', [FournisseurController::class, 'store'])->name('fournisseur.store');
Route::get('/listfournisseur', [FournisseurController::class, 'index'])->name('fournisseur.index');
Route::post('/fournisseur/{id}', [FournisseurController::class, 'update'])->name('fournisseur.update');
Route::delete('/deletefournisseur/{id}', [FournisseurController::class, 'destroy'])->name('fournisseur.destroy');

// Remboursement
Route::get('/remboursements/{id}/edit', [RemboursementController::class, 'edit']);
Route::put('/Editremboursements/{id}', [RemboursementController::class, 'update']);
Route::get('/Fremboursements/{id}', [RemboursementController::class, 'show']);
Route::delete('/deleteremboursement/{id}', [RemboursementController::class, 'destroy'])->name('remboursement.destroy');
Route::get('/showRemboursementF', [RemboursementController::class, 'create'])->name('fournisseur.create');
Route::get('/showRemboursementC', [RemboursementController::class, 'create2'])->name('RemboursementClient');
Route::get('/fournisseur/{id}/operations', [RemboursementController::class, 'getOperations'])->name('redevance-fournisseur.operations');
Route::get('/client/{id}/operations', [RemboursementController::class, 'getOperations2'])->name('dette-client.operations');
Route::post('/ajout-remboursement', [RemboursementController::class, 'store']);
Route::get('/dettes', [RemboursementController::class, 'listdette'])->name('RemboursementC.list');
Route::get('/listRemboursement', [RemboursementController::class, 'index'])->name('fournisseur.index');
Route::get('/remboursementF/export-pdf', [RemboursementController::class, 'exportPdf'])->name('Rfournisseur.export-pdf');
Route::get('/remboursementC/export-pdf', [RemboursementController::class, 'exportPdf2'])->name('Rclient.export-pdf');
// Route pour les clients
Route::get('/client', [ClientController::class, 'create'])->name('client.create');
Route::post('/addclient', [ClientController::class, 'store'])->name('client.store');
Route::get('/listclient', [ClientController::class, 'index'])->name('client.index');
Route::post('/client/{id}', [ClientController::class, 'update'])->name('client.update');
Route::get('/clients/export-pdf', [ClientController::class, 'exportPdf'])->name('client.export-pdf');
Route::delete('/deleteclient/{id}', [ClientController::class, 'destroy'])->name('client.destroy');

// Route pour les comptes
Route::get('/transfert', [CompteController::class, 'showTransfert'])->name('compte.create');
Route::get('/compte', [CompteController::class, 'create'])->name('compte.create');
Route::post('/addcompte', [CompteController::class, 'store'])->name('compte.store');
Route::get('/listcompte', [CompteController::class, 'index'])->name('compte.index');
Route::post('/compte/{id}', [CompteController::class, 'update'])->name('compte.update');
Route::delete('/deletecompte/{id}', [CompteController::class, 'destroy'])->name('compte.destroy');
Route::get('/comptes/{id}/historique', [CompteController::class, 'historique'])->name('comptes.historique');


// Route pour transfert
Route::post('/addtransfert', [TransfertController::class, 'store'])->name('transfert.store');
Route::get('/transfert', [TransfertController::class, 'index'])->name('transfert.index');
Route::post('/transfert/{id}', [TransfertController::class, 'update'])->name('transfert.update');
Route::delete('/deletetransfert/{id}', [TransfertController::class, 'destroy'])->name('transfert.destroy');
Route::get('/getTransfertDetails/{id}', [TransfertController::class,'getTransfertDetails'])->name('getTransfertDetails');
Route::post('/approvisionnement', [TransfertController::class, 'appro_caisse'])->name('appro_caisse');
//Route pour le depot
Route::get('/showdepotform', [DepotController::class, 'create'])->name('depot.create');
Route::get('/client-depot-solde/{clientId}', [DepotController::class, 'getDepotSolde']);
Route::post('/add-depot', [DepotController::class, 'store'])->name('depot.store');
Route::get('/depot', [DepotController::class, 'index'])->name('depot.index');
Route::post('/depot/{id}', [DepotController::class, 'update'])->name('depot.update');
Route::delete('/deletedepot/{id}', [DepotController::class, 'destroy'])->name('depot.destroy');
Route::get('/getDepotDetails/{id}', [DepotController::class,'getDepotDetails'])->name('getDepotDetails');

//Operations sur les bandes

Route::resource('alimentations', AlimentationController::class);
Route::resource('journalisations', JournalisationController::class);
Route::get('/getMortaliteStatistics', [JournalisationController::class, 'getMortaliteStatistics']);
Route::resource('ramassageoeufs', RamassageOeufController::class);
Route::get('/getPointeStatistics', [RamassageOeufController::class, 'getPointeStatistics']);
Route::get('/getConsomationStatistics', [AlimentationController::class, 'getConsommationStatistics']);
Route::resource('ramassagelitières', RamassageLitiereController::class);
Route::resource('traitements', TraitementController::class);
Route::post('/valider', [TraitementController::class, 'valider'])->name('validertraitement');
Route::post('/add-event', [TraitementController::class, 'addEvent']);
Route::get('/get-events', [TraitementController::class, 'getEvents']);
Route::resource('pesages', PesageController::class);
Route::get('/getCroissanceStatistics', [PesageController::class, 'getCroissanceStatistics']);

// Tri des Oeufs
Route::resource('classifications', ClassificationController::class);
Route::resource('operationCategorisations', OperationCategorisationController::class);
Route::resource('categorieOeufs', CategorieOeufController::class);

// Depense et Equipement
Route::resource('equipements', EquipementController::class);
Route::resource('mouvements', MouvementEquipementController::class);
Route::resource('typesDepense', TypeDepenseController::class);
Route::get('/type_depenses/export-pdf', [TypeDepenseController::class, 'exportPdf'])->name('type_depenses.export-pdf');
Route::resource('depenses', DepenseController::class);
Route::get('/depense_pdf/export-pdf', [DepenseController::class, 'exportPdf'])->name('depenses.export-pdf');

// PerteProduit et Provenderie
Route::delete('/pertes-oeufs/{id}', [PerteOeufController::class, 'destroy'])->name('pertes-oeufs.destroy');
Route::post('/pertes-oeufs_update/{id}', [PerteOeufController::class, 'update'])->name('pertes-oeufs.update');
Route::get('/pertes-oeufs/{id}/edit', [PerteOeufController::class, 'edit'])->name('pertes-oeufs.edit');

Route::resource('perte-eufs', PerteOeufController::class);
Route::get('/perte_oeufs/export-pdf', [PerteOeufController::class, 'exportPdf'])->name('perte_oeufs.export-pdf');
Route::get('/perteProduit/export-pdf', [PerteProduitOEufController::class, 'exportPdf'])->name('perteProduit.export-pdf');
Route::resource('perte-produit-o-eufs', PerteProduitOEufController::class);
Route::delete('/deletepertes-produit/{id}', [PerteProduitOEufController::class, 'destroy'])->name('pertes-produit.destroy');
Route::post('/pertes-produit_update/{id}', [PerteProduitOEufController::class, 'update'])->name('pertes-oeufs.update');
Route::get('/pertes-produit/{id}/edit', [PerteProduitOEufController::class, 'edit'])->name('pertes-oeufs.edit');

Route::resource('operation-retours', OperationRetourController::class);
Route::resource('provenderies', ProvenderieController::class);
Route::get('/provenderie/export-pdf', [ProvenderieController::class, 'exportPdf'])->name('provenderie.export-pdf');
Route::get('/fetch/operations', [OperationRetourController::class, 'fetchOperations'])->name('fetch.operations');
Route::get('/fetch/operation/details', [OperationRetourController::class, 'fetchOperationDetails'])->name('fetch.operation_details');
Route::get('/retour_vente/export-pdf', [OperationRetourController::class, 'exportPdf'])->name('retour_vente.export-pdf');
Route::get('/fetch/operations', [OperationRetourController::class, 'fetchOperations1'])->name('fetch.operations1');
Route::get('/operation_details', [OperationRetourController::class, 'fetchOperationDetails1'])->name('fetch.operation.details1');
Route::post('/operation-retours2', [OperationRetourController::class, 'store2'])->name('operation-retours.store2');
Route::put('/operation-retours/{id}', [OperationRetourController::class, 'update'])->name('operation-retours.update');
Route::delete('/operation-retours/{id}', [OperationRetourController::class, 'destroy'])->name('operation-retours.destroy');
// Pour les commandes
Route::resource('commandes', CommandeController::class);
Route::get('/commande/{id}/details', [CommandeController::class, 'showDetails'])->name('detailscommande');

Route::post('/payment', [CommandeController::class, 'processPayment'])->name('commande.payment');
Route::get('/commande/{id}/invoiceP', [CommandeController::class, 'showInvoiceP'])->name('invoicepay');
Route::get('/commande/{id}/exportPdf', [CommandeController::class, 'exportPdf'])->name('exportpdf');
Route::post('/upload-invoice', [CommandeController::class, 'uploadInvoice'])->name('uploadInvoice');
Route::get('/RetourV', [CommandeController::class, 'RetourV'])->name('CommandeRetour');
Route::get('/RetourVente', [CommandeController::class, 'RetourVente'])->name('CommandeRetourVente');
Route::get('/commande/{id}/Remboursement', [CommandeController::class, 'FactureRemb'])->name('Remboursement');
Route::get('/commande/{id}/Remplacement', [CommandeController::class, 'FactureRemp'])->name('Remplacement');
Route::post('/RembourserV', [CommandeController::class, 'RembourserVente'])->name('Rembourser');
Route::post('/upload-remplacement', [CommandeController::class, 'uploadBonRemplacement'])->name('uploadRemplacement');
Route::get('/ListRetourexportPdf', [CommandeController::class, 'exportList'])->name('exportlistRetour');
// pour les ventes
Route::resource('vente-oeufs', VenteOeufController::class);
// Route for deletion
Route::delete('/vente-oeufs/{id}', [VenteOeufController::class, 'destroy'])->name('vente-oeufs.destroy');
Route::put('/vente-sujet/{id}', [VenteSujetController::class, 'update'])->name('venteSujet.update');
Route::put('/vente-oeufs/{id}', [VenteOeufController::class, 'update'])->name('vente-oeufs.update');
Route::put('/vente-autres/{id}', [VenteAutreController::class, 'update'])->name('vente-autres.update');
Route::get('/Commande', [VenteOeufController::class, 'Venteview'])->name('commande');
Route::get('/vente_oeuf/export-pdf', [VenteOeufController::class, 'exportPdf'])->name('vente_oeufs.export-pdf');
Route::resource('vente-sujets', VenteSujetController::class);
Route::get('/vente_sujet/export-pdf', [VenteSujetController::class, 'exportPdf'])->name('vente_sujet.export-pdf');
Route::resource('vente-autres', VenteAutreController::class);
Route::get('/vente_autres/export-pdf', [VenteAutreController::class, 'exportPdf'])->name('vente_autre.export-pdf');

//Parametres generaux
Route::resource('settings', ParametreController::class);
Route::resource('souches', SoucheController::class);

// Roles et Permissions
Route::resource('roles', RolePermissionsController::class);

// Ferme et Entreprise
Route::resource('entreprises', EntrepriseController::class);
Route::resource('fermes', FermeController::class);

// Statistique et Depense
Route::get('/DepenseRecette',[StatistiqueController::class,'DepenseRecette']);
Route::get('/BestA',[StatistiqueController::class,'BestArticle']);
Route::get('/BestC',[StatistiqueController::class,'BestClient']);
Route::get('/getRecetteDepenseStatistics', [StatistiqueController::class, 'getRecetteDepenseStatistics']);
Route::get('/getBestArticles', [StatistiqueController::class, 'getBestArticles']);
Route::get('/getBestClients', [StatistiqueController::class, 'getBestClients']);
Route::get('/getStatferme', [StatistiqueController::class, 'getStatferme']);
Route::get('/getBandAge', [StatistiqueController::class, 'getBandAge']);
Route::get('/getBandPerformance', [StatistiqueController::class, 'getBandPerformance']);
Route::get('/getFermeStatRepport', [StatistiqueController::class, 'getFermeStatRepport']);
Route::get('/getProductionReports', [StatistiqueController::class, 'getProductionReports']);
Route::get('/generateRapportFinancier', [StatistiqueController::class, 'generateRapportFinancier']);


// Ferme bovins
Route::resource('vaches', VacheController::class);
Route::get('/dashboard', [VacheController::class, 'dashboard']);
Route::get('/data/charts', [VacheController::class, 'getChartData']);
Route::resource('races', RaceController::class);
Route::resource('laitieres', VacheLaitiereController::class);
Route::get('/getVacheStatistics', [VacheLaitiereController::class, 'getVacheStatistics'])->name('getVacheStatistics');
Route::get('/listVacheLpdf', [VacheLaitiereController::class, 'exportList'])->name('exportPdfVacheL');
Route::get('/listVachepdf', [VacheController::class, 'exportList'])->name('exportPdfVache');
Route::post('/etat_sante', [VacheController::class, 'sante'])->name('EtatSante');
Route::get('/vacheOperation/{id}', [VacheLaitiereController::class, 'OperationL'])->name('VacheOperation');
Route::get('/vacheStat/{id}', [VacheLaitiereController::class, 'statistiqueL'])->name('VacheStat');
Route::get('/productionsL', [VacheLaitiereController::class, 'productionLait'])->name('productionsL');
Route::resource('bovins', BovinController::class);
Route::get('/OperationBovins/{id}', [BovinController::class, 'Operation'])->name('BovinOperation');
Route::get('/StatBovins/{id}', [BovinController::class, 'statistique'])->name('BovinStat');
Route::get('/listBpdf', [BovinController::class, 'exportList'])->name('exportListBovins');
Route::resource('reproductions', ReproductionController::class);
Route::resource('ventes_bovins', VenteBovinController::class);
Route::delete('/ventes_bovins/{id}', [VenteBovinController::class, 'destroy'])->name('ventes_bovins.destroy');
Route::resource('betail', AlimentationBetailController::class);

// Logistique
Route::resource('voitures', VoitureController::class);
Route::get('/listvoiturepdf', [VoitureController::class, 'exportList'])->name('exportPdfVoiture');
Route::get('/listmaintenancepdf', [MaintenanceController::class, 'exportList'])->name('exportPdfmaint');
Route::get('/listvignettepdf', [VignetteController::class, 'exportList'])->name('exportPdfVignette');
Route::get('/listassurancepdf', [AssuranceController::class, 'exportList'])->name('exportPdfassurance');
Route::resource('assurances', AssuranceController::class);
Route::resource('vignettes', VignetteController::class);
Route::resource('maintenances', MaintenanceController::class);
Route::get('/Tableau_bord', [VoitureController::class, 'dashbord']);

// Notification
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

// Abattoires
Route::resource('sujetsAbbatus', SujetAbbatuController::class);
Route::resource('abbatoires', AbbatoireController::class);
Route::post('/cloturer-bande', [BandeController::class, 'cloturerBande'])->name('bande.cloturer');


});
Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('admin.home'); // Charger la vue admin/home.blade.php
    })->name('admin.home');

    // Définissez d'autres routes pour l'administration si nécessaire
});
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login2', [UserController::class, 'login']);

Route::get('/', function () {
    return view('users.login');
});
