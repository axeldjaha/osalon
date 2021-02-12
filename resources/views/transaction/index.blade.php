@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="lnr-database text-orange">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Transactions</span>
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
                                            <a href="{{route("transaction.index")}}" class="">Liste</a>
                                        </li>
                                    </ol>
                                </h6>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-header">Les 100 dernières transactions</div>
                <div class="card-body">
                    <table id="datatable" class="mb-0 table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Référence</th>
                            <th>Pid</th>
                            <th>Pressing</th>
                            <th>Montant</th>
                            <th>Validité</th>
                            <th>Mode paiement</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <th class="fit" scope="row">{{$transaction->id}}</th>
                                <td>{{$transaction->reference}}</td>
                                <td>{{$transaction->pressing->abonnement->pid}}</td>
                                <td>{{$transaction->pressing->nom}}</td>
                                <td>{{number_format($transaction->montant, 0, ",", " ")}}</td>
                                <td>{{$transaction->validite}}</td>
                                <td>{{$transaction->mode_paiement}}</td>
                                <td><span hidden>{{$transaction->date_transaction}}</span> {{date("d/m/Y", strtotime($transaction->date_transaction))}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Référence</th>
                            <th>Pid</th>
                            <th>Pressing</th>
                            <th>Montant</th>
                            <th>Validité</th>
                            <th>Mode paiement</th>
                            <th>Date</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
