<h6 class="" aria-label="breadcrumb">
    <ol class="breadcrumb align-items-center">
        <li class="breadcrumb-item">
            <a>
                <i aria-hidden="true" class="fa fa-home"></i>
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route("pressing.index")}}" class="">Liste</a>
        </li>
        <li class="breadcrumb-item active">
            #{{$pressing->id}}
            <strong class="">{{$pressing->nom}}</strong>
        </li>
    </ol>
</h6>
