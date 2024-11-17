@include('partials._head')
<style>
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }
    .table i,.table img {
    width: 24px;
    height: 24px;
    cursor: pointer;
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
                        <h4>Liste utilisateur</h4>
                        <h6>Gerer vos Utilisateur</h6>
                    </div>
                    @can('create Ressources Humaines')
                    <div class="page-btn">
                        <a href="{{url('adduser')}}" class="btn btn-added"><img src="assets/img/icons/plus.svg" alt="img">Ajouter
                            Utilisateur</a>
                    </div>
                    @endcan
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-path">
                                    <a class="btn btn-filter" id="filter_search">
                                        <img src="{{asset('assets/img/icons/filter.svg')}}" alt="img">
                                        <span><img src="{{asset('assets/img/icons/closes.sv')}}g" alt="img"></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img src="{{asset('assets/img/icons/search-white.svg')}}"
                                            alt="img"></a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                                src="{{ asset('assets/img/icons/pdf.svg')}}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                                src="{{ asset('assets/img/icons/excel.svg')}}" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                                src="{{ asset('assets/img/icons/printer.svg')}}" alt="img"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card" id="filter_inputs">
                            <div class="card-body pb-0">
                                <form action="#" class="dropdown">
                                    <div class="searchinputs" id="dropdownMenuClickable"
                                        data-bs-auto-close="false">
                                        <input type="text" placeholder="Search">
                                        <div class="search-addon">
                                            <span><i data-feather="x-circle" class="feather-14"></i></span>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>

                                        <th>Nom </th>
                                        <th>Prenom</th>
                                        <th>Telephone</th>
                                        <th>Adresse</th>
                                        <th>Email</th>
                                        <th>Profil</th>
                                        @can('edit Ressources Humaines')
                                        <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr data-id="{{ $user->id }}">
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->firstname }}</td>

                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->adresse }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->profil }}</td>
                                        @can('edit Ressources Humaines')
                                        <td>

                                            <a  class="me-3 edit-item-btn">
                                                <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="edit">
                                            </a>

                                            @can('delete Ressources Humaines')
                                            <a class="me-3 delete-item-btn" id="delete-item-btn" href="javascript:void(0);">
                                                <img src="{{ asset('assets/img/icons/delete.svg')}}" alt="img">
                                            </a>
                                            @endcan
                                        </td>
                                        @endcan
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
  <!-- Modal -->
  <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editUserModalLabel">Modifier l'utilisateur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Form for editing user -->
          <form method="POST" id="edit-user-form">
            @csrf
            <div class="row">
                <input type="hidden" class="form-control" name="edit-id" id="edit-id" >
              <div class="col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Prenom</label>
                  <input type="text" class="form-control" name="edit-firstname" id="edit-firstname" >
                </div>
              </div>
              <div class="col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Nom</label>
                  <input type="text" class="form-control" name="edit-nom" id="edit-nom">
                </div>
              </div>
              <div class="col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Email</label>
                  <input type="text" class="form-control" name="edit-email" id="edit-email">
                </div>
              </div>
              <div class="col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Telephone</label>
                  <input type="text" class="form-control" name="edit-phone" id="edit-phone">
                </div>
              </div>
              <div class="col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Adresse</label>
                  <input type="text" class="form-control" name="edit-adresse" id="edit-adresse" >
                </div>
              </div>
              <div class="col-lg-6 col-sm-12">
                <div class="form-group">
                    <label>Profil</label>
                    <select class="select" name="edit-profil" id="edit-profil">

                        <option value="simple utilisateur">Simple utilisateur</option>
                        <option value="admin">Admin</option>
                        <option value="super admin">Super admin</option>
                    </select>
                </div>
              </div>

              <div class="col-12">
                <button type="submit" class="btn btn-submit me-2">Enregistrer</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Annuler</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js')}}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js')}}"></script>
    <script src="{{ asset('js/backend/listuser.js')}}"></script>
</body>
