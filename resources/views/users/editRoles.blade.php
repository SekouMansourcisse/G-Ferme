@include('partials._head')
<style>
    .pagination-wrapper button {
        margin: 2px;
        padding: 5px 10px;
        cursor: pointer;
    }

    .pagination-wrapper .active {
        font-weight: bold;
        background-color: #ff9f43;
        color: white;
    }
</style>
<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">
        @include('partials._topbar')
        @include('partials._sidebar_collapsed')
        @include('partials._sidebar')
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Edition de role</h4>
                        <h6>Formulaire d'édition de role</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        <form action="{{ route('roles.update', $role->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Nom du rôle -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom du rôle</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $role->name }}" required>
                            </div>

                            <!-- Table des permissions -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>MODULE</th>
                                        <th>Créer</th>
                                        <th>Lire</th>
                                        <th>Supprimer</th>
                                        <th>Modifier</th>
                                        <th>Tous</th>
                                    </tr>
                                </thead>
                                <tbody id="RolesTable">
                                    @php $modulesDisplayed = []; @endphp
                                    @foreach ($permissions as $permission)
                                        @php
                                            $parts = explode(' ', $permission->name);
                                            $action = $parts[0];
                                            $module = implode(' ', array_slice($parts, 1));
                                        @endphp

                                        @if (!in_array($module, $modulesDisplayed))
                                            @php $modulesDisplayed[] = $module; @endphp
                                            <tr>
                                                <td>{{ ucfirst($module) }}</td>

                                                <td>
                                                    <input type="checkbox" name="permissions[]"
                                                           value="{{ $permissions->firstWhere('name', 'create ' . $module)->id }}"
                                                           {{ in_array($permissions->firstWhere('name', 'create ' . $module)->id, $rolePermissions) ? 'checked' : '' }}>
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="permissions[]"
                                                           value="{{ $permissions->firstWhere('name', 'read ' . $module)->id }}"
                                                           {{ in_array($permissions->firstWhere('name', 'read ' . $module)->id, $rolePermissions) ? 'checked' : '' }}>
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="permissions[]"
                                                           value="{{ $permissions->firstWhere('name', 'delete ' . $module)->id }}"
                                                           {{ in_array($permissions->firstWhere('name', 'delete ' . $module)->id, $rolePermissions) ? 'checked' : '' }}>
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="permissions[]"
                                                           value="{{ $permissions->firstWhere('name', 'edit ' . $module)->id }}"
                                                           {{ in_array($permissions->firstWhere('name', 'edit ' . $module)->id, $rolePermissions) ? 'checked' : '' }}>
                                                </td>
                                                <td>
                                                    <input type="checkbox" class="select-all">
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <div id="paginationControls" class="pagination-wrapper"></div>
                            <!-- Bouton de soumission -->
                            <button type="submit" class="btn btn-primary">Mettre à jour le rôle</button>
                        </form>
                    </div>
                </div>



            </div>
        </div>
    </div>

    @include('partials.script')

    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script>
        document.getElementById('check-all').addEventListener('click', function(e) {
            e.preventDefault();
            let checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });
        });

        document.getElementById('uncheck-all').addEventListener('click', function(e) {
            e.preventDefault();
            let checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
        });

        // Sélectionner toutes les permissions dans une ligne spécifique
        document.querySelectorAll('.select-all').forEach(function(el) {
            el.addEventListener('click', function() {
                let row = el.closest('tr');
                let checkboxes = row.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = el.checked;
                });
            });
        });
    </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
        let rowsPerPage = 8; // Nombre de lignes par page
        let tableBody = document.getElementById('RolesTable');
        let rows = tableBody.getElementsByTagName('tr');
        let paginationControls = document.getElementById('paginationControls');
        let totalRows = rows.length;
        let totalPages = Math.ceil(totalRows / rowsPerPage);
        let currentPage = 1;

        // Fonction pour afficher une page
        function displayPage(page) {
            let start = (page - 1) * rowsPerPage;
            let end = start + rowsPerPage;

            // Masquer toutes les lignes
            for (let i = 0; i < totalRows; i++) {
                rows[i].style.display = 'none';
            }

            // Afficher les lignes pour la page en cours
            for (let i = start; i < end && i < totalRows; i++) {
                rows[i].style.display = '';
            }

            // Mettre à jour les boutons de pagination
            updatePaginationControls(page);
        }

        // Fonction pour créer les boutons de pagination
        function updatePaginationControls(page) {
            paginationControls.innerHTML = '';

            // Ajouter le bouton "Précédent"
            if (page > 1) {
                let prevButton = document.createElement('button');
                prevButton.innerText = 'Précédent';
                prevButton.addEventListener('click', function() {
                    displayPage(page - 1);
                });
                paginationControls.appendChild(prevButton);
            }

            // Ajouter les boutons pour chaque page
            for (let i = 1; i <= totalPages; i++) {
                let pageButton = document.createElement('button');
                pageButton.innerText = i;
                if (i === page) {
                    pageButton.classList.add('active');
                }
                pageButton.addEventListener('click', function() {
                    displayPage(i);
                });
                paginationControls.appendChild(pageButton);
            }

            // Ajouter le bouton "Suivant"
            if (page < totalPages) {
                let nextButton = document.createElement('button');
                nextButton.innerText = 'Suivant';
                nextButton.addEventListener('click', function() {
                    displayPage(page + 1);
                });
                paginationControls.appendChild(nextButton);
            }
        }

        // Afficher la première page au chargement
        displayPage(currentPage);
    });
    </script>
</body>
