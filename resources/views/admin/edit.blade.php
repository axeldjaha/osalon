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

            {!!Form::open()->fill($user)->route("admin.update", [$user])->put()->multipart()!!}

            <div class="main-card mb-3 card">
                <div class="card-body col-lg-8">
                    <div class="card-header pt-3 pb-3 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="widget-content-left mr-3">
                                <img width="50" height="50" style="object-fit: cover;" class="rounded-circle"
                                     src="{{$user->avatar != null ? asset('storage/avatars/'.$user->avatar) : asset("images/profile.png") }}" alt="">
                            </div>
                            <div class="widget-content-left">
                                <div class="widget-heading">{{$user->name}}</div>
                                <div class="widget-subheading text-muted" style="text-transform: initial">{{$user->email}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="main-card mb-4">
                        <div class="card-header text-transform-initial d-block pt-2 pb-2" style="height: auto">
                            <h6 class="font-weight-bold">Accès aux modules</h6>
                            <span class="font-weight-normal text-muted">Cochez les modules auxquels l'utilisateur peut accéder</span>
                        </div>
                        <div class="card-body p-0">
                            <ul class="todo-list-wrapper list-group list-group-flush">
                                @foreach($permissions as $permission)
                                    <li class="list-group-item cursor-pointer" onclick="allowPermission(this)">
                                        <div class="todo-indicator btn-gradient-success"></div>
                                        <div class="widget-content p-0">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left mr-2">
                                                    <div class="custom-checkbox custom-control">
                                                        <input type="checkbox" name="permissions[]"
                                                               value="{{$permission->id}}"
                                                               id="permission{{$permission->id}}"
                                                               @if($user->hasPermissionTo($permission)) checked @endif
                                                               class="custom-control-input cursor-pointer">
                                                        <label class="custom-control-label" for="permission{{$permission->id}}">&nbsp;</label>
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <div class="widget-heading font-size-lg" style="font-weight: normal; opacity: 1; font-size: 1rem !important;">
                                                        {{$permission->name}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    @if(!$user->hasRole('super-admin'))
                        {!!Form::submit("Enregistrer les modifications", 'primary', 'lg')!!}
                        <a form-action="{{route('admin.destroy', $user)}}"
                           confirm-message="Supprimer l'utilisateur ?"
                           form-method="delete"
                           onclick="submitLinkForm(this)"
                           href="#"
                           class="form-link confirm btn btn-link text-danger float-right">
                            <i class="lnr-trash"></i> Supprimer
                        </a>
                    @endif

                </div>
            </div>

            {!!Form::close()!!}

        </div>
    </div>

    <script>
        $(function (e) {

            window.allowPermission = function (field) {
                console.log("parent");
                $(field).find('input[type=checkbox]').prop('checked', !$(field).find('input[type=checkbox]').is(':checked'))
            };

            $('input[type="checkbox"]').click(function(e) {
                e.preventDefault();
                e.stopPropagation();
            });
        })
    </script>

@endsection
