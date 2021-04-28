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
                            Total <span class="badge badge-primary badge-pill">{{ count($users) }}</span>
                        </div>
                    </div>
                    <div class="btn-actions-pane-right d-flex align-items-center ">
                        <input type="search" oninput="filterTable(this, 'datatable')" class="form-control form-control-sm" placeholder="Chercher dans la liste">
                    </div>
                </div>
                <div class="card-body p-0 table-responsive-sm">
                    <table id="datatable" class="table table-hover table-striped table-bordered" style="margin-bottom: 0 !important; margin-top: 0 !important;">
                        <thead class="bg-heavy-rain">
                        <tr>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Créé le</th>
                            <th>Activity</th>
                            <th>Compte</th>
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
                                <td><span hidden>{{ $user->last_activity_at ?? null }}</span>@if($user->last_activity_at != null) {{ date("Y-m-d à H:i", strtotime($user->last_activity_at)) }} @endif</td>
                                <td>
                                    <a href="{{ route("compte.show", $user->compte_id) }}" class="btn btn-link">Aller au compte</a>
                                </td>
                                <td>
                                    <button form-action="{{ route("user.password.reset", $user->id) }}"
                                       form-method="put"
                                       confirm-message="Réinitialiser mot de passe ?"
                                       onclick="submitLinkForm(this)"
                                       class="confirm btn btn-link btn-sm">
                                        <i class="fa fa-key mr-2"></i> Réinitialiser mot de passe
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
