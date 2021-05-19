@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-user-friends">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Administrateurs</span>
                            </div>
                            <div class="page-title-subheading">Cette section est réservée à la gestion des comptes admins</div>
                        </div>
                    </div>

                    @include("admin.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">Administrateurs</span>

                        <div class="text-transform-initial mr-sm-3">
                            Total <span class="badge badge-primary badge-pill">{{ count($users) }}</span>
                        </div>
                    </div>
                    <div class="btn-actions-pane-right d-flex align-items-center ">
                        <input type="search" oninput="filterTable(this, 'datatable')" class="form-control form-control-sm" placeholder="Chercher dans la liste">
                    </div>
                </div>
                <div class="card-body p-0 table-responsive-sm">
                    <table id="datatable" class="table table-hover table-striped" style="margin-bottom: 0 !important; margin-top: 0 !important;">
                        <thead class="bg-heavy-rain">
                        <tr>
                            <th>Admin</th>
                            <th>Créé le</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left mr-3">
                                                <img width="50" height="50" style="object-fit: cover;" class="rounded-circle"
                                                     src="{{$user->photo != null ? asset('storage/admins/'.$user->photo) : asset("images/profile.png") }}" alt="">
                                            </div>
                                            <div class="widget-content-left">
                                                <div class="widget-heading">{{$user->name}}</div>
                                                <div class="widget-subheading">{{$user->email}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><span hidden>{{ $user->created_at }}</span> {{ date("d/m/Y", strtotime($user->created_at)) }}</td>
                                <td>
                                    <button form-action="{{route("admin.destroy", $user)}}"
                                            form-method="delete"
                                            confirm-message="Supprimer l'administrateur ?"
                                            onclick="submitLinkForm(this)"
                                            class="confirm btn btn-link text-danger btn-sm">Supprimer
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
