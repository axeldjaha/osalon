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
                                    <ol class="breadcrumb align-items-center">
                                        <li class="breadcrumb-item">
                                            <a>
                                                <i aria-hidden="true" class="fa fa-home"></i>
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="{{route("fichier.index")}}" class="">Liste</a>
                                        </li>
                                        <li class="breadcrumb-item active">
                                            #{{$fichier->id}}
                                            <strong class="">{{$fichier->nom}}</strong>
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
                    <div class="card-title">
                        <form method="post" action="{{route("fichier.prospect.store", $fichier)}}">
                            @csrf
                            <div class="form-row align-items-center d-flex justify-content-start">
                                <div class="col-4">
                                    <input type="text" name="nom" class="form-control @error("nom") is-invalid @enderror" placeholder="Nom">
                                    @error("nom") <div class="invalid-feedback"></div> @enderror
                                </div>
                                <div class="col-auto">
                                    <input required type="text" name="telephone" minlength="8" maxlength="8"  class="form-control @error("telephone") is-invalid @enderror" placeholder="Téléphone *">
                                    @error("telephone") <div class="invalid-feedback"></div> @enderror
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fa fa-plus"></i> Ajouter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <table id="datatable" class="mb-0 table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($prospects as $prospect)
                            <tr>
                                <td class="fit" scope="row">{{$prospect->id}}</td>
                                <td>{{$prospect->nom}}</td>
                                <td>{{$prospect->telephone}}</td>
                                <td class="">
                                    <button form-action="{{route("fichier.prospect.destroy", [$fichier, $prospect])}}"
                                       form-method="delete"
                                       confirm-message="Supprimer le prospect ?"
                                       onclick="submitLinkForm(this)"
                                       class="confirm btn btn-link ">
                                        <i class="fa fa-trash-alt text-danger"></i> Supprimer
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <th></th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
