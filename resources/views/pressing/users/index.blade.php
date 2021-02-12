@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="lnr-apartment text-orange">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Pressings</span>
                            </div>
                            <div class="page-title-subheading opacity-10">
                                @include("pressing.subheading")
                            </div>
                        </div>
                    </div>

                    @include("pressing.actions")

                </div>
            </div>

            @include("layouts.alert")

            @include("pressing.menus")

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="card-header-title d-flex justify-content-start">
                        <ul class="nav">
                            <li class="nav-item">
                                <a href="{{route("pressing.users", $pressing)}}" class="nav-link active">
                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                        <i class="fa fa-list fa-w-20"></i>
                                    </span>
                                    <span>Liste</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route("pressing.createUser", $pressing)}}" class="nav-link active">
                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                        <i class="fa fa-user-plus fa-w-20"></i>
                                    </span>
                                    <span>Nouveau</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <table id="datatable" class="mb-0 table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <th class="fit" scope="row">{{$user->id}}</th>
                                <td>
                                    <div class="">
                                        {{$user->name}}
                                    </div>
                                </td>
                                <td>{{$user->telephone}}</td>
                                <td>{{$user->email}}</td>
                                <td>
                                    @if($user->activated == true)<div class="badge badge-pill badge-success">Activé</div>
                                    @else<div class="badge badge-pill badge-danger">Désactivé</div>
                                    @endif
                                </td>
                                <td class="">
                                    <div class="form-row">
                                        <div class="col-4">
                                            @if($user->activated == false)
                                                <a form-action="{{route("user.unlock", $user)}}"
                                                   form-method="put"
                                                   confirm-message="Activer l'utilisateur ?"
                                                   onclick="submitLinkForm(this)"
                                                   href="#"
                                                   class="confirm btn btn-link">
                                                    <i class="fa fa-lock"></i> Activer
                                                </a>
                                            @else
                                                <a form-action="{{route("user.lock", $user)}}"
                                                   form-method="put"
                                                   confirm-message="Désactiver l'utilisateur ?"
                                                   onclick="submitLinkForm(this)"
                                                   href="#"
                                                   class="confirm btn btn-link">
                                                    <i class="fa fa-lock"></i> Désactiver
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col-4">
                                            <a form-action="{{route("pressing.destroyeUser", [$pressing, $user])}}"
                                               form-method="delete"
                                               confirm-message="Supprimer l'utilisateur ?"
                                               onclick="submitLinkForm(this)"
                                               href="#"
                                               class="confirm btn btn-link">
                                                <i class="fa fa-trash-alt text-danger"></i> Supprimer
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection
