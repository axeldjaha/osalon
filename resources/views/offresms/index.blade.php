@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-gift text-orange">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Offres SMS</span>
                            </div>
                            <div class="page-title-subheading">Cette section est réservée aux offres SMS</div>
                        </div>
                    </div>

                    @include("offresms.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">Offres</span>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive-sm">
                    <table id="datatable" class="table table-hover table-striped table-bordered" style="margin-bottom: 0 !important; margin-top: 0 !important;">
                        <thead class="bg-heavy-rain">
                        <tr>
                            <th>Quantité</th>
                            <th>Prix</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($offres as $offre)
                            <tr>
                                <td>{{ number_format($offre->quantite, 0, ",", " ") }}</td>
                                <td>{{ number_format($offre->prix, 0, ",", " ") }}</td>
                                <td>
                                    <a class="btn btn-link btn-sm mr-sm-2" href="{{ route("offre.sms.edit", $offre) }}">
                                        <i class="fa fa-edit"></i> Modifier
                                    </a>
                                    <a form-action="{{route("offre.sms.destroy", $offre)}}"
                                       form-method="delete"
                                       confirm-message="Supprimer l'offre ?"
                                       onclick="submitLinkForm(this)"
                                       href="#"
                                       class="confirm btn btn-link text-danger">
                                        <i class="fa fa-trash-alt"></i> Supprimer
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
