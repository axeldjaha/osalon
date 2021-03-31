@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-gift text-orange">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Offres SMS</span>
                            </div>
                            <div class="page-title-subheading">Cette section est réservée aux offres SMS</div>
                        </div>
                    </div>

                    @include("offresms.actions")

                </div>
            </div>

            @include("layouts.alert")

            {!! Form::open()->route("offre.sms.update", [$offre])->put() !!}

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">Editer</span>
                    </div>
                </div>
                <div class="card-body p-0" style="background: #fafafa">
                    <table class="table table-striped mb-0">
                        <tbody>
                        <tr>
                            <td class="fit"><label for="quantite" class="col-form-label">Quantité</label></td>
                            <td style="width: 20%">
                                <input required type="number" id="quantite" name="quantite" min="0" class="form-control @error("quantite") is-invalid @enderror" value="{{ old("quantite") ?? $offre->quantite }}">
                                @error("quantite") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td class="fit"><label for="prix" class="col-form-label">Prix</label></td>
                            <td style="width: 20%">
                                <input required type="number" id="prix" name="prix" min="0" class="form-control @error("prix") is-invalid @enderror" value="{{ old("prix") ?? $offre->prix}}">
                                @error("prix") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>

                        <tr>
                            <td class="fit"></td>
                            <td style="width: 20% !important;">
                                <button class="btn btn-primary btn-lg">
                                    Modifier
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
