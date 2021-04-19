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
                                <span class="d-inline-block">Rechargement SMS</span>
                            </div>
                            <div class="page-title-subheading">Cette section est réservée aux rechargements SMS</div>
                        </div>
                    </div>

                    @include("abonnement.actions")

                </div>
            </div>

            @include("layouts.alert")

            {!! Form::open()->route("recharge.store", [$compte]) !!}

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-link btn-sm mr-sm-3" style="text-transform: initial">
                            <i class="fa fa-chevron-left"></i> Retour
                        </a>
                        <span class="d-inline-block mr-sm-3">Rechargement SMS</span>
                    </div>
                </div>
                <div class="card-body p-0" style="background: #fafafa">
                    <table class="table table-striped mb-0">
                        <tbody>
                        <tr>
                            <td class="fit"><label for="salon" class="col-form-label">Compte</label></td>
                            <td style="width: 20%">
                                <select required class="form-control @error("compte") is-invalid @enderror" id="compte" name="compte">
                                    <option value="{{ $compte->id }}" {{old("salon") == null ? "selected" : ""}} >#{{ $compte->id }}</option>
                                </select>
                                @error("salon") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td><label for="sms_balance" class="col-form-label">Volume SMS</label></td>
                            <td>
                                <input required type="text" id="sms_balance" name="sms_balance" class="form-control @error("sms_balance") is-invalid @enderror" value="{{ old("sms_balance") }}">
                                @error("sms_balance") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>

                        <tr>
                            <td class="fit"></td>
                            <td style="width: 20% !important;">
                                <button class="btn btn-primary btn-lg">
                                    Recharger
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection
