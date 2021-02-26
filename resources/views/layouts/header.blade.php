@php($user = auth()->user())

<div class="app-header bg-heavy-rain">
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
            <ul class="header-megamenu nav">
                <li class="btn-group nav-item">
                    @if($user->can("Comptes utilisateurs"))
                        <a href="{{ route("user.index") }}" class="nav-link mr-2">
                            <i class="nav-link-icon fa fa-users font-size-xlg"></i>
                            &nbsp;Comptes utilisateurs
                        </a>
                    @else
                        <a class="nav-link mr-2 disabled opacity-3">
                            <i class="nav-link-icon fa fa-users font-size-xlg"></i>
                            &nbsp;Comptes utilisateurs
                        </a>
                    @endif
                </li>
            </ul>
        </div>

        <div class="app-header-right">
            <div class="header-btn-lg pr-0">
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="btn-group show">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="p-0 btn">
                                    @if($user->photo != null && file_exists(storage_path("app/public/users/$user->photo")))
                                        <img width="42" height="42" class="rounded-circle" src="{{asset("storage/users/$user->photo")}}" alt="Photo de profile">
                                    @else
                                        <i class="fa fa-user text-primary font-size-xlg"></i>
                                    @endif
                                    <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                </a>
                                <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right" style="position: absolute; transform: translate3d(-292px, -3px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="top-end">
                                    <div class="dropdown-menu-header">
                                        <div class="dropdown-menu-header-inner bg-info" style="">
                                            <div class="menu-header-image opacity-2" style="background-image: url('{{ asset("assets/images/dropdown-header/bg-profile.jpg") }}');"></div>
                                            <div class="menu-header-content text-left">
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-3">
                                                            @if($user->photo != null && file_exists(storage_path("app/public/users/$user->photo")))
                                                                <img width="42" height="42" class="rounded-circle" src="{{asset("storage/users/$user->photo")}}" alt="Photo de profile">
                                                            @else
                                                                <i class="fa fa-user text-white font-size-xlg"></i>
                                                            @endif
                                                        </div>
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading">{{ $user->name }}</div>
                                                            <div class="widget-subheading opacity-8">{{ $user->email }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="scroll-area-xs" style="height: initial;">
                                        <div class="scrollbar-container ps ps--active-y">
                                            <ul class="nav flex-column">
                                                <li class="nav-item-header nav-item">Mon profile</li>
                                                <li class="nav-item">
                                                    <a href="{{ route("account.infos") }}" class="nav-link">
                                                        <i class="nav-link-icon fa fa-user-edit text-primary"></i>
                                                        <span>Modifier mes informations</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route("account.access") }}" class="nav-link">
                                                        <i class="nav-link-icon fa fa-key text-primary"></i>
                                                        <span>Modifier mes accès</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content-left  ml-3 header-user-info">
                            <div class="widget-heading" style="font-weight: 500 !important;">{{$user->name}}</div>
                            <div hidden class="widget-subheading"> {{ $user->email }} </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="header-btn-lg">
                <button title="Déconnexion" class="btn btn-sm"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out-alt text-danger"></i> Déconnexion
                </button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
