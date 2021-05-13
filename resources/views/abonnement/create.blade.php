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

            {!! Form::open()->route("abonnement.store", [$compte]) !!}

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-link btn-sm mr-sm-3" style="text-transform: initial">
                            <i class="fa fa-chevron-left"></i> Retour
                        </a>
                        <span class="d-inline-block mr-sm-3">Réabonnement</span>
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
                            <td><label for="type_abonnement" class="col-form-label required">Type abonnement</label></td>
                            <td>
                                <select required class="form-control @error("type_abonnement") is-invalid @enderror" id="type_abonnement" name="type_abonnement">
                                    @foreach($types as $type)
                                        <option value="{{$type->id}}" @if($type->id == $currentAbonnement->type_id) selected @endif>
                                            {{ number_format($type->montant, 0, ",", " ") }} FCFA / {{ $type->intitule }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("type_abonnement") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td><label for="montant" class="col-form-label">Montant</label></td>
                            <td>
                                <input required type="text" id="montant" name="montant" class="form-control @error("montant") is-invalid @enderror" value="{{ old("montant") ?? $currentAbonnement->montant }}">
                                @error("montant") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>

                        <tr>
                            <td class="fit"></td>
                            <td style="width: 20% !important;">
                                <button class="btn btn-primary btn-lg">
                                    Réabonner
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
