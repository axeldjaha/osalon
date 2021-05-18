@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-th">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Comptes</span>
                            </div>
                            <div class="page-title-subheading">Cette section est réservée à la gestion des comptes</div>
                        </div>
                    </div>

                    @include("compte.actions")

                </div>
            </div>

            @include("layouts.alert")

            {!! Form::open()->route("user.store", [$compte]) !!}

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain">
                    <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-link btn-sm mr-sm-3" style="text-transform: initial">
                        <i class="fa fa-chevron-left"></i> Retour
                    </a>
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">Créer utilisateur</span>
                    </div>
                </div>

                <div class="card-body p-0x" style="background: #fafafa">
                    <table class="table table-striped" style="margin-bottom: 0 !important; margin-top: 0 !important;">
                        <tbody>
                        <tr>
                            <td class="fit"><label for="salon" class="col-form-label required">Salon</label></td>
                            <td>
                                <select required class="form-control @error("salon") is-invalid @enderror" id="salon" name="salon">
                                    <option value="" @if(old("salon") == null) selected @endif disabled>----</option>
                                    @foreach($compte->salons()->orderBy("nom")->get() as $salon)
                                        <option value="{{$salon->id}}" @if($salon->id == old("salon")) selected @endif>
                                            {{ $salon->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("salon") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td class="fit"><label for="name" class="col-form-label">Nom</label></td>
                            <td>
                                <input type="text" id="name" name="name" class="form-control @error("name") is-invalid @enderror" value="{{ old("name") }}">
                                @error("name") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td class="fit"><label for="telephone" class="col-form-label required">Téléphone</label></td>
                            <td>
                                <input required type="text" id="telephone" name="telephone" class="form-control @error("telephone") is-invalid @enderror" value="{{ old("telephone") }}">
                                @error("telephone") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td class="fit"><label for="email" class="col-form-label">Email</label></td>
                            <td>
                                <input type="email" id="email" name="email" class="form-control @error("email") is-invalid @enderror" value="{{ old("email") }}">
                                @error("email") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>

                        <tr>
                            <td class="fit"></td>
                            <td style="width: 20% !important;">
                                <button class="btn btn-primary btn-lg">Créer l'utilisateur</button>
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
