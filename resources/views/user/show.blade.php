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
                <div class="card-header-tab card-header bg-heavy-rain" >
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <a href="{{ route("salon.index") }}" class="btn btn-link btn-sm mr-sm-3" style="text-transform: initial">
                            <i class="fa fa-chevron-left"></i> Retour
                        </a>
                        <span class="d-inline-block mr-sm-3">Détails</span>
                    </div>
                    <div class="btn-actions-pane-right d-flex align-items-center ">
                        <a class="btn btn-primary" href="{{route("abonnement.create", $salon)}}">Réabonner</a>
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
                                @php($abonnement = $salon->abonnements()->orderBy("id", "desc")->first())
                                <tr>
                                    <td class="fitx"><strong>Echéance</strong></td>
                                    <td>@if($abonnement != null) {{date("d/m/Y", strtotime($abonnement->echeance))}} @endif</td>
                                </tr>
                                <tr>
                                    <td class="fitx"><strong>Statut abonnement</strong></td>
                                    <td>
                                        @if($abonnement != null && \Illuminate\Support\Carbon::parse($abonnement->echeance)->greaterThanOrEqualTo(\Illuminate\Support\Carbon::now()))
                                            <span class="badge badge-success badge-pill">Actif</span>
                                        @else
                                            <span class="badge badge-danger badge-pill">Expiré<span>
                                        @endif
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>

                        <div class="col-sm-auto">
                            <table id="datatable" class="table table-hover table-striped table-bordered" style="margin-bottom: 0 !important; margin-top: 0 !important;">
                                <thead class="bg-heavy-rainx">
                                <th colspan="6">Abonnements</th>
                                </thead>
                                <thead class="">
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Validité</th>
                                <th>Échéance</th>
                                <th>Mode paiement</th>
                                <th>Action</th>
                                </thead>
                                <tbody>
                                @foreach($salon->abonnements()->orderBy("id", "desc")->get() as $abonnement)
                                    <tr>
                                        <td><span hidden>{{ $abonnement->created_at }}</span> {{ date("d/m/Y", strtotime($abonnement->created_at)) }}</td>
                                        <td>{{ number_format($abonnement->montant, 0, ",", " ") }}</td>
                                        <td>{{ $abonnement->validite }}</td>
                                        <td><span hidden>{{ $abonnement->echeance }}</span> {{ date("d/m/Y", strtotime($abonnement->echeance)) }}</td>
                                        <td>{{ $abonnement->mode_paiement }}</td>
                                        <td>
                                            <a class="btn btn-link btn-sm mr-sm-2" href="{{ route("abonnement.edit", [$salon, $abonnement]) }}">
                                                <i class="fa fa-edit"></i> Modifier
                                            </a>
                                            <button form-action="{{route("abonnement.destroy", [$salon, $abonnement])}}"
                                               form-method="delete"
                                               confirm-message="Supprimer l'abonnement ?"
                                               onclick="submitLinkForm(this)"
                                               class="confirm btn btn-link text-danger btn-sm">Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a form-action="{{route("salon.delete", $salon)}}"
                       form-method="delete"
                       confirm-message="Supprimer le salon ?"
                       onclick="submitLinkForm(this)"
                       href="#"
                       class="confirm btn btn-outline-danger">Supprimer le salon
                    </a>
                </div>
            </div>

        </div>
    </div>

@endsection
