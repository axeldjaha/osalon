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


            {!! Form::open()->route("sms.envoi")->id("SMSForm") !!}

            <div class="main-card mb-3 card">
                <div class="card-header-tab card-header bg-heavy-rain" style="height: initial">
                    <div class="card-header-title font-size-lg font-weight-normal">
                        <span class="d-inline-block mr-sm-3">Envoi</span>
                    </div>
                    <div class="btn-actions-pane-right d-flex align-items-center ">
                    </div>
                </div>
                <div class="card-body" style="background: #fafafa">
                    <div class="row">
                        <div class="col-sm-4">
                            <table id="checkboxtableUnordered" class="table table-hover table-striped" style="margin-top: 0 !important;">
                                <thead class="bg-heavy-rainx text-primary">
                                <th class="text-center cursor-pointer" style="width: 40px"></th>
                                <th class="">Envoyer à</th>
                                </thead>
                                <tbody>
                                @foreach($groupes as $groupe)
                                    <tr role="row" class="cursor-pointer">
                                        <td style="color: transparent">{{ $groupe->id }} </td>
                                        <td style="padding: .55rem">{{ $groupe->intitule }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="col-sm-5">
                            <table class="table table-borderless">
                                <tbody>
                                <tr>
                                    <td class="p-0" style="">
                                        <textarea required name="message" id="message" rows="5" class="form-control @error("message") is-invalid @enderror" placeholder="Message...">{{old("message")}}</textarea>
                                        @error("message") <div class="invalid-feedback">{{$message}}</div> @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="text-muted text-center">
                                            <small>
                                                <span id="charCounter">0</span>
                                                <span> / </span>
                                                <span id="charLimit">480</span>
                                                <span>|</span>
                                                Paquets:
                                                <span id="partCounter">0</span>
                                            </small>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-sm-3">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                <tr>
                                    <td class="p-0" style="vertical-align: top">
                                        <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-paper-plane"></i> &nbsp;Envoyer</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>

                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

    <script>
        $(document).ready(function () {

            // Handle form submission event
            $('form#SMSForm').on('submit', function(e){
                var form = this;

                var rows_selected = checkboxtableUnordered.column(0).checkboxes.selected();

                if(rows_selected.length === 0)
                {
                    event.preventDefault();
                    $.alert({
                        backgroundDismiss: true,
                        title: 'Information',
                        //icon: 'fa fa-info-circle',
                        content: "Veuillez cocher les destinataires.",
                    });
                }

                // Iterate over all selected checkboxes
                $.each(rows_selected, function(index, rowId){
                    // Create a hidden element
                    $(form).append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'groupes[]')
                            .val(rowId)
                    );
                });
            });

        });
    </script>

    <script src="{{asset("js/sms-counter.js")}}"></script>
    <script src="{{asset("js/sms.js")}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script src="{{asset("js/numeral-config.js")}}"></script>

@endsection
