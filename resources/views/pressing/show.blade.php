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
                    <div class="row">
                        <strong class="col-lg-3">Pid</strong>
                        <label class="col-auto">{{$pressing->abonnement->pid}}</label>
                    </div>
                    <div class="row">
                        <strong class="col-lg-3">Nom pressing</strong>
                        <label class="col-auto">{{$pressing->nom}}</label>
                    </div>
                    <div class="row">
                        <strong class="col-lg-3">Adresse</strong>
                        <label class="col-auto">{{$pressing->adresse}}</label>
                    </div>
                    <div class="row">
                        <strong class="col-lg-3">Statut</strong>
                        <label class="col-auto">
                            @if($pressing->activated == true)<div class="badge badge-pill badge-success">Activé</div>
                            @else<div class="badge badge-pill badge-danger">Désactivé</div>
                            @endif
                        </label>
                    </div>
                    <div class="row">
                        <strong class="col-lg-3">Date création</strong>
                        <label class="col-auto">{{date("d/m/Y", strtotime($pressing->created_at))}}</label>
                    </div>

                    <div class="dropdown-divider"></div>
                    <div class="mt-3 clearfix">
                        <a form-action="{{route("pressing.destroy", $pressing)}}"
                           form-method="delete"
                           confirm-message="Supprimer le pressing ?"
                           onclick="submitLinkForm(this)"
                           href="#"
                           class="confirm btn btn-link text-danger">Supprimer le pressing ?
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
