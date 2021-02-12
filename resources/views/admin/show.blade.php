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
                                <span class="d-inline-block">Opérateurs</span>
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
                                            <a href="{{route("operateur.index")}}" class="">Liste</a>
                                        </li>
                                        <li class="breadcrumb-item active">
                                            #{{$operateur->id}}
                                            <strong class="">{{$operateur->name}}</strong>
                                        </li>
                                    </ol>
                                </h6>
                            </div>
                        </div>
                    </div>

                    @include("operateur.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="d-flex justify-content-between">
                                <strong>Nom</strong>
                                <label>{{$operateur->name}}</label>
                            </div>
                            <div class="d-flex justify-content-between">
                                <strong>Pressing</strong>
                                <label>{{$operateur->pressing->nom}}</label>
                            </div>
                            <div class="d-flex justify-content-between">
                                <strong>Adresse pressing</strong>
                                <label>{{$operateur->pressing->adresse}}</label>
                            </div>
                            <div class="d-flex justify-content-between">
                                <strong>Statut</strong>
                                <label>
                                    @if($operateur->statut == \App\Operateur::$STATUT_ACTIVE)<div class="badge badge-pill badge-success">Activé</div>
                                    @else<div class="badge badge-pill badge-danger">Désactivé</div>
                                    @endif
                                </label>
                            </div>
                            <div class="d-flex justify-content-between">
                                <strong>Date création</strong>
                                <label>{{date("d/m/Y", strtotime($operateur->created_at))}}</label>
                            </div>
                        </div>
                        <div class="col-lg-6 offset-2">
                            <div class="d-flex justify-content-between">
                                <div class="mr-sm-5">
                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                        <i class="fa fa-at fa-w-20"></i>
                                    </span>
                                    <strong>Email</strong>
                                </div>
                                <label>{{$operateur->email}}</label>
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
                        @if($operateur->statut == \App\Operateur::$STATUT_DESACTIVE)
                            <a form-action="{{route("operateur.activer", $operateur)}}"
                               form-method="put"
                               confirm-message="Activer compte opérateur ?"
                               onclick="submitLinkForm(this)"
                               href="#"
                               class="confirm btn btn-outline-warning btn-sm">Activer le compte
                            </a>
                        @else
                            <a form-action="{{route("operateur.desactiver", $operateur)}}"
                               form-method="put"
                               confirm-message="Désactivé compte opérateur ?"
                               onclick="submitLinkForm(this)"
                               href="#"
                               class="confirm btn btn-outline-warning btn-sm">Désactiver le compte
                            </a>
                        @endif
                        <a form-action="{{route("operateur.destroy", $operateur)}}"
                           form-method="delete"
                           confirm-message="Supprimer l'opérateur ?"
                           onclick="submitLinkForm(this)"
                           href="#"
                           class="confirm btn btn-link text-danger float-right">Supprimer l'opérateur
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
