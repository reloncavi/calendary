@extends('layouts.admin')

@section('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.systemCalendar') }}
    </div>

    <div class="card-body">
        <form action="{{ route('admin.systemCalendar') }}" method="GET" class="mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-auto">
                    <label class="form-label">{{ trans('cruds.venue.title_singular') }}</label>
                    <select name="venue_id" class="form-select">
                        <option value="">-- {{ trans('global.all') }} --</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}" {{ request('venue_id') == $venue->id ? 'selected' : '' }}>
                                {{ $venue->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>

        <div class="mb-2">
            <span class="badge" style="background:#3788d8">&#9632; {{ trans('cruds.event.title') }}</span>
            <span class="badge ms-2" style="background:#28a745">&#9632; {{ trans('cruds.meeting.title') }}</span>
            <span class="badge ms-2" style="background:#fd7e14">&#9632; {{ trans('cruds.equipmentLoan.title') }}</span>
        </div>

        <div id='calendar'></div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: {!! json_encode($calendarEvents) !!},
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            if (info.event.url) {
                window.open(info.event.url, '_self');
            }
        }
    });
    calendar.render();
});
</script>
@stop
