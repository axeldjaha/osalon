@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-tachometer-alt text-orange">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Salon</span>
                            </div>
                            <div class="page-title-subheading">Cette section est réservée à la gestion des salons</div>
                        </div>
                    </div>

                    @include("salon.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain" style="height: inherit">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">Détails</span>
                    </div>
                </div>
                <div class="card-body" style="background: #fafafa">
                    <div class="row">
                        <strong class="col-lg-3">Pid</strong>
                        <label class="col-auto">{{$salon->pid}}</label>
                    </div>
                    <div class="row">
                        <strong class="col-lg-3">Nom salon</strong>
                        <label class="col-auto">{{$salon->nom}}</label>
                    </div>
                    <div class="row">
                        <strong class="col-lg-3">Adresse</strong>
                        <label class="col-auto">{{$salon->adresse}}</label>
                    </div>
                    <div class="row">
                        <strong class="col-lg-3">Date création</strong>
                        <label class="col-auto">{{date("d/m/Y", strtotime($salon->created_at))}}</label>
                    </div>

                    <div class="dropdown-divider"></div>
                    <div class="mt-3 clearfix">
                        <a form-action="{{route("salon.destroy", $salon)}}"
                           form-method="delete"
                           confirm-message="Supprimer le salon ?"
                           onclick="submitLinkForm(this)"
                           href="#"
                           class="confirm btn btn-link text-danger">Supprimer le salon ?
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
