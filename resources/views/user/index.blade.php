@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-users text-orange">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Utilisateurs</span>
                            </div>
                            <div class="page-title-subheading">Cette section est réservée à la gestion des Utilisateurs</div>
                        </div>
                    </div>

                    @include("user.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">Utilisateurs</span>
                        <div class="text-transform-initial mr-sm-3">
                            Total: <span class="badge badge-primary badge-pill">{{ count($users) }}</span>
                        </div>
                    </div>
                    <div class="btn-actions-pane-right d-flex align-items-center ">
                        <input type="search" oninput="filterTable(this, 'datatable')" class="form-control form-control-sm" placeholder="Chercher dans la liste">
                    </div>
                </div>
                <div class="card-body p-0">
                    <table id="datatable" class="table table-hover table-striped table-bordered" style="margin-bottom: 0 !important; margin-top: 0 !important;">
                        <thead class="bg-heavy-rain">
                        <tr>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Créé le</th>
                            <th>Statut</th>
                            <th class="text-center">Compte ID</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>{{$user->telephone}}</td>
                                <td>{{$user->email}}</td>
                                <td><span hidden>{{ $user->created_at }}</span>{{ date("d/m/Y", strtotime($user->created_at)) }}</td>
                                <td>
                                    @if($user->activated)
                                        <span class="badge badge-success badge-pill">Activé</span>
                                    @else
                                        <span class="badge badge-warning badge-pill">Attente<span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $user->compte->id }}</td>
                                <td>
                                    <button form-action="{{ route("user.password.reset", $user) }}"
                                       form-method="put"
                                       confirm-message="Réinitialiser mot de passe ?"
                                       onclick="submitLinkForm(this)"
                                       class="confirm btn btn-link btn-sm">
                                        <i class="fa fa-key"></i> Réinitialiser mot de passe
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
