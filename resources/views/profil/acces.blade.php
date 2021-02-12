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
                                <span class="d-inline-block">Mon compte</span>
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
                                            <a href="{{route("profil.infos")}}">Modifier mes informations</a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="{{route("profil.acces")}}">Modifier mes accès</a>
                                        </li>
                                    </ol>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-key fa-w-20"></i>
                        </span>
                        Modifier mes accès
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open()->route("profil.acces")->put()->multipart() !!}
                    <div class="form-row">
                        <div class="col-lg-6">
                            {!!Form::text('email', 'Email')->type('email')->value(old("email") ?? auth()->user()->email)!!}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-lg-6">
                            {!!Form::text('password', 'Mot de passe')->type('password')!!}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-lg-3">
                            {!!Form::text('nouveau_mot_de_passe', 'Nouveau mot de passe')->type('password')!!}
                        </div>
                        <div class="col-lg-3">
                            {!!Form::text('nouveau_mot_de_passe_confirmation', 'Confirmez')->type('password')!!}
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg mt-3">Enregistrer les modifications</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
