@extends($extends)

@section("content")

    <div class="app-main__outer @if($user->formateur != null) professeur @endif">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="lnr-user text-primary">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Mon compte</span>
                            </div>
                            <div class="page-title-subheading">Cette section est réservée à la mise à jour de votre compte</div>
                        </div>
                    </div>

                    @include("account.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">Mes accès</span>
                    </div>
                </div>
                <div class="card-body" style="background: #fafafa;">
                    {!! Form::open()->route("account.acces.update")->put()->multipart() !!}

                    <div class="row">
                        <div class="col">
                            <table class="table table-borderless" style="margin-bottom: 0 !important; margin-top: 0 !important;">
                                <tbody>
                                <tr>
                                    <td class="fit"><label for="password" class="col-form-label required">Mot de passe actuel</label></td>
                                    <td>
                                        <input required type="password" id="password" name="password" minlength="6" value="{{ old("password") }}" class="form-control @error("password") is-invalid @enderror">
                                        @error('password')<div class="invalid-feedback">{{$message}}</div> @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit"><label for="nouveau_mot_de_passe" class="col-form-label">Nouveau mot de passe</label></td>
                                    <td>
                                        <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" minlength="6" value="{{ old("nouveau_mot_de_passe") }}" class="form-control @error("nouveau_mot_de_passe") is-invalid @enderror">
                                        @error('nouveau_mot_de_passe')<div class="invalid-feedback">{{$message}}</div> @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit"><label for="nouveau_mot_de_passe_confirmation" class="col-form-label">Confirmez</label></td>
                                    <td>
                                        <input type="password" id="nouveau_mot_de_passe_confirmation" name="nouveau_mot_de_passe_confirmation" minlength="6" value="{{ old("nouveau_mot_de_passe_confirmation") }}" class="form-control @error("nouveau_mot_de_passe_confirmation") is-invalid @enderror">
                                        @error('nouveau_mot_de_passe_confirmation')<div class="invalid-feedback">{{$message}}</div> @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <button type="submit" class="btn btn-primary btn-lg">Enregistrer</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
