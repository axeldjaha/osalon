@extends("layouts.app")

@section("content")
    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-user-friends">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Administrateurs</span>
                            </div>
                            <div class="page-title-subheading">Cette section est réservée à la gestion des comptes admins</div>
                        </div>
                    </div>

                    @include("admin.actions")

                </div>
            </div>

            @include("layouts.alert")

            {!! Form::open()->route("admin.store") !!}

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">Nouveau</span>
                    </div>
                </div>
                <div class="card-body p-0" style="background: #fafafa">
                    <table class="table table-striped mb-0">
                        <tbody>
                        <tr>
                            <td><label for="name" class="col-form-label">Nom</label></td>
                            <td>
                                <input required type="text" id="name" name="name" class="form-control @error("name") is-invalid @enderror" value="{{ old("name") }}">
                                @error("name") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td><label for="telephone" class="col-form-label">Téléphone</label></td>
                            <td>
                                <input required type="text" id="telephone" name="telephone" class="form-control @error("telephone") is-invalid @enderror" value="{{ old("telephone") }}">
                                @error("telephone") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td><label for="email" class="col-form-label">Email</label></td>
                            <td>
                                <input required type="email" id="email" name="email" class="form-control @error("email") is-invalid @enderror" value="{{ old("email") }}">
                                @error("email") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td class="fit"></td>
                            <td style="width: 20% !important;">
                                <button class="btn btn-primary btn-lg">
                                    Créer l'utilisateur
                                </button>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection
