@extends("layouts.app")

@section("content")

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="lnr-user text-orange">
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
                                            <a href="{{route("user.index")}}" class="">Liste</a>
                                        </li>
                                    </ol>
                                </h6>
                            </div>
                        </div>
                    </div>

                    @include("user.actions")
                </div>
            </div>

            @include("layouts.alert")

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <table id="datatable" class="mb-0 table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="">#</th>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <th class="fit" scope="row">{{$user->id}}</th>
                                <td>
                                    <div class="d-flex justify-content-between">
                                        <span>{{$user->name}}</span>
                                        @if($user->activated == false)
                                            <span class="fa fa-lock text-danger"></span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{$user->telephone}}</td>
                                <td>{{$user->email}}</td>
                                <td>
                                    @if($user->activated == true)<div class="badge badge-pill badge-success">Activé</div>
                                    @else<div class="badge badge-pill badge-danger">Désactivé</div>
                                    @endif
                                </td>
                                <td class="fit">
                                    <a href="{{route("user.show", $user)}}" tabindex="0" class="btn btn-link">
                                        <span class="btn-icon-wrapper pr-2"><i class="dropdown-icon pe-7s-edit"></i></span>
                                        <span>Détail</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th style="min-width: 1%">#</th>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
