@if(count($errors))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        Veuillez renseigner correctement les champs.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@elseif(session()->has('message'))
    <div class="alert {{session()->get('type')}} alert-dismissible fade show" role="alert">
        {{session()->get('message')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
