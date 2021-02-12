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
                                <h6 class="" aria-label="breadcrumb">
                                    <ol class="breadcrumb align-items-center">
                                        <li class="breadcrumb-item">
                                            <a>
                                                <i aria-hidden="true" class="fa fa-home"></i>
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="{{route("pressing.index")}}" class="">Liste</a>
                                        </li>
                                    </ol>
                                </h6>
                            </div>
                        </div>
                    </div>

                    @include("pressing.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-plus fa-w-20"></i>
                        </span>
                        Nouveau pressing
                    </div>
                </div>
                <div class="card-body col-lg-6">
                    {!! Form::open()->route("pressing.store") !!}
                    {!! Form::text("nom", "Nom du pressing") !!}
                    {!! Form::text("adresse", "Adresse") !!}
                    {!! Form::text("telephone", "Telephone client")->attrs(["maxlength" => 8]) !!}

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Créer pressing</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
