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
                                            <a href="{{route("pressing.index")}}" class="">Liste des pressings</a>
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
                <div class="card-body">
                    <table id="datatable" class="mb-0 table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Pressing</th>
                            <th>Adresse</th>
                            <th>Créé le</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pressings as $pressing)
                            <tr>
                                <th class="fit" scope="row">{{$pressing->id}}</th>
                                <td>{{$pressing->nom}}</td>
                                <td>{{$pressing->adresse}}</td>
                                <td><span hidden>{{$pressing->created_at}}</span> {{date("d/m/Y", strtotime($pressing->created_at))}}</td>
                                <td class="fit">
                                    <a href="{{route("pressing.show", $pressing)}}" tabindex="0" class="btn btn-link">
                                        <i class="pe-7s-edit"></i> Détail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Pressing</th>
                            <th>Adresse</th>
                            <th>Créé le</th>
                            <th>Actions</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
