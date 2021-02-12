@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-users text-orange">
                            </i>
                        </div>
                        <div>
                            <div class="page-title-head center-elem">
                                <span class="d-inline-block">Utilisateurs</span>
                            </div>
                            <div class="page-title-subheading opacity-10">
                                <h6 class="" aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a>
                                                <i aria-hidden="true" class="fa fa-home"></i>
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="{{route("admin.index")}}" class="">Liste</a>
                                        </li>
                                    </ol>
                                </h6>
                            </div>
                        </div>
                    </div>

                    @include("admin.actions")

                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card col-lg-8">
                <table id="datatable" class="table" style="margin: 0 !important;">
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="">
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left mr-3">
                                            <img width="50" height="50" style="object-fit: cover;" class="rounded-circle"
                                                 src="{{$user->avatar != null ? asset('storage/avatars/'.$user->avatar) : asset("images/profile.png") }}" alt="">
                                        </div>
                                        <div class="widget-content-left">
                                            <div class="widget-heading">{{$user->name}}</div>
                                            <div class="widget-subheading">{{$user->email}}</div>
                                        </div>

                                        <div class="widget-content-right">
                                            @if($user->id != auth()->id())
                                                <a href="{{route('admin.edit', $user)}}" class="btn btn-outline-primary font-size-sm">
                                                    <span class="fa fa-key"></span> Accès aux modules
                                                </a>
                                            @else
                                                <a class="btn btn-outline-light disabled font-size-sm">
                                                    <span class="fa fa-key"></span> Accès aux modules
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>

@endsection
