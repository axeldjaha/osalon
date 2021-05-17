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
                <div class="card-header-tab card-header bg-heavy-rain">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">Comptes</span>

                        <div class="text-transform-initial mr-sm-3">
                            Total <span class="badge badge-primary badge-pill">{{ count($comptes) }}</span>
                        </div>

                        <div class="text-transform-initial mr-sm-3">
                            Actif <span class="badge badge-success badge-pill">{{ count($actifs) }}</span>
                        </div>

                        <div class="text-transform-initial mr-sm-3">
                            Expiré <span class="badge badge-danger badge-pill">{{ count($comptes) - count($actifs) }}</span>
                        </div>
                    </div>
                    <div class="btn-actions-pane-right d-flex align-items-center ">
                        <input type="search" oninput="filterTable(this, 'datatable')" class="form-control form-control-sm" placeholder="Chercher dans la liste">
                    </div>
                </div>
                <div class="card-body p-0 table-responsive-sm">
                    <table id="datatable" class="table table-hover table-striped table-bordered" style="margin-bottom: 0 !important; margin-top: 0 !important;">
                        <thead class="bg-heavy-rain">
                        <tr>
                            <th>#ID</th>
                            <th>Créé le</th>
                            <th>Abonnement</th>
                            <th>Echéance</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($comptes as $compte)
                            <tr>
                                <td>{{ $compte->id }}</td>
                                <td><span hidden>{{$compte->created_at}}</span> {{ date("d/m/Y", strtotime($compte->created_at)) }}</td>
                                @php($abonnement = $compte->abonnements()->orderBy("id", "desc")->first())
                                <td class="">
                                    @if(\Illuminate\Support\Carbon::parse($abonnement->echeance)->lessThan(\Illuminate\Support\Carbon::today()))
                                        <span class="badge badge-danger badge-pill">Expiré<span>
                                    @else
                                          <span class="badge badge-success badge-pill">Actif</span>
                                    @endif
                                </td>
                                <td>{{ date("d/m/Y", strtotime($abonnement->echeance)) }}</td>
                                <td>
                                    <a href="{{ route("compte.show", $compte) }}" class="btn btn-link mr-sm-2">Détails</a>
                                    <a href="{{ route("abonnement.create", $compte) }}" class="btn btn-primary mr-2">Réabonner</a>
                                    <a href="{{ route("recharge.create", $compte) }}" class="btn btn-alternate">Recharger SMS</a>
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
