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

            {!! Form::open()->route("salon.update", [$salon])->put() !!}

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">Édition du salon</span>
                    </div>
                </div>

                <div class="card-body p-0x" style="background: #fafafa">
                    <table class="table table-striped" style="margin-bottom: 0 !important; margin-top: 0 !important;">
                        <tbody>
                        <tr>
                            <td class="fit"><label for="nom" class="col-form-label required">Nom</label></td>
                            <td>
                                <input required type="text" id="nom" name="nom" class="form-control @error("nom") is-invalid @enderror" value="{{ old("nom") ?? $salon->nom }}">
                                @error("nom") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td class="fit"><label for="adresse" class="col-form-label required">Adresse</label></td>
                            <td>
                                <input required type="text" id="adresse" name="adresse" class="form-control @error("adresse") is-invalid @enderror" value="{{ old("adresse") ?? $salon->adresse }}">
                                @error("adresse") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td class="fit"><label for="telephone" class="col-form-label required">Téléphone</label></td>
                            <td>
                                <input required type="text" id="telephone" name="telephone" class="form-control @error("telephone") is-invalid @enderror" value="{{ old("telephone") ?? $salon->telephone }}">
                                @error("telephone") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td class="fit"></td>
                            <td style="width: 20% !important;">
                                <button class="btn btn-primary btn-lg">
                                    Enregistrer
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {!!Form::close()!!}

        </div>
    </div>

@endsection
