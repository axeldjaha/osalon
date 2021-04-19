@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-th">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Comptes</span>
                            </div>
                            <div class="page-title-subheading">Cette section est réservée à la gestion des comptes</div>
                        </div>
                    </div>

                    @include("compte.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain" >
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <a href="{{ route("compte.index") }}" class="btn btn-link btn-sm mr-sm-3" style="text-transform: initial">
                            <i class="fa fa-chevron-left"></i> Retour
                        </a>
                        <span class="d-inline-block mr-sm-3">Détails</span>
                    </div>
                    <div class="btn-actions-pane-right d-flex align-items-center ">
                        <a class="btn btn-primary" href="{{route("abonnement.create", $compte)}}">Réabonner</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-striped mb-0">
                                <tbody>
                                <tr>
                                    <td class=""><strong>ID</strong></td>
                                    <td>{{ $compte->id }}</td>
                                </tr>
                                <tr>
                                    <td class=""><strong>Salons</strong></td>
                                    <td>{{ $compte->salons()->count() }}</td>
                                </tr>
                                <tr>
                                    <td class=""><strong>Users</strong></td>
                                    <td>{{ $compte->users()->count() }}</td>
                                </tr>
                                <tr>
                                    <td class=""><strong>SMS</strong></td>
                                    <td>{{ number_format($compte->sms_balance, 0, ",", " ") }}</td>
                                </tr>
                                <tr>
                                    <td class=""><strong>Créé le</strong></td>
                                    <td>{{ date("d/m/Y", strtotime($compte->created_at)) }}</td>
                                </tr>
                                @php($abonnement = $compte->abonnements()->orderBy("id", "desc")->first())
                                <tr>
                                    <td class=""><strong>Abonnement</strong></td>
                                    <td>
                                        @if(\Illuminate\Support\Carbon::parse($abonnement->echeance)->lessThan(\Illuminate\Support\Carbon::now()))
                                            <span class="badge badge-danger badge-pill">Expiré<span>
                                        @else
                                                        <span class="badge badge-success badge-pill">Actif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class=""><strong>Echéance</strong></td>
                                    <td>{{ date("d/m/Y", strtotime($abonnement->echeance)) }}</td>
                                </tr>

                                <tr>
                                    <td colspan="2" class="pt-3 pb-3">
                                        <a form-action="{{route("compte.destroy", $compte)}}"
                                           form-method="delete"
                                           confirm-message="Supprimer le compte ?"
                                           onclick="submitLinkForm(this)"
                                           href="#"
                                           class="confirm btn btn-danger">Supprimer le compte
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-sm-6">
                            <table id="datatable" class="table table-hover table-striped table-bordered" style="margin-bottom: 0 !important; margin-top: 0 !important;">
                                <thead class="">
                                <th colspan="6">Abonnements</th>
                                </thead>
                                <thead class="bg-success text-white">
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Validité</th>
                                <th>Action</th>
                                </thead>
                                <tbody>
                                @foreach($compte->abonnements()->orderBy("id", "desc")->get() as $abonnement)
                                    <tr>
                                        <td><span hidden>{{ $abonnement->created_at }}</span> {{ date("d/m/Y", strtotime($abonnement->created_at)) }}</td>
                                        <td>{{ number_format($abonnement->montant, 0, ",", " ") }}</td>
                                        <td>{{ $abonnement->type->validity }}</td>
                                        <td>
                                            <button form-action="{{route("abonnement.destroy", [$compte, $abonnement])}}"
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

            </div>

        </div>
    </div>

@endsection
