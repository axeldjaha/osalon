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

            {!! Form::open()->route("salon.reabonner", [$salon]) !!}

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <a href="{{ route("salon.index") }}" class="btn btn-link btn-sm mr-sm-3" style="text-transform: initial">
                            <i class="fa fa-chevron-left"></i> Retour
                        </a>
                        <span class="d-inline-block mr-sm-3">Réabonnement</span>
                    </div>
                </div>
                <div class="card-body p-0" style="background: #fafafa">
                    <table class="table table-striped mb-0">
                        <tbody>
                        <tr>
                            <td class="fit"><label for="salon" class="col-form-label">Salon</label></td>
                            <td style="width: 20%">
                                <select required class="form-control @error("salon") is-invalid @enderror" id="salon" name="salon">
                                    <option value="{{ $salon->id }}"> {{ $salon->pid }} - {{ $salon->nom }}</option>
                                </select>
                                @error("salon") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td class="fit"><label for="montant" class="col-form-label">Montant</label></td>
                            <td style="width: 20%">
                                <input required type="number" id="montant" name="montant" min="0" class="form-control @error("montant") is-invalid @enderror" value="{{ old("montant") }}">
                                @error("montant") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td class="fit"><label for="validite" class="col-form-label">Validité</label></td>
                            <td style="width: 20%">
                                <input required type="number" id="validite" name="validite" class="form-control @error("validite") is-invalid @enderror" value="{{ old("validite") }}">
                                @error("validite") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td class="fit"><label for="mode_paiement" class="col-form-label">Mode paiement</label></td>
                            <td style="width: 20%">
                                <select required name="mode_paiement" id="mode_paiement" class="form-control @error("mode_paiement") is-invalid @enderror">
                                    <option value="" disabled {{old("mode_paiement") == null ? "selected" : ""}}>------</option>
                                    @foreach($modes as $mode => $name)
                                        <option value="{{$mode}}" {{old("mode_paiement") == $mode ? "selected" : ""}}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("mode_paiement") <div class="invalid-feedback">{{$message}}</div> @enderror
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
