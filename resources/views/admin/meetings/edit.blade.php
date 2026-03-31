@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.meeting.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.meetings.update", [$meeting->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="attendees">{{ trans('cruds.meeting.fields.attendees') }}</label>
                <input type="text" id="attendees" name="attendees"
                    class="form-control {{ $errors->has('attendees') ? 'is-invalid' : '' }}"
                    value="{{ old('attendees', isset($meeting) ? $meeting->attendees : '') }}">
                @if($errors->has('attendees'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attendees') }}
                    </div>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.meeting.fields.attendees_helper') }}
                </p>
            </div>
            <div class="mb-3">
                <label for="start_time">{{ trans('cruds.meeting.fields.start_time') }}</label>
                <input type="text" id="start_time" name="start_time"
                    class="form-control flatpickr-datetime {{ $errors->has('start_time') ? 'is-invalid' : '' }}"
                    value="{{ old('start_time', isset($meeting) ? $meeting->start_time : '') }}">
                @if($errors->has('start_time'))
                    <div class="invalid-feedback">
                        {{ $errors->first('start_time') }}
                    </div>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.meeting.fields.start_time_helper') }}
                </p>
            </div>
            <div class="mb-3">
                <label for="end_time">{{ trans('cruds.meeting.fields.end_time') }}</label>
                <input type="text" id="end_time" name="end_time"
                    class="form-control flatpickr-datetime {{ $errors->has('end_time') ? 'is-invalid' : '' }}"
                    value="{{ old('end_time', isset($meeting) ? $meeting->end_time : '') }}">
                @if($errors->has('end_time'))
                    <div class="invalid-feedback">
                        {{ $errors->first('end_time') }}
                    </div>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.meeting.fields.end_time_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-success" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection
