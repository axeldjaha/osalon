@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-file-excel text-orange">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Fichiers de prospection</span>
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
                                            <a href="{{route("fichier.index")}}" class="">Liste</a>
                                        </li>
                                    </ol>
                                </h6>
                            </div>
                        </div>
                    </div>

                    @include("prospect.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-file-excel fa-w-20"></i>
                        </span>
                        Nouveau fichier
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open()->route("fichier.store")->multipart() !!}
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="form-row">
                                <div class="col-lg-10">
                                    {!!Form::file('fichier', 'Fichier')->attrs(["accept" => ".xlsx,.xls"])!!}
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-lg-10">
                                    {!! Form::text("nom", "Nom") !!}
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg mt-3">Importer</button>
                        </div>
                        <div class="col-lg-4">
                            <p>Modèle du fichier</p>
                            <img class="img-fluid" src="{{asset("images/modele-fichier-prospect.jpg")}}" alt="modele fichier prospect">
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
