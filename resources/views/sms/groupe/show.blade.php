@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-mail-bulk text-orange">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Envoi SMS</span>
                            </div>
                            <div class="page-title-subheading">Cette section est réservée à l'envoi de SMS</div>
                        </div>
                    </div>

                    @include("sms.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="row">
                <div class="col-sm-8">
                    <div class="main-card mb-3 card">
                        <div class="card-header-tab card-header bg-heavy-rain">
                            <div class="card-header-title font-size-lg font-weight-normal">
                                <a href="{{ route("sms.fichier.index") }}" class="btn btn-link btn-sm mr-sm-3" style="text-transform: initial">
                                    <i class="fa fa-chevron-left"></i> Retour
                                </a>
                                <span class="d-inline-block mr-sm-3">{{ $groupe->intitule }}</span>
                                <span class="badge badge-primary">{{ number_format($groupe->contacts->count(), 0, ",", " ") }}</span>
                            </div>
                            <div class="btn-actions-pane-right d-flex align-items-center ">
                                <input type="search" oninput="filterTable(this, 'datatable')" class="form-control form-control-sm" placeholder="Chercher dans la liste">
                            </div>
                        </div>
                        <div class="card-body p-0 table-responsive" style="background: #fafafa">
                            <table id="datatable" class="table table-hover table-striped" style="margin-top: 0 !important; margin-bottom: 0 !important;">
                                <thead class="">
                                <th>#</th>
                                <th>Nom</th>
                                <th>Téléphone</th>
                                <th>Actions</th>
                                </thead>
                                <tbody>
                                @php($index = 1)
                                @foreach($contacts as $contact)
                                    <tr>
                                        <td>{{ $index++ }}</td>
                                        <td style="padding: .55rem">{{ $contact->nom }}</td>
                                        <td style="padding: .55rem">{{ $contact->telephone }}</td>
                                        <td>
                                            <a form-action ="{{ route("sms.contact.destroy", $contact) }}"
                                               form-method="delete"
                                               confirm-message="Supprimer le contact ?"
                                               onclick="submitLinkForm(this)"
                                               href="#"
                                               class="confirm btn btn-link text-primary btn-sm">
                                                <i class="fa fa-trash-alt text-danger"></i> Supprimer
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="main-card mb-3 card">
                        <div class="card-header-tab card-header bg-heavy-rain">
                            <div class="card-header-title font-size-lg font-weight-normal">
                                <span class="d-inline-block mr-sm-3">Importer/Ajouter contacts</span>
                            </div>
                        </div>
                        <div class="card-body" style="background: #fafafa">

                            {!! Form::open()->route("sms.contact.store")->id("smsForm") !!}

                            <div class="">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a data-toggle="tab" href="#tab-eg10-0" class="nav-link active">
                                            <i class="fa fa-file-upload mr-sm-2"></i>
                                            Importer
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a data-toggle="tab" href="#tab-eg10-1" class="nav-link">
                                            <i class="fa fa-address-book mr-sm-2"></i>
                                            Ajouter contact
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab-eg10-0" role="tabpanel">
                                        <button type="button" class="btn btn-sm btn-success d-flex align-items-center" data-toggle="modal" data-target="#modal">
                                            Importer liste de contacts
                                        </button>
                                    </div>
                                    <div class="tab-pane" id="tab-eg10-1" role="tabpanel">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                            <tr>
                                                <td class="pl-0"><label for="pdf" class="col-form-label">Nom</label></td>
                                                <td class="pr-0">
                                                    <input type="text" id="nom" name="nom" class="form-control form-control-sm @error("nom") is-invalid @enderror" style="width: initial">
                                                    @error("nom") <div class="invalid-feedback">{{$message}}</div> @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pl-0"><label for="pdf" class="col-form-label required">Téléphone</label></td>
                                                <td class="pr-0">
                                                    <input required type="text" id="telephone" name="telephone" class="form-control form-control-sm @error("telephone") is-invalid @enderror" style="width: initial">
                                                    @error("telephone") <div class="invalid-feedback">{{$message}}</div> @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pl-0 fit"><label for="groupe" class="col-form-label required">Ajouter dans</label></td>
                                                <td class="pr-0">
                                                    <select required class="form-control form-control-sm @error("groupe") is-invalid @enderror" id="groupe" name="groupe" style="">
                                                        <option value="" {{old("groupe") == null ? "selected" : ""}} disabled>-- Liste de contact --</option>
                                                        @foreach($groupes as $groupe)
                                                            <option value="{{ $groupe->id }}" @if(request()->old("groupe") == $groupe->id) selected @endif>
                                                                {{ $groupe->intitule }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error("groupe") <div class="invalid-feedback">{{$message}}</div> @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=""></td>
                                                <td style="">
                                                    <button type="submit" class="btn btn-primary btn-lg">
                                                        Ajouter
                                                    </button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section("modal")
    {!! Form::open()->route("sms.fichier.importer")->id("fichierForm")->multipart() !!}
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-heavy-rain">
                    <h6 class="modal-title text-black text-uppercase" id="modalLabel">
                        <i class="fa fa-file-excel text-primary"></i> Exemple de fichier Excel à importer
                    </h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body table-responsive" style="background-color: #fafafa">

                    <p>
                        La colonne <strong class="text-danger">Nom</strong> est facultative.
                    </p>
                    <table class="table">
                        <tbody>
                        <tr>
                            <td class="p-0">
                                <img class="img-fluid" src="{{ asset("images/import-sms-groupes.png") }}" alt="Exemple fichier excel">
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <table class="table table-striped mb-0">
                        <tbody>
                        <tr>
                            <td class="fit"><label for="pdf" class="col-form-label">Nom de la liste</label></td>
                            <td style="width: 20% !important;">
                                <input required type="text" id="intitule" name="intitule" class="form-control @error("intitule") is-invalid @enderror">
                                @error("intitule") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td class="fit"><label for="pdf" class="col-form-label">Fichier</label></td>
                            <td style="width: 20% !important;">
                                <input required type="file" id="fichier" name="fichier" class="form-control-file @error("fichier") is-invalid @enderror"
                                       accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                @error("fichier") <div class="invalid-feedback">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td class="fit"></td>
                            <td style="width: 20% !important;">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Importer
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
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
