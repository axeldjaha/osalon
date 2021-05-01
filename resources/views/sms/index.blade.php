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

            {!! Form::open()->delete()->route("sms.destroy")->id("smsForm") !!}

            <div class="main-card mb-3 card">
                <div hidden class="card-header-tab card-header bg-heavy-rain">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">Historique d'envoi</span>
                        <div class="text-transform-initial mr-sm-3">
                            <span class="badge badge-primary">{{ \App\Sms::count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive-sm" style="background: #fafafa">
                    <table id="checkboxtableUnordered" class="table table-hover table-striped" style="margin-top: 0 !important; margin-bottom: 0 !important;">
                        <thead class="bg-heavy-rain">
                        <th class="text-center" style="width: 40px"></th>
                        <th>A</th>
                        <th style="width: 40%">Message</th>
                        <th class="fit">Envoyé par</th>
                        <th class="fit">Date</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        @php($i = 0)
                        @foreach($smses as $sms)
                            <tr>
                                <td style="color: transparent !important;">{{ $sms->id }}</td>
                                <td>{{ $sms->to }}</td>
                                <td style="background: @if($i % 2 == 0) rgba(213,218,235,0.4) @else rgba(213,218,235,0.2) @endif"><div>{{ $sms->message }}</div></td>
                                <td class="fit">{{ $sms->user }}</td>
                                <td class="fit">{{ \Illuminate\Support\Carbon::parse($sms->created_at)->locale('fr')->isoFormat('Do MMM YYYY à HH:m') }}</td>
                                <td class="">
                                    <button type="button"
                                            class="btn btn-link text-danger"
                                            onclick="deleteSMS('{{ $sms->id }}')">
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                            @php($i++)
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="mr-sm-3">
                            Total:
                            <span class="badge badge-primary">{{ number_format(\App\Sms::count(), 0, ",", " ") }}</span>
                        </div>

                        <span class="mr-sm-2">Pour la sélection:</span>
                        @if(count($smses))
                            <button type="submit" class="btn btn-link text-danger mr-sm-3">
                                <i class="fa fa-trash-alt mr-sm-1"></i>
                                Supprimer
                            </button>
                        @else
                            <a class="btn disabled opacity-5">
                                <i class="fa fa-trash-alt mr-sm-1"></i>
                                Supprimer
                            </a>
                        @endif
                    </div>
                    {{ $smses->links() }}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

    <script>
        $(document).ready(function () {

            var form = $("form#smsForm");

            window.deleteSMS = function(smsId){
                event.stopPropagation();

                if(confirm("Supprimer le SMS ?"))
                {
                    $(form).unbind();
                    $(form).append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'smses[]')
                            .val(smsId)
                    ).submit();
                }
            };

        });
    </script>

    <script>
        $(document).ready(function () {

            // Handle form submission event
            $('form#smsForm').on('submit', function(e){

                event.preventDefault();

                var form = this;

                var rows_selected = checkboxtableUnordered.column(0).checkboxes.selected();

                if(rows_selected.length === 0)
                {
                    alert("Aucune ligne n'a été cochée.")
                }
                else if(confirm("Supprimer la sélection ?"))
                {
                    $.each(rows_selected, function(index, rowId){
                        $(form).append(
                            $('<input>')
                                .attr('type', 'hidden')
                                .attr('name', 'smses[]')
                                .val(rowId)
                        );
                    });
                    $(form).unbind();
                    $(form).submit();
                }
            });

        });
    </script>

@endsection

