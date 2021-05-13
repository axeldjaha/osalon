@php

    $drilldownStatus = config('LaravelLogger.enableDrillDown');
    $prependUrl = '/activity/log/';

    if (isset($hoverable) && $hoverable === true) {
        $hoverable = true;
    } else {
        $hoverable = false;
    }

    if (Request::is('activity/cleared')) {
        $prependUrl = '/activity/cleared/log/';
    }

@endphp

<div class="table-responsive activity-table">
    <table class="table table-striped table-condensed @if(config('LaravelLogger.enableDrillDown') && $hoverable) table-hover @endif data-table">
        <thead>
        <th>
            <span class="hidden-sm hidden-xs">
                #ID
            </span>
        </th>
        <th>
            <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
            {!! trans('LaravelLogger::laravel-logger.dashboard.labels.time') !!}
        </th>
        <th>
            <i class="fa fa-user-o fa-fw" aria-hidden="true"></i>
            {!! trans('LaravelLogger::laravel-logger.dashboard.labels.user') !!}
        </th>
        <th>
            <i class="fa fa-truck fa-fw" aria-hidden="true"></i>
            <span class="hidden-sm hidden-xs">
                        {!! trans('LaravelLogger::laravel-logger.dashboard.labels.method') !!}
                    </span>
        </th>
        <th>
            <i class="fa fa-map-o fa-fw" aria-hidden="true"></i>
            {!! trans('LaravelLogger::laravel-logger.dashboard.labels.route') !!}
        </th>
        <th>
            <i class="fa fa-map-marker fa-fw" aria-hidden="true"></i>
            {!! trans('LaravelLogger::laravel-logger.dashboard.labels.ipAddress') !!}
        </th>
        @if(Request::is('activity/cleared'))
            <th>
                <i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                {!! trans('LaravelLogger::laravel-logger.dashboard.labels.deleteDate') !!}
            </th>
        @endif
        </thead>
        <tbody>
        @foreach($activities as $activity)
            <tr @if($drilldownStatus && $hoverable) class="clickable-row" data-href="{{ url($prependUrl . $activity->id) }}" data-toggle="tooltip" title="{{trans('LaravelLogger::laravel-logger.tooltips.viewRecord')}}" @endif >
                <td>
                    @if($hoverable)
                        {{ $activity->id }}
                    @else
                        <a href="{{ url($prependUrl . $activity->id) }}">
                            {{ $activity->id }}
                        </a>
                    @endif
                </td>
                <td title="{{ $activity->created_at }}">
                    {{ $activity->timePassed }}
                </td>
                <td>
                        <span class="fsize-1">
                            {{ $activity->userDetails['telephone'] }}
                        </span>
                </td>
                <td>
                    @php
                        switch (strtolower($activity->methodType)) {
                            case 'get':
                                $methodClass = 'info';
                                break;

                            case 'post':
                                $methodClass = 'warning';
                                break;

                            case 'put':
                                $methodClass = 'alternate';
                                break;

                            case 'delete':
                                $methodClass = 'danger';
                                break;

                            default:
                                $methodClass = 'info';
                                break;
                        }
                    @endphp
                    <span class="badge badge-{{ $methodClass }}">
                            {{ $activity->methodType }}
                        </span>
                </td>
                <td>
                    @if($hoverable)
                        {{ substr($activity->route, 0, strpos($activity->route, "?")) }}
                    @else
                        <a href="{{ $activity->route }}">
                            {{$activity->route}}
                        </a>
                    @endif
                </td>
                <td>
                    {{ $activity->ipAddress }}
                </td>
                @if(Request::is('activity/cleared'))
                    <td>
                        {{ $activity->deleted_at }}
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@if(config('LaravelLogger.loggerPaginationEnabled'))
    <div class="text-center">
        <div class="d-flex justify-content-center">
            {!! $activities->render() !!}
        </div>
        <p>
            {!! trans('LaravelLogger::laravel-logger.pagination.countText', ['firstItem' => $activities->firstItem(), 'lastItem' => $activities->lastItem(), 'total' => $activities->total(), 'perPage' => $activities->perPage()]) !!}
        </p>
    </div>
@endif
