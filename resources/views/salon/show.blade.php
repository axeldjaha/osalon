@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-tachometer-alt text-orange">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Salon</span>
                            </div>
                            <div class="page-title-subheading">Cette section est réservée à la gestion des salons</div>
                        </div>
                    </div>

                    @include("salon.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain" style="height: inherit">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">Détails</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <table class="table table-striped mb-0">
                                <tbody>
                                <tr>
                                    <td class="fitx"><strong>Nom</strong></td>
                                    <td>{{ $salon->nom }}</td>
                                </tr>
                                <tr>
                                    <td class="fitx"><strong>Pid</strong></td>
                                    <td>{{ $salon->pid }}</td>
                                </tr>
                                <tr>
                                    <td class="fitx"><strong>Adresse</strong></td>
                                    <td>{{ $salon->adresse }}</td>
                                </tr>
                                <tr>
                                    <td class="fitx"><strong>Créé le</strong></td>
                                    <td>{{ date("d/m/Y", strtotime($salon->created_at)) }}</td>
                                </tr>

                                </tbody>
                            </table>
                            <div class="dropdown-divider"></div>
                            <div class="mt-3 clearfix">
                                <a form-action="{{route("salon.destroy", $salon)}}"
                                   form-method="delete"
                                   confirm-message="Supprimer le salon ?"
                                   onclick="submitLinkForm(this)"
                                   href="#"
                                   class="confirm btn btn-outline-danger">Supprimer le salon
                                </a>
                            </div>
                        </div>

                        <div class="col-sm">
                            <table>
                                <thead>
                                <th>Date</th>
                                <th>Validité</th>
                                <th>Mode </th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
