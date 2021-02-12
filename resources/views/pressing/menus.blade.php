
<ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
    <li class="nav-item">
        <a role="tab" class="nav-link {{$tab == "info" ? "active" : ""}}" href="{{route("pressing.show", $pressing)}}">
            <span class="btn-icon-wrapper pr-2 opacity-7">
                <i class="fa fa-tachometer-alt fa-w-20"></i>
            </span>
            <span>Info</span>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link {{$tab == "users" ? "active" : ""}}" href="{{route("pressing.users", $pressing)}}">
            <span class="btn-icon-wrapper pr-2 opacity-7">
                <i class="fa fa-users fa-w-20"></i>
            </span>
            <span>Utilisateurs</span>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link {{$tab == "services" ? "active" : ""}}" href="{{route("service.index", $pressing)}}">
            <span class="btn-icon-wrapper pr-2 opacity-7">
                <i class="fa fa-gift fa-w-20"></i>
            </span>
            <span>Services</span>
        </a>
    </li>
</ul>
