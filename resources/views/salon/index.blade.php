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
                            Total <span class="badge badge-primary badge-pill">{{ count($salons) }}</span>
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
                            <th>Nom</th>
                            <th>Adresse</th>
                            <th>Téléphone</th>
                            <th>Créé le</th>
                            <th>Abonnement</th>
                            <th>Compte</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($salons as $salon)
                            <tr>
                                <td>{{$salon->nom}}</td>
                                <td>{{$salon->adresse}}</td>
                                <td>{{$salon->telephone}}</td>
                                <td><span hidden>{{$salon->created_at}}</span> {{date("d/m/Y", strtotime($salon->created_at))}}</td>
                                @php($abonnement = $salon->compte->abonnements()->orderBy("id", "desc")->first())
                                <td class="">
                                    @if(\Illuminate\Support\Carbon::parse($abonnement->echeance)->lessThan(\Illuminate\Support\Carbon::today()))
                                        <span class="badge badge-danger badge-pill">Expiré<span>
                                    @else
                                        <span class="badge badge-success badge-pill">Actif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route("compte.show", $salon->compte) }}" class="btn btn-link">Aller au compte</a>
                                </td>
                                <td>
                                    <a href="{{ route("salon.edit", $salon) }}" class="btn btn-link">
                                        <i class="fa fa-edit"></i> Editer
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
