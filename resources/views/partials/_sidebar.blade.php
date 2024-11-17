<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                @can('read Tableau de bord')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Main</h6>
                    <ul>

                        <li>
                            <a href="{{ url('home') }}" class="{{ Request::is('home') ? 'active' : '' }}"><i
                                    data-feather="grid"></i><span>Tableau de bord</span><span></span></a>

                        </li>

                    </ul>
                </li>
                @endcan
                @can('read Produit')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Gestion de stock</h6>
                    <ul>

                        <li class="{{ Request::is('listproduit*') ? 'active' : '' }}">
                            <a href="{{ url('listproduit') }}"><i data-feather="box"></i><span>Produits</span></a>
                        </li>

                        @can('create Produit')
                        <li class="{{ Request::is('produit') ? 'active' : '' }}">
                            <a href="{{ url('produit') }}"><i data-feather="plus-square"></i><span>Ajouter Produit</span></a>
                        </li>
                        @endcan
                        @can('read Ravitaillements')
                        <li class="{{ Request::is('ravitaillement', 'add_viewravi') ? 'active' : '' }}">
                            <a href="{{ url('ravitaillement') }}"><i data-feather="codesandbox"></i><span>Ravitaillement</span></a>
                        </li>
                        @endcan
                        @can('read Perte Produit')
                        <li class="{{ Request::is('perte-produit-o-eufs', 'perte-produit-o-eufs/create') ? 'active' : '' }}">
                            <a href="{{ url('perte-produit-o-eufs') }}"><i data-feather="trending-down"></i><span>Perte de produit</span></a>
                        </li>
                        @endcan
                        @can('read Perte Oeufs')
                        <li class="{{ Request::is('perte-eufs', 'perte-eufs/create') ? 'active' : '' }}">
                            <a href="{{ url('perte-eufs') }}"><i data-feather="trending-down"></i><span>Perte d'Oeufs</span></a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('read voitures')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Logistique</h6>
                    <ul>

                        <li class="{{ Request::is('Tableau_bord') ? 'active' : '' }}"><a
                            href="{{ url('Tableau_bord') }}"><i data-feather="grid"></i><span>Tableau de bord
                            </span></a></li>

                        @can('read voitures')
                            <li class="{{ Request::is('voitures') ? 'active' : '' }}"><a href="{{ url('voitures') }}"><i
                                data-feather="truck"></i><span>Voitures
                            </span></a></li>
                        @endcan
                        @can('read vignettes')
                            <li class="{{ Request::is('vignettes', 'vignettes/create') ? 'active' : '' }}"><a
                                href="{{ url('vignettes') }}"><i data-feather="file"></i><span>Vignettes
                                </span></a></li>
                        @endcan
                        @can('read assurances')
                        <li class="{{ Request::is('assurances', 'assurances/create') ? 'active' : '' }}"><a
                            href="{{ url('assurances') }}"><i data-feather="file-text"></i><span>Assurance
                            </span></a></li>
                        @endcan
                        @can('read maintenances')
                        <li class="{{ Request::is('maintenances', 'maintenances/create') ? 'active' : '' }}"><a
                            href="{{ url('maintenances') }}"><i data-feather="tool"></i><span>Maintenances
                            </span></a></li>
                        @endcan

                    </ul>
                </li>
                @endcan

                @can('read Equipements')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Gestion Equipement</h6>
                    <ul>
                        <li class="{{ Request::is('equipements*') ? 'active' : '' }}"><a href="{{ url('equipements') }}"><i
                            data-feather="package"></i><span>Equipement</span></a></li>

                        <li class="{{ Request::is('mouvements*') ? 'active' : '' }}"><a
                            href="{{ url('mouvements') }}"><i data-feather="repeat"></i><span>Mouvement

                            </span></a></li>

                    </ul>
                </li>
                @endcan
                @can('read Ventes Oeufs')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Gestion des Ventes</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                            class="{{ Request::is('vente-oeufs*', 'vente-sujets*','vente-autres*') ? 'active subdrop' : '' }}">
                            <i data-feather="shopping-cart"></i><span>Ventes</span><span
                                class="menu-arrow"></span></a>
                                <ul>
                                    @can('read Ventes Oeufs')
                                    <li class="{{ Request::is('vente-oeufs*') ? 'active' : '' }}">
                                        <a href="{{ url('vente-oeufs') }}">Ventes Oeufs</a>
                                    </li>
                                    @endcan
                                    @can('read Ventes Sujet')
                                    <li class="{{ Request::is('vente-sujets*') ? 'active' : '' }}">
                                        <a href="{{ url('vente-sujets') }}">Ventes Sujet</a>
                                    </li>
                                    @endcan
                                    @can('read Autres Ventes')
                                    <li class="{{ Request::is('vente-autres*') ? 'active' : '' }}">
                                        <a href="{{ url('vente-autres') }}">Autres Ventes</a>
                                    </li>
                                    @endcan
                                </ul>

                        </li>
                        @can('read Retour Ventes')
                        <li class="{{ Request::is('operation-retours*') ? 'active' : '' }}">
                            <a href="{{ url('operation-retours') }}"><i data-feather="refresh-cw"></i><span>Retour Ventes</span></a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('read commandes')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Gestion Commande</h6>
                    <ul>

                        <li class="{{ Request::is('commandes', 'commande/{id}/details') ? 'active' : '' }}"><a
                            href="{{ url('commandes') }}"><i
                                data-feather="file-text"></i><span>Commandes</span></a></li>

                        @can('create commandes')
                        <li class="{{ Request::is('Commande') ? 'active' : '' }}"><a href="{{ url('Commande') }}"><i
                            data-feather="plus-square"></i><span>Enregistrer une commande
                        </span></a></li>
                        @endcan
                        @can('read retour_ventes_commande')
                        <li class="{{ Request::is('RetourVente', 'RetourV', 'Remboursement') ? 'active' : '' }}"><a
                            href="{{ url('RetourVente') }}"><i data-feather="copy"></i><span>Retour ventes
                            </span></a></li>
                        @endcan

                    </ul>
                </li>
                @endcan
                @can('read Dépenses')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Gestion Dépenses</h6>
                    <ul>

                        <li class="{{ Request::is('depenses') ? 'active' : '' }}"><a href="{{ url('depenses') }}"><i
                            data-feather="file-text"></i><span>Depenses</span></a></li>

                        @can('create Dépenses')
                        <li class="{{ Request::is('depenses/create') ? 'active' : '' }}"><a
                            href="{{ url('depenses/create') }}"><i data-feather="plus-square"></i><span>Enregistrer
                                une depenses
                            </span></a></li>
                        @endcan
                        @can('delete Dépenses')
                        <li class="{{ Request::is('typesDepense', 'typesDepense/create') ? 'active' : '' }}"><a
                            href="{{ url('typesDepense') }}"><i data-feather="file-minus"></i><span>Type de
                                Depenses</span></a></li>
                        @endcan


                    </ul>
                </li>
                @endcan
                @can('read Comptes')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Gestion Comptes</h6>
                    <ul>

                            <li
                                class="{{ Request::is('listcompte', 'compte', 'comptes/{id}/historique*') ? 'active' : '' }}">
                                <a href="{{ url('listcompte') }}"><i
                                        data-feather="credit-card"></i><span>Comptes</span></a>
                            </li>
                            <li class="{{ Request::is('transfert') ? 'active' : '' }}"><a href="{{ url('transfert') }}"><i
                                data-feather="repeat"></i><span>Transfert Fond</span></a></li>


                    </ul>
                </li>
                @endcan

                @can('read Situation Financier')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Situation financier</h6>
                    <ul>

                        <li class="{{ Request::is('BestA') ? 'active' : '' }}">
                            <a href="{{ url('BestA') }}"><i data-feather="package"></i><span>Meilleur
                                    Article</span></a>
                        </li>


                        <li class="{{ Request::is('BestC') ? 'active' : '' }}"><a href="{{ url('BestC') }}"><i
                                    data-feather="user-check"></i><span>Meilleur Client</span></a></li>

                        <li class="{{ Request::is('DepenseRecette') ? 'active' : '' }}"><a
                                href="{{ url('DepenseRecette') }}"><i data-feather="hard-drive"></i><span>Recette et
                                    Dépense</span></a></li>
                    </ul>
                </li>
                @endcan
                @can('read vaches')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Ferme bovine</h6>
                    <ul>
                        <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                            <a href="{{ url('dashboard') }}"><i data-feather="grid"></i><span>Tableau de
                                    bord</span></a>
                        </li>
                        @can('read ventes_bovins')
                        <li class="{{ Request::is('ventes_bovins', 'ventes_bovins/create') ? 'active' : '' }}">
                            <a href="{{ url('ventes_bovins') }}"><i data-feather="shopping-cart"></i><span>Ventes</span></a>
                        </li>
                        @endcan
                        @can('read vaches')
                        <li class="{{ Request::is('vaches', 'vaches/create') ? 'active' : '' }}">
                            <a href="{{ url('vaches') }}"><i data-feather="gitlab"></i><span>Vaches</span></a>
                        </li>
                        @endcan
                        @can('read vaches_laitiers')
                        <li class="{{ Request::is('laitieres','vacheOperation*','vacheStat*') ? 'active' : '' }}"><a
                            href="{{ url('laitieres') }}"><i data-feather="droplet"></i><span>Vaches laitières
                                </span></a></li>
                        @endcan
                        @can('read production_lait')
                        <li class="{{ Request::is('productionsL') ? 'active' : '' }}"><a
                            href="{{ url('productionsL') }}"><i data-feather="trending-up"></i><span>Production laitières
                                </span></a></li>
                        @endcan
                        @can('read bovins')
                        <li class="{{ Request::is('bovins*','OperationBovins*','StatBovins*') ? 'active' : '' }}"><a
                            href="{{ url('bovins') }}"><i data-feather="gitlab"></i><span>bovins
                                </span></a></li>
                        @endcan
                        @can('read alimentation_betail')
                        <li class="{{ Request::is('betail*') ? 'active' : '' }}"><a
                            href="{{ url('betail') }}"><i data-feather="feather"></i><span>Alimentation
                                </span></a></li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('read Poulailler')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Gestion poulailler</h6>
                    <ul>
                        @can('read Poulailler')
                        <li class="{{ Request::is('listpoulailler') ? 'active' : '' }}">
                            <a href="{{ url('listpoulailler') }}"><i
                                    data-feather="box"></i><span>Poulailler</span></a>
                        </li>
                        @endcan
                        @can('create Poulailler')
                        <li class="{{ Request::is('poulailler') ? 'active' : '' }}"><a
                            href="{{ url('poulailler') }}"><i data-feather="plus-square"></i><span>Ajouter un
                                poulailler</span></a></li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('read Cycle Production')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Ferme avicole</h6>
                    <ul>

                        <li class="{{ Request::is('listbande', 'bandeOperation', 'bandeStat') ? 'active' : '' }}">
                            <a href="{{ url('listbande') }}"><i data-feather="layers"></i><span>Liste
                                    Bande</span></a>
                        </li>

                        @can('create Cycle Production')
                        <li class="{{ Request::is('bande*') ? 'active' : '' }}"><a href="{{ url('bande') }}"><i
                            data-feather="plus-square"></i><span>Enregistrer une bande</span></a></li>
                        @endcan

                    </ul>
                </li>
                @endcan

                @can('read Tri des Oeufs')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Tri des Oeufs</h6>
                    <ul>

                        <li class="{{ Request::is('categorieOeufs*') ? 'active' : '' }}">
                            <a href="{{ url('categorieOeufs') }}"><i data-feather="codepen"></i><span>Categorie d'oeufs
                                    </span></a>
                        </li>
                        <li class="{{ Request::is('classifications*') ? 'active' : '' }}"><a href="{{ url('classifications') }}"><i
                            data-feather="layers"></i><span>Classification Oeufs</span></a></li>

                        <li class="{{ Request::is('operationCategorisations*') ? 'active' : '' }}"><a href="{{ url('operationCategorisations') }}"><i
                                    data-feather="droplet"></i><span>Categorisation Oeufs</span></a></li>

                    </ul>
                </li>
                @endcan
                @can('read abbatoire')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Gestion Abattoire</h6>
                    <ul>
                        <li class="{{ Request::is('abbatoires*') ? 'active' : '' }}">
                            <a href="{{ url('abbatoires') }}"><i data-feather="briefcase"></i><span>
                                    Abattoire</span></a>
                        </li>


                        <li class="{{ Request::is('sujetsAbbatus*') ? 'active' : '' }}"><a href="{{ url('sujetsAbbatus') }}"><i
                                    data-feather="scissors"></i><span>Abattage</span></a></li>

                    </ul>
                </li>
                @endcan
                @can('read Clients')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Fournisseurs et Client</h6>
                    <ul>
                        @can('read Fournisseurs')
                        <li class="{{ Request::is('listfournisseur', 'fournisseur') ? 'active' : '' }}"><a
                            href="{{ url('listfournisseur') }}"><i
                                data-feather="users"></i><span>Fournisseur</span></a>
                        </li>
                        <li class="{{ Request::is('listRemboursement', 'showRemboursementF') ? 'active' : '' }}"><a
                            href="{{ url('listRemboursement') }}"><i
                                data-feather="refresh-cw"></i><span>Redevance
                                Fournisseur</span></a></li>
                        @endcan
                        @can('read Clients')
                        <li class="{{ Request::is('listclient', 'client') ? 'active' : '' }}"><a
                            href="{{ url('listclient') }}"><i data-feather="user"></i><span>Client</span></a>
                        </li>
                        <li class="{{ Request::is('dettes', 'showRemboursementC') ? 'active' : '' }}"><a
                            href="{{ url('dettes') }}"><i data-feather="copy"></i><span>Dette Client
                            </span></a></li>
                        <li class="{{ Request::is('depot', 'showdepotform') ? 'active' : '' }}"><a
                            href="{{ url('depot') }}"><i data-feather="clipboard"></i><span>Depôt
                                Client</span></a></li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('read Ressources Humaines')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">RH</h6>
                    <ul>
                        <li class="{{ Request::is('listuser') ? 'active' : '' }}">
                            <a href="{{ url('listuser') }}"><i data-feather="user"></i><span>Employés</span></a>
                        </li>

                        <li class="{{ Request::is('adduser') ? 'active' : '' }}"><a href="{{ url('adduser') }}"><i
                                    data-feather="plus-square"></i><span>Ajouter un employé
                                </span></a></li>

                        <li class="{{ Request::is('AllRoles', 'Roles&P') ? 'active' : '' }}"><a
                                href="{{ url('AllRoles') }}"><i data-feather="shield"></i><span>Roles &
                                    Permissions</span></a></li>
                    </ul>
                </li>
                @endcan
                @can('read Rapport Général')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Rapport</h6>
                    <ul>
                        <li class="{{ Request::is('getProductionReports') ? 'active' : '' }}"><a
                                href="{{ url('getProductionReports') }}"><i
                                    data-feather="pie-chart"></i><span>Rapport de Production
                                </span></a></li>

                        <li class="{{ Request::is('generateRapportFinancier') ? 'active' : '' }}"><a
                                href="{{ url('generateRapportFinancier') }}"><i
                                    data-feather="inbox"></i><span>Rapport Financier
                                </span></a></li>
                        <li class="{{ Request::is('getStatferme') ? 'active' : '' }}"><a
                                href="{{ url('getStatferme') }}"><i data-feather="bar-chart-2"></i><span>Statistique
                                    ferme
                                </span></a></li>

                        <li class="{{ Request::is('DepenseRecette') ? 'active' : '' }}"><a
                                href="{{ url('DepenseRecette') }}"><i data-feather="pie-chart"></i><span>Profit &
                                    Perte</span></a></li>
                    </ul>
                </li>
                @endcan
                @can('read Paramétrages')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Parametres</h6>
                    <ul>
                        <li class="{{ Request::is('settings/create*') ? 'active' : '' }}"><a href="{{ url('settings/create') }}"><i
                                    data-feather="settings"></i><span>Parametrès généraux</span></a>
                        </li>
                        <li class="{{ Request::is('souches*') ? 'active' : '' }}"><a
                            href="{{ url('souches') }}"><i data-feather="twitter"></i><span>Souches
                                </span></a></li>
                        <li class="{{ Request::is('races*') ? 'active' : '' }}"><a
                                href="{{ url('races') }}"><i data-feather="gitlab"></i><span>Races vaches
                                    </span></a></li>
                        <li class="{{ Request::is('fermes*') ? 'active' : '' }}"><a
                                href="{{ url('fermes') }}"><i data-feather="briefcase"></i><span>Ferme
                                    </span></a></li>
                        <li class="{{ Request::is('logout') ? 'active' : '' }}">
                            <a href="{{ url('logout') }}"><i data-feather="log-out"></i><span>Déconnexion</span> </a>
                        </li>
                    </ul>
                </li>
                @endcan

            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
