@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        {!! Form::open()->route("sms.delete.checked")->id("inboxForm")->delete() !!}
        <div class="app-main__inner p-0">
            <div class="app-inner-layout chat-layout">
                <div class="app-inner-layout__header" style="background: rgba(255,255,255,0.45);">
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
                </div>

                @include("layouts.alert")

                <div class="app-inner-layout__wrapper" style="">

                    <div class="app-inner-layout__sidebar card bg-white" style="flex: 3">
                        <div class="app-inner-layout__sidebar-header">
                            <div class="bg-white">
                                <table id="smssent" class="mb-0 table table-hover table-striped" style="margin: 0 !important;">
                                    <thead>
                                    <th class="text-center cursor-pointer" style="width: 15%"></th>
                                    <th class="fit" style="">A</th>
                                    <th style="width: 40%">Message</th>
                                    <th>Date</th>
                                    </thead>
                                    <tbody>
                                    @foreach($smsSent as $sms)
                                        <tr class="cursor-pointer"
                                            data-message="{{$sms->message}}"
                                            data-to="{{$sms->to}}"
                                            data-sent-by="{{$sms->sent_by}}"
                                            data-date="{{date("d/m/Y à H:i", strtotime($sms->created_at))}}"
                                            data-delete="{{route("sms.destroy", $sms)}}" onclick="showSMS(this)" >
                                            <td class="text-center cursor-pointer" style="color: transparent !important;">{{$sms->id}}</td>
                                            <td>
                                                <div class="widget-content p-0 mr-5">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading ellipsis-1">{{$sms->to}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-left">
                                                <div class="ellipsis-1">{{$sms->message}}</div>
                                            </td>
                                            <td class="fit">
                                                <span hidden>{{$sms->created_at}}</span>
                                                <i class="fa fa-calendar-alt opacity-4 mr-2"></i>
                                                {{ date("d/m/Y", strtotime($sms->created_at)) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-between px-4 mt-3">
                                    <button
                                        onclick="return confirm('Supprimer la sélection ?')"
                                        type="submit" class="btn btn-link btn-sm">
                                        <i class="lnr-trash text-danger"></i> Supprimer la sélection
                                    </button>
                                    <nav class="mt-3 d-inline-block" aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li class="page-item"><a href="javascript:void(0);" class="page-link" aria-label="Previous"><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li>
                                            <li class="page-item"><a href="javascript:void(0);" class="page-link">1</a></li>
                                            <li class="page-item active"><a href="javascript:void(0);" class="page-link">2</a></li>
                                            <li class="page-item"><a href="javascript:void(0);" class="page-link">3</a></li>
                                            <li class="page-item"><a href="javascript:void(0);" class="page-link">4</a></li>
                                            <li class="page-item"><a href="javascript:void(0);" class="page-link">5</a></li>
                                            <li class="page-item"><a href="javascript:void(0);" class="page-link" aria-label="Next"><span aria-hidden="true">»</span><span class="sr-only">Next</span></a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="app-inner-layout__content card" style="flex: 2">
                        <div class="table-responsive">
                            <div class="app-inner-layout__top-pane">
                                <div class="pane-left">
                                    <div class="mobile-app-menu-btn">
                                        <button type="button" class="hamburger hamburger--elastic">
                                            <span class="hamburger-box">
                                                <span class="hamburger-inner"></span>
                                            </span>
                                        </button>
                                    </div>

                                </div>
                            </div>
                            <div class="chat-wrapper">
                                <div class="float-right">
                                    <div class="chat-box-wrapper chat-box-wrapper-right pt-0">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2" style="width: 100%">
                                                <div>
                                                    <div class="d-flex align-items-center">
                                                        <i class="lnr-users text-dark header-icon" style="font-size: 1.5rem"></i>
                                                        <div class="ml-2 d-inline-block">
                                                            A:
                                                            <strong id="sms_to" class=""></strong>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <small>Envoyé par :</small>
                                                        <small id="sms_sent_by" class="opacity-8"> </small>
                                                    </div>
                                                </div>
                                                <button style="display: none" id="sms_delete"
                                                        form-action=""
                                                        form-method="delete"
                                                        onclick="submitLinkForm(this)"
                                                        confirm-message="Supprimer le message ?"
                                                        href="#" class="confirm btn btn-sm text-danger">
                                                    <i class="lnr-trash font-size-lg"></i>
                                                </button>
                                            </div>
                                            <div class="divider"></div>
                                            <div id="sms_body" class="chat-box">Cliquez sur un message pour afficher son contenu dans cette zone</div>
                                            <div class="d-flex justify-content-end align-items-center mt-2">
                                                <div class="datewrapper" style="display: none">
                                                    <i class="fa fa-calendar-alt opacity-6 mr-1"></i>
                                                    <small id="sms_date" class="opacity-8"> </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        {!! Form::close() !!}

    </div>

    <script>
        $(function () {
            var table = $('#smssent').DataTable({
                responsive: true,
                "bFilter": false, // show search input
                "paging": false,
                "ordering": true,
                "info": false,
                "searching": true,
                "language": {
                    "lengthMenu": "Afficher _MENU_ liste de contacts par page",
                    "zeroRecords": "Aucun enregistrement",
                    "infoEmpty": "No records available",
                    "search":         "",
                    "paginate": {
                        "first":      "Premier",
                        "last":       "Dernier",
                        "next":       "Suivant",
                        "previous":   "Précédent"
                    },
                },

                'columnDefs': [
                    {
                        'targets': 0,
                        'checkboxes': {'selectRow': true},
                    }
                ],
                //'select': {'style': 'multi'},
                select: {
                    style:    'multi',
                    selector: 'td:first-child'
                },
                'order': []
            });

            $("#smssent_filter").css("display", "none"); // hidden search input

            window.filterSMSTable = function(input)
            {
                $('#smssent').DataTable().search($(input).val()).draw();
                $('#smssent').DataTable().search($(input).val()).draw();
            };

            // Handle form submission event
            $('#inboxForm').on('submit', function(e){
                var form = this;
                var rows_selected = table.column(0).checkboxes.selected();
                $.each(rows_selected, function(index, rowId){
                    $(form).append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'checked[]')
                            .val(rowId)
                    );
                });
            });

            window.showSMS = function (row)
            {
                table.$("tr").removeClass("selected");
                row = $(row);
                $("#sms_to").html(row.attr("data-to"));
                $("#sms_sent_by").html(row.attr("data-sent-by"));
                $("#sms_date").html(row.attr("data-date"));
                $("#sms_body").html(row.attr('data-message').replace(/\n/g, '<br>'));
                $("#sms_delete").attr("form-action", row.attr("data-delete"));
                row.addClass("selected");
                $("#sms_delete").show();
                $("#sms_date").closest("div.datewrapper").show();
            }

        })
    </script>

@endsection
