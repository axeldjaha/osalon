@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-coins">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Offre</span>
                            </div>
                            <div class="page-title-subheading">Cette section est réservée à l'offre</div>
                        </div>
                    </div>

                </div>
            </div>

            @include("layouts.alert")

            <div class="mb-3 card">
                <div class="no-gutters d-flex justify-content-between align-items-center pr-4">
                    <div class="">
                        <div class="card no-shadow rm-border bg-transparent widget-chart text-left">
                            <div class="icon-wrapper rounded-circle">
                                <div class="icon-wrapper-bg opacity-9 bg-success"></div>
                                <i class="fa fa-dollar-sign text-white"></i>
                            </div>
                            <div class="widget-chart-content">
                                <div class="widget-subheading">Montant</div>
                                <div class="widget-numbers text-success">
                                    <span>{{number_format($offre->montant, 0, ".", " ")}}</span>
                                </div>
                                <div class="widget-description text-focus">
                                    FCFA / mois
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="{{route("offre.edit", $offre)}}" class="btn-pill btn-shadow btn-wide fsize-1 btn btn-primary btn-lg">
                        <span class="mr-2 opacity-7">
                            <i class="fa fa-edit"></i>
                        </span>
                            <span class="mr-1">Editer</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
