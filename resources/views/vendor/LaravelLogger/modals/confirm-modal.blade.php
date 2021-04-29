@php
    if (!isset($actionBtnIcon)) {
      $actionBtnIcon = null;
    } else {
      $actionBtnIcon = $actionBtnIcon . ' fa-fw';
    }
    if (!isset($modalClass)) {
      $modalClass = null;
    }
    if (!isset($btnSubmitText)) {
      $btnSubmitText = trans('LaravelLogger::laravel-logger.modals.shared.btnConfirm');
    }
@endphp
@section("modal")
    <div class="modal fade modal-{{$modalClass}}" id="{{$formTrigger}}" role="dialog" aria-labelledby="{{$formTrigger}}Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header {{$modalClass}}">
                    <h5 class="modal-title">
                        Confirm
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline pull-left btn-flat" data-dismiss="modal">
                        <i class="fa fa-fw fa-close" aria-hidden="true"></i>
                        {{ trans('LaravelLogger::laravel-logger.modals.shared.btnCancel') }}
                    </button>

                    <button form-action="{{route("destroy-activity")}}"
                            form-method="delete"
                            confirm-message="Supprimer l'abonnement ?"
                            onclick="submitLinkForm(this)"
                            class="btn btn-danger pull-right btn-flat"> Confirmer
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

