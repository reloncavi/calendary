@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.event.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.events.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name">{{ trans('cruds.event.fields.name') }}</label>
                <input type="text" id="name" name="name"
                    class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                    value="{{ old('name', isset($event) ? $event->name : '') }}">
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.event.fields.name_helper') }}
                </p>
            </div>
            <div class="mb-3">
                <label for="start_time">{{ trans('cruds.event.fields.start_time') }}</label>
                <input type="text" id="start_time" name="start_time"
                    class="form-control flatpickr-datetime {{ $errors->has('start_time') ? 'is-invalid' : '' }}"
                    value="{{ old('start_time', isset($event) ? $event->start_time : '') }}">
                @if($errors->has('start_time'))
                    <div class="invalid-feedback">
                        {{ $errors->first('start_time') }}
                    </div>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.event.fields.start_time_helper') }}
                </p>
            </div>
            <div class="mb-3">
                <label for="end_time">{{ trans('cruds.event.fields.end_time') }}</label>
                <input type="text" id="end_time" name="end_time"
                    class="form-control flatpickr-datetime {{ $errors->has('end_time') ? 'is-invalid' : '' }}"
                    value="{{ old('end_time', isset($event) ? $event->end_time : '') }}">
                @if($errors->has('end_time'))
                    <div class="invalid-feedback">
                        {{ $errors->first('end_time') }}
                    </div>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.event.fields.end_time_helper') }}
                </p>
            </div>
            <div class="mb-3">
                <label for="venue">{{ trans('cruds.event.fields.venue') }}</label>
                <select name="venue_id" id="venue"
                    class="form-control select2 {{ $errors->has('venue_id') ? 'is-invalid' : '' }}">
                    @foreach($venues as $id => $venue)
                        <option value="{{ $id }}" {{ (isset($event) && $event->venue ? $event->venue->id : old('venue_id')) == $id ? 'selected' : '' }}>{{ $venue }}</option>
                    @endforeach
                </select>
                @if($errors->has('venue_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('venue_id') }}
                    </div>
                @endif
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>
@endsection
