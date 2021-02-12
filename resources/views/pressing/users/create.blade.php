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
                    <ul class="nav">
                        <li class="nav-item">
                            <a href="{{route("pressing.users", $pressing)}}" class="nav-link active">
                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                        <i class="fa fa-list fa-w-20"></i>
                                    </span>
                                <span>Liste</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route("pressing.createUser", $pressing)}}" class="nav-link active">
                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                        <i class="fa fa-user-plus fa-w-20"></i>
                                    </span>
                                <span>Nouveau</span>
                            </a>
                        </li>
                    </ul>
                    <div class="divider"></div>
                    <div class="row">
                        <div class="col-lg-10 mx-auto">
                            {!! Form::open()->route("pressing.storeUser", [$pressing]) !!}
                            <div class="form-row">
                                <div class="col-lg-6">
                                    {!! Form::text("telephone", "Numéro de téléphone") !!}
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-lg-6">
                                    {!! Form::select("role", "Role")->options($roles->prepend('---', '')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Créer le compte</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
