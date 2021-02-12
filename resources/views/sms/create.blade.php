@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="pe-7s-chat text-orange"></i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Envoi SMS</span>
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
                                            <a href="{{route("sms.index")}}" class="">Boîte d'envoi</a>
                                        </li>
                                    </ol>
                                </h6>
                            </div>
                        </div>
                    </div>

                    @include("sms.actions")

                </div>
            </div>

            @include("layouts.alert")

            {!! Form::open()->route("sms.store")->id("smsForm") !!}

            <div class="main-card card">
                <div class="card-body col-lg-11 mx-auto">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="">
                                <div class="form-group">
                                    <div class="custom-checkbox custom-control">
                                        <input type="checkbox" name="unique" onchange="switchToSingle(this)" id="telephoneSwitcher" class="custom-control-input">
                                        <label class="custom-control-label" for="telephoneSwitcher">Envoyer à un numéro</label>
                                    </div>
                                </div>

                                <div id="phoneInputWrapper" style="display: none">
                                    <div class="form-group">
                                        <input type="text" name="telephone" class="form-control @error("telephone") is-invalid @enderror" placeholder="Numéro de téléphone">
                                        @error("telephone") <div class="invalid-feedback">{{$message}}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <textarea name="message" id="message" rows="5" class="form-control @error("message") is-invalid @enderror">{{old("message")}}</textarea>
                                    @error("message") <div class="invalid-feedback">{{$message}}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <div class="alert alert-light bg-light border-0 p-2">
                                        <small style="font-size: small">
                                            <span id="charCounter">0</span>
                                            <span> / </span>
                                            <span id="charLimit">480</span>
                                            <span>|</span>
                                            Paquets:
                                            <span id="partCounter">0</span>
                                            <span> | </span>
                                            Destinataires:
                                            <span id="recipientCounter">0</span>
                                            <span> | </span>
                                            Volume SMS:
                                            <span id="smsVolumeCounter" style="display: inline; width: 5%">0</span>
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg">Envoyer</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="d-flex justify-content-end align-items-center">
                                <div class="d-flex">
                                    <div class="custom-radio custom-control mr-sm-4">
                                        <input checked class="custom-control-input form-check-input cursor-pointer"
                                               type="radio"
                                               onchange="loadRecipients('{{route("sms.recipients.prospects")}}')"
                                               id="prospects" name="filter" value="prospects">
                                        <label class="custom-control-label form-check-label cursor-pointer" for="prospects">Prospects</label>
                                    </div>
                                    <div class="custom-radio custom-control mr-sm-4">
                                        <input class="custom-control-input form-check-input cursor-pointer"
                                               type="radio"
                                               onchange="loadRecipients('{{route("sms.recipients.clients")}}')"
                                               id="clients" name="filter" value="clients">
                                        <label class="custom-control-label form-check-label cursor-pointer" for="clients">Clients</label>
                                    </div>
                                </div>
                            </div>

                            <table id="recipients" class="mb-0 table table-hover table-striped">
                                <thead>
                                <tr>
                                    <th style="width: 15%" class="text-center"></th>
                                    <th>Envoyer à</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

    {{ ScriptVariables::render() }}

    <script>
        $(function () {
            recipients = $('#recipients').DataTable({
                responsive: true,
                "bFilter": false, // show search input
                "paging": false,
                "ordering": true,
                "info": false,
                "searching": true,
                "processing": true,
                "language": {
                    "lengthMenu": "Afficher _MENU_ liste de contacts par page",
                    "zeroRecords": "Aucun enregistrement",
                    "infoEmpty": "No records available",
                    "search":         "",
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw text-primary"></i><span class="sr-only">Loading...</span>',
                    "paginate": {
                        "first":      "Premier",
                        "last":       "Dernier",
                        "next":       "Suivant",
                        "previous":   "Précédent"
                    },
                },

                'select': {'style': 'multi'},
                'order': [],

                ajax: window.config.defaultUrl,
                "columns": [
                    { "data": "id" },
                    { "data": "nom" },
                ],
                'columnDefs': [
                    {
                        'targets': 0,
                        'checkboxes': {'selectRow': true},
                    },
                ],
                'select': {'style': 'multi'},
                'order': []
            });

            $("#recipients_filter").css("display", "none"); // hidden search input
        })
    </script>

    <script>
        $(function () {


            window.filterSMSTable = function(input)
            {
                recipients.search($(input).val()).draw();
            };

            // Handle form submission event
            $('#smsForm').on('submit', function(e){
                var form = this;
                var rows_selected = recipients.column(0).checkboxes.selected();
                $.each(rows_selected, function(index, rowId){
                    $(form).append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'recipients[]')
                            .val(rowId)
                    );
                });
            });

            // Handle click on all checkbox
            $('#recipients thead').on('click', 'input[type="checkbox"]', function(e){
                switch ($("input[name=filter]:checked").val()) {
                    case "prospects":
                        setRecipients(window.config.prospects);
                        break;
                    case "clients":
                        setRecipients(window.config.clients);
                        break;
                    case "pressings":
                        setRecipients(window.config.pressings);
                        break;
                }
            });

            // Handle click on checkbox
            $('#recipients tbody').on('click', 'input[type="checkbox"]', function(e){
                var row = $(this).closest('tr');
                onRowClick(row);
            });

            $('#recipients tbody').on('click', 'tr', function(e){
                onRowClick($(this));
            });
        })
    </script>

    <script src="{{asset("js/sms-counter.js")}}"></script>
    <script src="{{asset("js/sms.js")}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script src="{{asset("js/numeral-config.js")}}"></script>

@endsection
