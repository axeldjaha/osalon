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
                                        <li class="breadcrumb-item active">
                                            #{{$pressing->id}}
                                            <strong class="">{{$pressing->nom}}</strong>
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
                    <div class="row">
                        <div class="col-lg-10 mx-auto">
                            <table id="datatable" class="mt-3 table">
                                <tbody>
                                <td class="border-0 p-0" style="width: 100%">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="m-0" aria-label="breadcrumb">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item" aria-current="page">
                                                    {{$service->nom}}
                                                </li>
                                                <li class="breadcrumb-item active" aria-current="page">
                                                    <span class="text-orange">Tarifs</span>
                                                </li>
                                            </ol>
                                        </h6>
                                        <a class="btn btn-link ml-2" href="{{route("service.edit", [$pressing, $service])}}">
                                            <i class="fa fa-edit"></i>
                                            Editer
                                        </a>
                                    </div>
                                </td>
                                </tbody>
                            </table>

                            <table id="datatable" class="table table-hover table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 60%">Engin</th>
                                    <th style="width: 40%">Coût prestation</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($service->tarifications as $tarification)
                                    <tr>
                                        <td>{{$tarification->engin->nom}}</td>
                                        <td>{{$tarification->cout}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
