@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-coins">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Transactions</span>
                            </div>
                            <div class="page-title-subheading">Cette section est réservée aux transactions</div>
                        </div>
                    </div>

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">Les 100 dernières transactions</span>
                    </div>
                    <div class="btn-actions-pane-right d-flex align-items-center ">
                        <input type="search" oninput="filterTable(this, 'datatable')" class="form-control form-control-sm" placeholder="Chercher dans la liste">
                    </div>
                </div>
                <div class="card-body p-0">
                    <table id="datatable" class="table table-hover table-striped table-bordered" style="margin-bottom: 0 !important; margin-top: 0 !important;">
                        <thead class="bg-heavy-rain">
                        <tr>
                            <th>Référence</th>
                            <th>Salon</th>
                            <th>Pid</th>
                            <th>Montant</th>
                            <th>Validité</th>
                            <th>Mode paiement</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{$transaction->reference}}</td>
                                <td>{{$transaction->salon->nom}}</td>
                                <td>{{$transaction->salon->pid}}</td>
                                <td>{{number_format($transaction->montant, 0, ",", " ")}}</td>
                                <td>{{$transaction->validite}}</td>
                                <td>{{$transaction->mode_paiement}}</td>
                                <td><span hidden>{{$transaction->date_transaction}}</span> {{date("d/m/Y", strtotime($transaction->date_transaction))}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
