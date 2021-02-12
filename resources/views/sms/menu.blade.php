<div class="app-inner-layout__sidebar card" style="">
    <ul class="nav flex-column">
        <li class="pt-4 pl-3 pr-3 pb-3 nav-item">
            <a href="{{route("sms.create")}}" class="btn-pill btn btn-success btn-hover-shine btn-sm">
                <i class="fa fa-edit"></i> Nouveau Message</a>
        </li>
        <li class="nav-item">
            <a href="{{route("sms.index")}}" class="nav-link">
                <i class="nav-link-icon pe-7s-chat"> </i><span>Boîte d'envoi</span>
                <div class="ml-3">({{$all}})</div>
            </a>
        </li>
    </ul>
</div>
