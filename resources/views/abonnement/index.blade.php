@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-credit-card text-orange">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Abonnement</span>
                            </div>
                            <div class="page-title-subheading">Cette section est réservée aux réabonnements</div>
                        </div>
                    </div>

                    @include("abonnement.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">Parcourir</span>
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
                            <th>Echéance</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($salons as $salon)
                            <tr>
                                @php($abonnement = $salon->abonnements()->orderBy("id", "desc")->first())
                                <td>{{$salon->nom}}</td>
                                <td>{{$salon->adresse}}</td>
                                <td>{{$salon->pid}}</td>
                                <td><span hidden>{{$abonnement->echeance}}</span> {{date("d/m/Y", strtotime($abonnement->echeance))}}</td>
                                <td class="">
                                    @if(\Illuminate\Support\Carbon::parse($abonnement->echeance)->greaterThanOrEqualTo(\Illuminate\Support\Carbon::now()))
                                        <span class="badge badge-success badge-pill">On</span>
                                    @else
                                        <span class="badge badge-danger badge-pill">Off</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{route("abonnement.create", ["salon" => $salon->pid])}}">Réabonner</a>
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
