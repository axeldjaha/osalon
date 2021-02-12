@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-credit-card text-orange">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Abonnement</span>
                            </div>
                            <div class="page-title-subheading opacity-10">
                                <h6 class="" aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a>
                                                <i aria-hidden="true" class="fa fa-home"></i>
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="{{route("abonnement.index")}}" class="">Liste</a>
                                        </li>
                                    </ol>
                                </h6>
                            </div>
                        </div>
                    </div>

                    @include("abonnement.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-credit-card fa-w-20"></i>
                        </span>
                        Réabonnement
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open()->route("abonnement.reabonner", [$pressing]) !!}
                    <div class="border border-info p-2 b-radius-0">
                        <div class="row">
                            <strong class="col-sm-1 col-form-label">ID</strong>
                            <div class="col-sm-10">
                                <span class="form-control-plaintext">1234</span>
                            </div>
                        </div>
                        <div class="row">
                            <strong class="col-sm-1 col-form-label">Nom</strong>
                            <div class="col-sm-10">
                                <span class="form-control-plaintext">{{$pressing->nom}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-row mt-4">

                        <div class="col-2">
                            {!! Form::text("montant", "Montant")->type("number")->min(0) !!}
                        </div>
                        <div class="col-2">
                            {!! Form::text("validite", "Validité")->type("number")->min(0) !!}
                        </div>
                        <div class="col-2">
                            {!! Form::select("mode_paiement", "Mode de paiement")->options($modes->prepend('------', '')) !!}
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Réabonner</button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
