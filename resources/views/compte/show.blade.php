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

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain text-transform-initial" >
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-link btn-sm mr-sm-3" style="text-transform: initial">
                            <i class="fa fa-chevron-left"></i> Retour
                        </a>
                        <span class="d-inline-block mr-sm-3 text-uppercase">Détails</span>
                    </div>
                    <div class="btn-actions-pane-left d-flex align-items-center ">
                        <a class="btn btn-primary mr-sm-3" href="{{route("abonnement.create", $compte)}}">Réabonner</a>
                        <a class="btn btn-alternate mr-sm-3" href="{{ route("recharge.create", $compte) }}">Recharger SMS</a>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal">
                            <i class="fa fa-envelope"></i> Envoyer SMS
                        </button>
                    </div>
                    <div class="btn-actions-pane-right d-flex align-items-center ">
                        <a form-action="{{route("compte.destroy", $compte)}}"
                           form-method="delete"
                           confirm-message="Supprimer le compte ?"
                           onclick="submitLinkForm(this)"
                           href="#"
                           class="confirm btn btn-danger">Supprimer le compte
                        </a>
                    </div>
                </div>
                <div class="card-body" style="background: #fafafa">
                    <div class="row">
                        <div class="col-sm-3">
                            <table class="table table-striped mb-0">
                                <tbody>
                                <tr>
                                    <td class=""><strong>ID</strong></td>
                                    <td>{{ $compte->id }}</td>
                                </tr>
                                <tr>
                                    <td class=""><strong>Salons</strong></td>
                                    <td>{{ $compte->salons()->count() }}</td>
                                </tr>
                                <tr>
                                    <td class=""><strong>Users</strong></td>
                                    <td>{{ $compte->users()->count() }}</td>
                                </tr>
                                <tr>
                                    <td class=""><strong>SMS</strong></td>
                                    <td>{{ number_format($compte->sms_balance, 0, ",", " ") }}</td>
                                </tr>
                                <tr>
                                    <td class=""><strong>Créé le</strong></td>
                                    <td>{{ date("d/m/Y", strtotime($compte->created_at)) }}</td>
                                </tr>
                                @php($abonnement = $compte->abonnements()->orderBy("id", "desc")->first())
                                <tr>
                                    <td class=""><strong>Abonnement</strong></td>
                                    <td>
                                        @if(\Illuminate\Support\Carbon::parse($abonnement->echeance)->lessThan(\Illuminate\Support\Carbon::today()))
                                            <span class="badge badge-danger badge-pill">Expiré<span>
                                        @else
                                                        <span class="badge badge-success badge-pill">Actif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class=""><strong>Echéance</strong></td>
                                    <td>{{ date("d/m/Y", strtotime($abonnement->echeance)) }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-sm table-responsive-sm">
                            <table id="datatable" class="table table-hover table-striped table-bordered" style="margin-top: 0 !important;">
                                <h5 class="font-weight-bold p-1" style="font-size: .88rem">Salons</h5>
                                <thead class="bg-heavy-rain">
                                <th>Nom</th>
                                <th>Adresse</th>
                                <th>Téléphone</th>
                                <th>Créé le</th>
                                <th>Action</th>
                                </thead>
                                <tbody>
                                @foreach($compte->salons()->orderBy("nom")->get() as $salon)
                                    <tr>
                                        <td>{{ $salon->nom }}</td>
                                        <td>{{ $salon->adresse }}</td>
                                        <td>{{ $salon->telephone }}</td>
                                        <td><span hidden>{{ $salon->created_at }}</span> {{ date("d/m/Y", strtotime($salon->created_at)) }}</td>
                                        <td>
                                            <button form-action="{{route("salon.destroy", $salon)}}"
                                                    form-method="delete"
                                                    confirm-message="Supprimer le salon ?"
                                                    onclick="submitLinkForm(this)"
                                                    class="confirm btn btn-link text-danger btn-sm">Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <table id="datatable" class="table table-hover table-striped table-bordered" style="margin-bottom: 0 !important; margin-top: 0 !important;">
                                <h5 class="font-weight-bold p-1" style="font-size: .88rem">Abonnements</h5>
                                <thead class="bg-success text-white">
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Validité</th>
                                <th>Échéance</th>
                                <th>Action</th>
                                </thead>
                                <tbody>
                                @foreach($compte->abonnements()->orderBy("id", "desc")->get() as $abonnement)
                                    <tr>
                                        <td><span hidden>{{ $abonnement->created_at }}</span> {{ date("d/m/Y", strtotime($abonnement->created_at)) }}</td>
                                        <td>{{ number_format($abonnement->montant, 0, ",", " ") }}</td>
                                        <td>{{ $abonnement->type->validity }}</td>
                                        <td><span hidden>{{ $abonnement->echeance }}</span> {{ date("d/m/Y", strtotime($abonnement->echeance)) }}</td>
                                        <td>
                                            <button form-action="{{route("abonnement.destroy", [$abonnement])}}"
                                               form-method="delete"
                                               confirm-message="Supprimer l'abonnement ?"
                                               onclick="submitLinkForm(this)"
                                               class="confirm btn btn-link text-danger btn-sm">Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection


@section("modal")
    {!! Form::open()->route("compte.sms", [$compte]) !!}
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h6 class="modal-title text-white text-uppercase" id="modalLabel">
                        <i class="fa fa-envelope"></i> Envoi de SMS
                    </h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body table-responsive" style="background-color: #fafafa">
                    <table class="table table-borderless mb-0">
                        <tbody>
                        <tr>
                            <td class="fit"><label for="to" class="col-form-label">A</label></td>
                            <td class="">
                                <select required id="to" name="to" class="form-control @error("to") is-invalid @enderror">
                                    <option value="" @if(old("to") == null) selected disabled @endif>Envoyer à</option>
                                    <option value="tous" @if(old("to") == "tous") selected @endif>Tous</option>
                                    @foreach($compte->users()->orderBy("id")->get() as $user)
                                        <option value="{{ $user->telephone }}" @if(old("to") == $user->telephone) selected @endif>
                                            {{ $user->telephone }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("to") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td class="fit"><label for="message" class="col-form-label">Message</label></td>
                            <td class="">
                                <textarea required name="message" id="message" rows="4" class="form-control @error("message") is-invalid @enderror">{{ old("message") }}</textarea>
                                @error("message") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-paper-plane"></i> Envoyer
                    </button>
                </div>
            </div>

        </div>
    </div>
    {!! Form::close() !!}

    <script>
        $(function (e) {
            $("form#fichierForm").on("submit", function () {
                $("button[class=close]").trigger("click");
            })
        })
    </script>

@endsection
