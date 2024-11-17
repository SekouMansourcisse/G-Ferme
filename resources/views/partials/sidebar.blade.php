<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li>
                    <a href="{{url('home')}}"  class="{{ Request::is('home*') ? 'active' : '' }}"><img src="{{ asset('assets/img/icons/dashboard.svg')}}" alt="img"><span>
                            Tableau de bord</span> </a>
                </li>


                @can('read Ravitaillements')
                <li class="submenu ">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/product.svg')}}" alt="img"><span>
                            Gestion de Stock</span> <span class="menu-arrow"></span></a>
                    <ul>
                        @can('read Produit')
                        <li><a href="{{url('listproduit')}}" class="{{ Request::is('listproduit*') ? 'active' : '' }}">Produit</a></li>
                        @endcan
                        @can('read Ravitaillements')
                        <li><a href="{{url('ravitaillement')}}" class="{{ Request::is('ravitaillement*','add_viewravi*') ? 'active' : '' }}">Les ravitaillements</a></li>
                        @endcan
                        @can('read Provenderie')
                        <li><a href="{{url('provenderies')}}" class="{{ Request::is('provenderies*') ? 'active' : '' }}">La Provenderie</a></li>
                        @endcan
                        @can('read Perte Produit')
                        <li><a href="{{url('perte-produit-o-eufs')}}" class="{{ Request::is('perte-produit-o-eufs*') ? 'active' : '' }}">Perte Produit</a></li>
                        @endcan
                        @can('read Perte Oeufs')
                        <li><a href="{{url('perte-eufs')}}" class="{{ Request::is('perte-eufs*') ? 'active' : '' }}">Perte Oeufs</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('read Ventes Oeufs')
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/sales1.svg')}}" alt="img"><span>
                          Gestion des Ventes</span> <span class="menu-arrow"></span></a>
                    <ul>
                        @can('read Ventes Oeufs')
                        <li><a href="{{ url('vente-oeufs')}}" class="{{ Request::is('vente-oeufs*') ? 'active' : '' }}">Ventes Oeufs</a></li>
                        @endcan
                        @can('read Ventes Sujet')
                        <li><a href="{{ url('vente-sujets')}}" class="{{ Request::is('vente-sujets*') ? 'active' : '' }}">Ventes Sujet</a></li>
                        @endcan

                        <li><a href="{{ url('vente-autres')}}" class="{{ Request::is('vente-autres*') ? 'active' : '' }}">Autres Ventes</a></li>

                        @can('read Retour Ventes')
                        <li><a href="{{ url('operation-retours')}}" class="{{ Request::is('operation-retours*') ? 'active' : '' }}">Retour Ventes</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/purchase1.svg')}}" alt="img"><span>
                        Gestion Commande</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{url('Commande')}}" class="{{ Request::is('Commande*') ? 'active' : '' }}">Enregistrer une commande</a></li>
                        <li><a href="{{url('commandes')}}" class="{{ Request::is('commandes*','commande/{id}/details*') ? 'active' : '' }}">Commandes</a></li>

                        <li><a href="{{url('RetourVente')}}" class="{{ Request::is('RetourVente*','RetourV*','Remboursement*') ? 'active' : '' }}">Retour Vente</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="truck"></i>
                        <span>
                        Logistique</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{url('Tableau_bord')}}" class="{{ Request::is('Tableau_bord*') ? 'active' : '' }}">Tableau de bord</a></li>
                        <li><a href="{{url('voitures')}}" class="{{ Request::is('voitures*') ? 'active' : '' }}">Voitures</a></li>
                        <li><a href="{{url('assurances')}}" class="{{ Request::is('assurances*') ? 'active' : '' }}">Assurances</a></li>
                        <li><a href="{{url('vignettes')}}" class="{{ Request::is('vignettes*') ? 'active' : '' }}">Vignette</a></li>
                        <li><a href="{{url('maintenances')}}" class="{{ Request::is('maintenances*') ? 'active' : '' }}">Maintenance</a></li>
                    </ul>
                </li>
                @can('read Gestion Achats')
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/purchase1.svg')}}" alt="img"><span>
                        Gestion Achat</span> <span class="menu-arrow"></span></a>
                    <ul>
                        @can('read Liste Achats')
                        <li><a href="purchaselist.html">Purchase List</a></li>
                        @endcan
                        @can('read Ajouter Achats')
                        <li><a href="addpurchase.html">Add Purchase</a></li>
                        @endcan
                        @can('read Import Achats')
                        <li><a href="importpurchase.html">Import Purchase</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan
                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="briefcase"></i> <!-- Utilise une icône de couteau -->
                    <span>Abattoire</span> <span class="menu-arrow"></span></a>
                    <ul>

                        <li><a href="{{ url('abbatoires')}}" class="{{ Request::is('abbatoires*') ? 'active' : '' }}">Liste des abattoires</a></li>


                        <li><a href="{{url('sujetsAbbatus')}}" class="{{ Request::is('sujetsAbbatus*') ? 'active' : '' }}">Liste des abattages</a></li>

                    </ul>
                </li>
                @can('read Dépenses')
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/expense1.svg')}}" alt="img"><span>
                        Gestion Dépenses</span> <span class="menu-arrow"></span></a>
                    <ul>
                        @can('create Dépenses')
                        <li><a href="{{ url('depenses/create')}}" class="{{ Request::is('depenses/create*') ? 'active' : '' }}">Enregistrer une dépense</a></li>
                        @endcan
                        @can('read Dépenses')
                        <li><a href="{{url('depenses')}}" class="{{ Request::is('depenses*') ? 'active' : '' }}">Liste des dépenses</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('read Fournisseurs')
                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="users"></i><span>
                        Gestion Fournisseurs</span> <span class="menu-arrow"></span></a>
                    <ul>
                        @can('create Fournisseurs')
                        <li><a href="{{url('fournisseur')}}" class="{{ Request::is('fournisseur*') ? 'active' : '' }}">Ajouter Fournisseur </a></li>
                        @endcan
                        @can('read Fournisseurs')
                        <li><a href="{{url('listfournisseur')}}" class="{{ Request::is('listfournisseur*') ? 'active' : '' }}">Liste Fournisseur</a></li>
                        @endcan
                        @can('read Fournisseurs')
                        <li><a href="{{url('listRemboursement')}}" class="{{ Request::is('listRemboursement*') ? 'active' : '' }}">Redevance Fournisseur</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('read Clients')
                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="users"></i><span>
                        Gestion Clients</span> <span class="menu-arrow"></span></a>
                    <ul>
                        @can('create Clients')
                        <li><a href="{{url('client')}}" class="{{ Request::is('client*') ? 'active' : '' }}">Ajouter Client </a></li>
                        @endcan
                        @can('read Clients')
                        <li><a href="{{url('listclient')}}" class="{{ Request::is('listclient*') ? 'active' : '' }}">Liste Client</a></li>
                        @endcan
                        @can('read Clients')
                        <li><a href="{{url('dettes')}}" class="{{ Request::is('dettes*') ? 'active' : '' }}">Remboursement Dettes</a></li>
                        @endcan
                        @can('create Clients')
                        <li><a href="{{url('depot')}}" class="{{ Request::is('depot*') ? 'active' : '' }}">Depôt des Client</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('read Comptes')
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/expense1.svg')}}" alt="img"><span>
                            Gestion des comptes</span> <span class="menu-arrow"></span></a>
                    <ul>
                        @can('create Comptes')
                        <li><a href="{{url('compte')}}" class="{{ Request::is('compte*') ? 'active' : '' }}" >Ajouter un Compte</a></li>
                        @endcan
                        @can('read Comptes')
                        <li><a href="{{ url('listcompte')}}" class="{{ Request::is('listcompte*','comptes/{id}/historique*') ? 'active' : '' }}">Liste des Comptes</a></li>
                        @endcan
                        @can('read Comptes')
                        <li><a href="{{ url('transfert')}}" class="{{ Request::is('transfert*') ? 'active' : '' }}">Transfert Comptes</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('read Situation Financier')
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/eye1.svg')}}" alt="img"><span>
                            Situation Financier</span> <span class="menu-arrow"></span></a>
                    <ul>
                        @can('read Situation Financier')
                        <li><a href="{{ url('BestA')}}"  class="{{ Request::is('BestA*') ? 'active' : '' }}">Meilleur Article</a></li>
                        @endcan
                        @can('read Situation Financier')
                        <li><a href="{{ url('BestC')}}" class="{{ Request::is('BestC*') ? 'active' : '' }}">Meilleur Client</a></li>
                        @endcan
                        @can('read Situation Financier')
                        <li><a href="{{ url('DepenseRecette')}}" class="{{ Request::is('DepenseRecette*') ? 'active' : '' }}">Recette et Dépense</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('read Poulailler')
                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="box"></i><span> Gestion Poulailler</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ url('poulailler')}}" class="{{ Request::is('poulailler*') ? 'active' : '' }}">Ajouter un poulailler</a></li>
                        <li><a href="{{ url('listpoulailler')}}" class="{{ Request::is('listpoulailler*') ? 'active' : '' }}">Liste des poulaillers</a></li>

                    </ul>
                </li>
                @endcan
                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="feather"></i><span>
                        Elevage Bovine</span> <span class="menu-arrow"></span></a>
                    <ul><li><a href="{{url('dashboard')}}" class="{{ Request::is('dashboard*') ? 'active' : '' }}">Tableau de board</a></li>
                        <li><a href="{{url('vaches')}}" class="{{ Request::is('vaches*') ? 'active' : '' }}">Vaches</a></li>
                        <li><a href="{{url('laitieres')}}" class="{{ Request::is('laitieres*','vacheOperation*','vacheStat*') ? 'active' : '' }}">Vaches Laitières</a></li>
                        <li><a href="{{url('productionsL')}}" class="{{ Request::is('productionsL*') ? 'active' : '' }}">Production laitières</a></li>
                        <li><a href="{{url('bovins')}}" class="{{ Request::is('bovins*','OperationBovins*','StatBovins*') ? 'active' : '' }}">Bovins</a></li>
                        <li><a href="{{url('betail')}}" class="{{ Request::is('betail*') ? 'active' : '' }}">Alimentation</a></li>
                    </ul>
                </li>
                @can('read Cycle Production')
                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="layers"></i><span>Cycle Production</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ url('bande')}}" class="{{ Request::is('bande*') ? 'active' : '' }}">Ajouter une bande</a></li>

                        <li><a href="{{ url('listbande')}}" class="{{ Request::is('listbande*') ? 'active' : '' }}">Liste des bandes</a></li>
                        <li><a href="{{ url('listbande')}}" class="{{ Request::is('Statbande*') ? 'active' : '' }}">Statistique des Bandes</a></li>
                    </ul>
                </li>
                @endcan
                @can('read Tri des Oeufs')
                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="box"></i><span>Tri des oeufs</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ url('categorieOeufs')}}" class="{{ Request::is('categorieOeufs*') ? 'active' : '' }}">Liste des categories</a></li>

                        <li><a href="{{ url('classifications')}}" class="{{ Request::is('classifications*') ? 'active' : '' }}">Classification Oeufs</a></li>
                        <li><a href="{{ url('operationCategorisations')}}" class="{{ Request::is('operationCategorisations*') ? 'active' : '' }}">Categorisation Oeufs</a></li>
                    </ul>
                </li>
                @endcan
                @can('read Ressources Humaines')
                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="users"></i><span>
                        Ressources Humaines</span> <span class="menu-arrow"></span></a>
                    <ul>

                        @can('read Ressources Humaines')
                        <li><a href="{{url('AllRoles')}}" class="{{ Request::is('AllRoles*') ? 'active' : '' }}">Profil</a></li>
                        <li><a href="{{url('adduser')}}" class="{{ Request::is('adduser*') ? 'active' : '' }}">Ajouter Employer </a></li>
                        <li><a href="{{url('listuser')}}" class="{{ Request::is('listuser*') ? 'active' : '' }}">Liste Employer</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('read Equipements')
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/product.svg')}}" alt="img"><span>
                        Gestion Equipements</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{url('equipements')}}" class="{{ Request::is('equipements*') ? 'active' : '' }}" >Equipement</a></li>
                        <li><a href="{{url('mouvements')}}" class="{{ Request::is('mouvements*') ? 'active' : '' }}">Mouvement</a></li>

                    </ul>
                </li>
                @endcan
                @can('read Rapport Général')
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/time.svg')}}" alt="img"><span>
                        Rapport Générale</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ url('getProductionReports')}}"  class="{{ Request::is('getProductionReports*') ? 'active' : '' }}">Rapport de Production</a></li>
                        <li><a href="{{ url('generateRapportFinancier')}}" class="{{ Request::is('generateRapportFinancier*') ? 'active' : '' }}">Rapport Financier</a></li>
                        <li><a href="{{ url('getStatferme')}}" class="{{ Request::is('getStatferme*') ? 'active' : '' }}">Statistiques des fermes</a></li>
                    </ul>
                </li>
                @endcan
                @can('read Paramétrages')
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/settings.svg')}}" alt="img"><span>
                            Parametrages</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{url('typesDepense')}}" class="{{ Request::is('typesDepense*') ? 'active' : '' }}">Type de Depense</a></li>
                        <li><a href="{{url('categorieOeufs')}}" class="{{ Request::is('categorieOeufs*') ? 'active' : '' }}">Categories d'oeufs</a></li>
                        <li><a href="{{ url('settings/create')}}" class="{{ Request::is('settings/create*') ? 'active' : '' }}">Parametres generaux</a></li>
                        <li><a href="{{ url('souches')}}" class="{{ Request::is('souches*') ? 'active' : '' }}">Souches</a></li>
                        <li><a href="{{ url('races')}}" class="{{ Request::is('races*') ? 'active' : '' }}">Races</a></li>
                        <li><a href="{{ url('fermes')}}" class="{{ Request::is('fermes*') ? 'active' : '' }}">Ferme</a></li>
                        <li><a href="{{ url('entreprises')}}" class="{{ Request::is('entreprises*') ? 'active' : '' }}">Entreprise</a></li>
                    </ul>
                </li>
                @endcan
            </ul>
        </div>
    </div>
</div>
