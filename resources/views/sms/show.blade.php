<div style="z-index: 10000" class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="d-flex justify-content-start align-items-center m-0" style="width: 100%">
                    <i class="pe-7s-chat text-orange header-icon" style="font-size: 1.5rem"></i>
                    <div class="ml-2">
                        A:
                        <strong id="sms_to" class=""></strong>
                    </div>
                    <div class="ml-5 text-muted">
                        <i class="lnr-calendar-full text-muted"></i>
                        <span id="sms_date" CLASS=""></span>
                    </div>
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="sms_body"></div>
                <div class="divider"></div>
                <div class="d-flex justify-content-start">
                    <a id="sms_delete"
                       form-action=""
                       form-method="delete"
                       onclick="submitLinkForm(this)"
                       confirm-message="Supprimer le message ?"
                       href="#" class="confirm btn btn-outline-link text-danger">
                        <i class="lnr-trash"></i> Supprimer
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
