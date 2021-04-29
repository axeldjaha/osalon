{!! Form::open()->route("clear-activity") !!}
{!! Form::hidden('_method', 'DELETE') !!}
<button type="button" class="dropdown-item"
        data-toggle="modal"
        data-target="#confirmDelete"
        data-title="{{ trans('LaravelLogger::laravel-logger.modals.clearLog.title') }}"
        data-message="{{ trans('LaravelLogger::laravel-logger.modals.clearLog.message') }}">
    <i class="fa fa-fw fa-trash" aria-hidden="true"></i>
    {{ trans('LaravelLogger::laravel-logger.dashboard.menu.clear') }}
</button>
{!! Form::close() !!}
