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
                    <div class="card-header-title d-flex justify-content-start">
                        <ul class="nav">
                            <li class="nav-item">
                                <a href="{{route("service.index", $pressing)}}" class="nav-link active">
                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                        <i class="fa fa-list fa-w-20"></i>
                                    </span>
                                    <span>Liste</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route("service.create", $pressing)}}" class="nav-link active">
                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                        <i class="fa fa-plus fa-w-20"></i>
                                    </span>
                                    <span>Créer service</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="divider"></div>
                    {!! Form::open()->route("service.store", [$pressing]) !!}
                    <div class="row">
                        <div class="col-lg-10 mx-auto">
                            {!! Form::text("nom", "Nom du service")->placeholder("Exemple: Laver à sec") !!}

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>

@endsection
