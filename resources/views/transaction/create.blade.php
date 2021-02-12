@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="lnr-database text-orange">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Abonnement</span>
                            </div>
                            <div class="page-title-subheading opacity-10">
                                <h6 class="" aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a>
                                                <i aria-hidden="true" class="fa fa-home"></i>
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="{{route("abonnement.index")}}" class="">Liste</a>
                                        </li>
                                    </ol>
                                </h6>
                            </div>
                        </div>
                    </div>

                    @include("abonnement.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-plus fa-w-20"></i>
                        </span>
                        Nouvel abonnement
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open()->route("abonnement.store") !!}
                    <div class="form-row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="pressing">Pressing</label>
                                <select name="pressing" id="pressing" class="multiselect-dropdown form-control @error("pressing") is-invalid @enderror">
                                    <option disabled {{old("pressing") == null ? "selected" : ""}}>--- Pressing ---</option>
                                    @foreach($pressings as $pressing)
                                        <option value="{{$pressing->id}}" {{old("pressing") == $pressing->id ? "selected" : ""}}>
                                            {{$pressing->nom}}
                                        </option>
                                    @endforeach
                                </select>
                                @error("pressing") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-lg-3">
                            {!! Form::text("montant", "Montant")->type("number")->min(0) !!}
                        </div>
                        <div class="col-lg-3">
                            {!! Form::text("validite", "Validité")->type("number")->min(0) !!}
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-lg-3">
                            {!! Form::select("mode_paiement", "Mode de paiement")->options($modes->prepend('--- Mode ---', '')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Réabonner</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
