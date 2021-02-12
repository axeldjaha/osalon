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
                            <div class="page-title-subheading opacity-10">
                                <h6 class="" aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a>
                                                <i aria-hidden="true" class="fa fa-home"></i>
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="{{route("abonnement.index")}}" class="">Liste</a>
                                        </li>
                                    </ol>
                                </h6>
                            </div>
                        </div>
                    </div>

                    @include("abonnement.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <table id="datatable" class="mb-0 table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Pressing</th>
                            <th>Echéance</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($abonnements as $abonnement)
                            <tr>
                                <th class="fit" scope="row">{{$abonnement->pressing->id}}</th>
                                <td>{{$abonnement->pressing->nom}}</td>
                                <td><span hidden>{{$abonnement->echeance}}</span> {{date("d/m/Y", strtotime($abonnement->echeance))}}</td>
                                <td>
                                    <a class="btn btn-success" href="{{route("abonnement.reabonnement", $abonnement->pressing->id)}}">Réabonner</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Pressing</th>
                            <th>Echéance</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
