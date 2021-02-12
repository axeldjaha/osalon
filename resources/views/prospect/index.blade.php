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
                <div class="card-body">
                    <table id="datatable" class="mb-0 table table-hover table-striped table-bordered table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Prostect</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($fichiers as $fichier)
                            <tr>
                                <th class="fit" scope="row">
                                    <i class="fa fa-file-excel header-icon text-muted" style="font-size: 1.5rem"></i>
                                </th>
                                <td>{{$fichier->nom}}</td>
                                <td>
                                    <div class="badge badge-pill badge-info">
                                        {{number_format($fichier->prospects->count(), 0, ',', " ")}}
                                    </div>
                                </td>
                                <td><span hidden>{{$fichier->created_at}}</span> {{date("d/m/Y", strtotime($fichier->created_at))}}</td>
                                <td class="fit">
                                    <a class="btn btn-link mr-sm-2" href="{{route("fichier.show", $fichier)}}">
                                        <i class="fa fa-list"></i> Liste
                                    </a>
                                    <a class="btn btn-link mr-sm-2" href="{{route("fichier.edit", $fichier)}}">
                                        <i class="fa fa-edit"></i> Renommer
                                    </a>
                                    <a form-action="{{route("fichier.destroy", $fichier)}}"
                                       form-method="delete"
                                       confirm-message="Supprimer le fichier ?"
                                       onclick="submitLinkForm(this)"
                                       class="confirm btn btn-link"
                                        href="">
                                        <i class="fa fa-trash-alt text-danger"></i> Supprimer
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Prostect</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
