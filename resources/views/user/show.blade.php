@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="lnr-user text-orange">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Utilisateurs</span>
                            </div>
                            <div class="page-title-subheading opacity-10">
                                <h6 class="" aria-label="breadcrumb">
                                    <ol class="breadcrumb align-items-center">
                                        <li class="breadcrumb-item">
                                            <a>
                                                <i aria-hidden="true" class="fa fa-home"></i>
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="{{route("user.index")}}" class="">Liste</a>
                                        </li>
                                        <li class="breadcrumb-item active">
                                            #{{$user->id}}
                                            <strong class="">{{$user->name}}</strong>
                                        </li>
                                    </ol>
                                </h6>
                            </div>
                        </div>
                    </div>

                    @include("user.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="d-flex justify-content-between">
                                <strong>Nom</strong>
                                <label>{{$user->name}}</label>
                            </div>
                            <div class="d-flex justify-content-between">
                                <strong>Statut</strong>
                                <label>
                                    @if($user->activated == true)<div class="badge badge-pill badge-success">Activé</div>
                                    @else<div class="badge badge-pill badge-danger">Désactivé</div>
                                    @endif
                                </label>
                            </div>
                            <div class="d-flex justify-content-between">
                                <strong>Date création</strong>
                                <label>{{date("d/m/Y", strtotime($user->created_at))}}</label>
                            </div>
                        </div>
                        <div class="col-lg-6 offset-2">
                            <div class="d-flex justify-content-between">
                                <div class="mr-sm-5">
                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                        <i class="fa fa-phone fa-w-20"></i>
                                    </span>
                                    <strong>Téléphone</strong>
                                </div>
                                <label>{{$user->telephone}}</label>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="mr-sm-5">
                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                        <i class="fa fa-at fa-w-20"></i>
                                    </span>
                                    <strong>Email</strong>
                                </div>
                                <label>{{$user->email}}</label>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                        <i class="fa fa-key fa-w-20"></i>
                                    </span>
                                    <strong>Mot de passe</strong>
                                </div>
                                <label>********</label>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown-divider"></div>
                    <div class="mt-3 clearfix">
                        @if($user->activated == false)
                            <a form-action="{{route("user.unlock", $user)}}"
                               form-method="put"
                               confirm-message="Activer l'utilisateur ?"
                               onclick="submitLinkForm(this)"
                               href="#"
                               class="confirm btn btn-outline-warning">Activer l'utilisateur
                            </a>
                        @else
                            <a form-action="{{route("user.lock", $user)}}"
                               form-method="put"
                               confirm-message="Désactiver l'utilisateur ?"
                               onclick="submitLinkForm(this)"
                               href="#"
                               class="confirm btn btn-outline-warning">Désactiver l'utilisateur
                            </a>
                        @endif
                        <a form-action="{{route("user.destroy", $user)}}"
                           form-method="delete"
                           confirm-message="Supprimer l'utilisateur ?"
                           onclick="submitLinkForm(this)"
                           href="#"
                           class="confirm btn btn-link text-danger float-right">Supprimer l'utilisateur
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
