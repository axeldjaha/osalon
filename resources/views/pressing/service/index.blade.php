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
                    <table id="datatable" class="mb-0 table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($services as $service)
                            <tr>
                                <th class="" scope="row">{{$service->id}}</th>
                                <td>{{$service->nom}}</td>
                                <td class="">
                                    <a class="btn btn-link mr-2" href="{{route("service.edit", [$pressing, $service])}}">
                                        <i class="fa fa-edit"></i>
                                        Modifier
                                    </a>
                                    <a form-action="{{route("service.destroy", [$pressing, $service])}}"
                                       form-method="delete"
                                       confirm-message="Supprimer le service ?"
                                       onclick="submitLinkForm(this)"
                                       href="#"
                                       class="confirm btn btn-link">
                                        <i class="fa fa-trash-alt text-danger"></i> Supprimer
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection
