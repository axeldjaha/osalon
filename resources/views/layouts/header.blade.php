<div class="app-header header-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
            <span>
                <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                    <span class="btn-icon-wrapper">
                        <i class="fa fa-ellipsis-v fa-w-6"></i>
                    </span>
                </button>
            </span>
    </div>

    <div class="app-header__content">
        <div class="app-header-left">
            <div class="search-wrapper">
                <div class="input-holder">
                    <input type="text" class="search-input" placeholder="Recherche">
                    <button class="search-icon"><span></span></button>
                </div>
                <button class="close"></button>
            </div>
            <ul class="header-megamenu nav">
                @can("Users")
                    <li class="dropdown nav-item">
                        <a class="nav-link ml-2" href="{{route("admin.index")}}">
                            <i class="nav-link-icon fa fa-users"></i>
                            Admins
                        </a>
                    </li>
                @endcan
            </ul>
        </div>

        <div class="app-header-right">

            <div class="header-btn-lg pr-0">
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="btn-group">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                    @if(auth()->user()->avatar != null)
                                        <img width="42" height="42" class="rounded-circle" src="{{asset('storage/avatars/'.auth()->user()->avatar)}}" alt="">
                                    @else
                                        <img width="42" class="rounded-circle" src="{{asset("images/profile.png")}}" alt="">
                                    @endif
                                    <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                </a>
                                <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-menu-header mb-0">
                                        <div class="dropdown-menu-header-inner text-dark">
                                            <div class="menu-header-image opacity-2"></div>
                                            <div class="menu-header-content text-left">
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-3">
                                                            @if(auth()->user()->avatar != null)
                                                                <img width="42" height="42" class="rounded-circle" src="{{asset('storage/avatars/'.auth()->user()->avatar)}}" alt="">
                                                            @else
                                                                <img width="42" class="rounded-circle" src="{{asset("images/profile.png")}}" alt="">
                                                            @endif
                                                        </div>
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading">{{auth()->user()->name}}</div>
                                                            <div class="widget-subheading opacity-8">{{auth()->user()->email}}
                                                            </div>
                                                        </div>
                                                        <div class="widget-content-right mr-2">
                                                            <button
                                                                form-action="{{route("logout")}}"
                                                                form-method="post"
                                                                onclick="submitLinkForm(this)"
                                                                class="btn-pill btn btn-danger">Déconnexion</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="scroll-area-xs" style="height: inherit">
                                        <div class="scrollbar-container ps">
                                            <ul class="nav flex-column">
                                                <li class="nav-item">
                                                    <a href="{{route("profil.infos")}}" class="nav-link">
                                                        <i class="fa fa-user-edit mr-sm-1"></i>
                                                        Modifier mes informations
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{route("profil.acces")}}" class="nav-link">
                                                        <i class="fa fa-key mr-sm-2"></i>
                                                        Modifier mes accès
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content-left  ml-3 header-user-info">
                            <div class="widget-heading">
                                {{auth()->user()->name}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
