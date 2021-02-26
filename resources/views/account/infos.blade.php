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
                        <span class="d-inline-block mr-sm-3">Mes informations</span>
                    </div>
                </div>
                <div class="card-body" style="background: #fafafa;">
                    {!! Form::open()->route("account.infos.update")->put()->multipart() !!}
                    <div class="row">
                        <div class="@if($user->formateur != null) col-sm-1 @else col-sm-2 @endif" style="text-align: center">
                            @if($user->photo != null && file_exists(storage_path("app/public/users/$user->photo")))
                                <img class="img-thumbnail img-fluid rounded-circle p-0" width="180" src="{{asset("storage/users/$user->photo")}}" alt="Photo">
                            @else
                                <i class="fa fa-user text-primary" style="font-size: 5rem"></i>
                            @endif
                        </div>
                        <div class="col">
                            <table class="table table-borderless" style="margin-bottom: 0 !important; margin-top: 0 !important;">
                                <tbody>
                                <tr>
                                    <td class="fit"><label for="nom" class="col-form-label required">Nom et prénoms</label></td>
                                    <td>
                                        <input type="text" id="nom" name="nom" value="{{ old("nom") ?? auth()->user()->name }}" class="form-control @error("nom") is-invalid @enderror" placeholder="Nom et prénoms">
                                        @error("nom") <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit"><label for="photo" class="col-form-label">Photo de profile</label></td>
                                    <td>
                                        <input type="file" accept="image/*" name="photo" id="photo" class="form-control-file @error('photo') is-invalid @enderror">
                                        @error('photo')<div class="invalid-feedback">{{$message}}</div> @enderror
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
