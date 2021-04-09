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
                <div class="card-header-tab card-header bg-heavy-rain">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">SALON</span>
                        <div class="text-transform-initial mr-sm-3">
                            Total: <span class="badge badge-primary badge-pill">{{ count($salons) }}</span>
                        </div>

                        <div class="text-transform-initial mr-sm-3">
                             Actif: <span class="badge badge-success badge-pill">{{ count($actifs) }}</span>
                        </div>

                        <div class="text-transform-initial mr-sm-3">
                            Expiré: <span class="badge badge-danger badge-pill">{{ count($salons) - count($actifs) }}</span>
                        </div>
                    </div>
                    <div class="btn-actions-pane-right d-flex align-items-center ">
                        <input type="search" oninput="filterTable(this, 'datatable')" class="form-control form-control-sm" placeholder="Chercher dans la liste">
                    </div>
                </div>
                <div class="card-body p-0">
                    <table id="datatable" class="table table-hover table-striped table-bordered" style="margin-bottom: 0 !important; margin-top: 0 !important;">
                        <thead class="bg-heavy-rain">
                        <tr>
                            <th>Salon</th>
                            <th>Adresse</th>
                            <th>Pid</th>
                            <th>Créé le</th>
                            <th>Echéance</th>
                            <th>Abonnement</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($salons as $salon)
                            <tr>
                                <td>{{$salon->nom}}</td>
                                <td>{{$salon->adresse}}</td>
                                <td>{{$salon->pid}}</td>
                                <td><span hidden>{{$salon->created_at}}</span> {{date("d/m/Y", strtotime($salon->created_at))}}</td>
                                @php($abonnement = $salon->abonnements()->orderBy("id", "desc")->first())
                                <td><span hidden>{{$abonnement->echeance ?? null}}</span> @if($abonnement != null) {{date("d/m/Y", strtotime($abonnement->echeance))}} @endif</td>
                                <td class="">
                                    @if($abonnement != null && \Illuminate\Support\Carbon::parse($abonnement->echeance)->greaterThanOrEqualTo(\Illuminate\Support\Carbon::now()))
                                        <span class="badge badge-success badge-pill">Actif</span>
                                    @else
                                        <span class="badge badge-danger badge-pill">Expiré<span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-link btn-sm mr-sm-2" href="{{ route("salon.show", $salon) }}">
                                        <i class="fa fa-tasks"></i> Détails
                                    </a>
                                    <a class="btn btn-primary btn-sm" href="{{route("abonnement.create", $salon)}}">Réabonner</a>
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
