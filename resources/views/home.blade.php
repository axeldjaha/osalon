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
                                <span class="d-inline-block">Tableau de bord</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include("layouts.alert")

            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="card mb-3 widget-chart text-left">
                        <div class="icon-wrapper rounded-circle">
                            <div class="icon-wrapper-bg bg-success"></div>
                            <i class="lnr-apartment text-success"></i></div>
                        <div class="widget-chart-content">
                            <div class="widget-subheading">Pressings</div>
                            <div class="widget-numbers">{{number_format($pressings, 0, ",", " ")}}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card mb-3 widget-chart text-left">
                        <div class="icon-wrapper rounded-circle">
                            <div class="icon-wrapper-bg bg-success"></div>
                            <i class="lnr-apartment text-success"></i></div>
                        <div class="widget-chart-content">
                            <div class="widget-subheading">Utilisateurs</div>
                            <div class="widget-numbers">{{number_format($users, 0, ",", " ")}}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="card mb-3 bg-success widget-chart text-white card-border">
                        <div class="icon-wrapper rounded-circle">
                            <div class="icon-wrapper-bg bg-white opacity-10"></div>
                            <i class="lnr-database text-success"></i></div>
                        <div class="widget-numbers">{{number_format($montantTransactions, 0, ",", " ")}}</div>
                        <div class="widget-subheading">Transactions (FCFA)</div>
                        <div class="widget-description text-white">
                            <span class="pr-1">{{ucfirst($moisCourant)}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card mb-3 bg-warning widget-chart text-white card-border">
                        <div class="icon-wrapper rounded-circle">
                            <div class="icon-wrapper-bg bg-white opacity-10"></div>
                            <i class="lnr-database text-success"></i></div>
                        <div class="widget-numbers">{{number_format($recette, 0, ",", " ")}}</div>
                        <div class="widget-subheading">Recette pressings</div>
                        <div class="widget-description text-white">
                            <span class="pr-1">{{ucfirst($moisCourant)}}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card mb-3 widget-chart text-left">
                        <div class="icon-wrapper rounded-circle">
                            <div class="icon-wrapper-bg opacity-10 bg-danger"></div>
                            <i class="fa fa-calendar-alt text-white"></i></div>
                        <div class="widget-chart-content">
                            <div class="widget-subheading">Abonnements expirés</div>
                            <div class="widget-numbers text-danger">{{number_format($abonnementsExp, 0, ",", " ")}}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card mb-3 widget-chart">
                        <div class="widget-numbers text-dark">{{number_format($sms, 0, ",", " ")}}</div>
                        <div class="widget-subheading">SMS envoyé</div>
                        <div class="widget-description text-info">
                            <span class="pl-1">{{ucfirst($moisCourant)}}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection
