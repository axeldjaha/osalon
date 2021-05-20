@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-chart-pie"></i>
                        </div>
                        <div>
                            Tableau de bord
                            <div class="page-title-subheading">
                                Cette section présente le tableau de bord général
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include("layouts.alert")

            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="widget-chart widget-chart2 text-left mb-3 bg-primary text-white card-btm-border card-shadow-primary border-primary card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Comptes</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            <span class="opacity-10 pr-2">
                                                <i class="fa fa-th"></i>
                                            </span>
                                            {{ number_format($total_compte, 0, ",", " ") }}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-chart widget-chart2 text-left mb-3 bg-success text-white card-btm-border card-shadow-success border-success card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Comptes actifs</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            <span class="opacity-10 pr-2">
                                                <i class="fa fa-check"></i>
                                            </span>
                                            {{ number_format($total_compte_actif, 0, ",", " ") }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-chart widget-chart2 text-left mb-3 bg-danger text-white card-btm-border card-shadow-danger border-danger card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Comptes expirés</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            <span class="opacity-10 pr-2">
                                                <i class="fa fa-check"></i>
                                            </span>
                                            {{ number_format($total_compte - $total_compte_actif, 0, ",", " ") }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-chart widget-chart2 text-left mb-3 bg-secondary text-white card-btm-border card-shadow-warning border-warning card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Salons</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            <span class="opacity-10 pr-2">
                                                <i class="fa fa-tachometer-alt"></i>
                                            </span>
                                            {{ number_format($total_salon, 0, ",", " ") }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-chart widget-chart2 text-left bg-alternate text-white mb-3 card-btm-border card-shadow-alternate border-alternate card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Utilisateurs</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            <span class="opacity-10 pr-2">
                                                <i class="fa fa-users"></i>
                                            </span>
                                            {{ number_format($total_user, 0, ",", " ") }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-chart widget-chart2 text-left mb-3 bg-info text-white card-btm-border card-shadow-info border-info card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Crédit SMS</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            <span class="opacity-10 pr-2">
                                                <i class="fa fa-database"></i>
                                            </span>
                                            {{ number_format($total_sms_balance, 0, ",", " ") }}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
