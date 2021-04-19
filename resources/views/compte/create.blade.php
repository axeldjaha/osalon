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

            {!! Form::open()->route("compte.store") !!}

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">Nouveau compte</span>
                    </div>
                </div>

                <div class="card-body p-0x" style="background: #fafafa">

                    <div class="row">
                        <div class="col-sm">
                            <table class="table table-striped" style="margin-bottom: 0 !important; margin-top: 0 !important;">
                                <tbody>
                                <tr>
                                    <td><label for="salon" class="col-form-label">Salon</label></td>
                                    <td>
                                        <input required type="text" id="salon" name="salon" class="form-control @error("salon") is-invalid @enderror" value="{{ old("salon") }}">
                                        @error("salon") <div class="invalid-feedback">{{$message}}</div> @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="adresse" class="col-form-label">Adresse</label></td>
                                    <td>
                                        <input required type="text" id="adresse" name="adresse" class="form-control @error("adresse") is-invalid @enderror" value="{{ old("adresse") }}">
                                        @error("adresse") <div class="invalid-feedback">{{$message}}</div> @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="telephone" class="col-form-label">Téléphone</label></td>
                                    <td>
                                        <input required type="text" id="telephone" name="telephone" class="form-control @error("telephone") is-invalid @enderror" value="{{ old("telephone") }}">
                                        @error("telephone") <div class="invalid-feedback">{{$message}}</div> @enderror
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-sm">
                            <table class="table table-striped" style="margin-bottom: 0 !important; margin-top: 0 !important;">
                                <thead class="bg-success text-white">
                                <th colspan="2" class="">Abonnement</th>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><label for="type_abonnement" class="col-form-label @if(isset($required["type_abonnement"])) required @endif">Type abonnement</label></td>
                                    <td>
                                        <select required class="form-control @error("type_abonnement") is-invalid @enderror" id="type_abonnement" name="type_abonnement">
                                            <option {{old("type_abonnement") == null ? "selected" : ""}} value="">----</option>
                                            @foreach($types as $type)
                                                <option value="{{$type->id}}" {{(old("type_abonnement") == $type->id) ? "selected" : ""}}>
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
                                        <input required type="text" id="montant" name="montant" class="form-control @error("montant") is-invalid @enderror" value="{{ old("montant") }}">
                                        @error("montant") <div class="invalid-feedback">{{$message}}</div> @enderror
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card-footer justify-content-end">
                    <button class="btn btn-primary">Créer compte</button>
                </div>
            </div>

            {!!Form::close()!!}

        </div>
    </div>

@endsection