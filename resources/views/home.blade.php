@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="mb-4">{{ trans('global.youAreLoggedIn') }}</p>

                    <div class="row">
                        @can('event_access')
                        <div class="col-sm-6 col-lg-3 mb-3">
                            <a href="{{ route('admin.systemCalendar') }}" class="text-decoration-none">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center py-4">
                                        <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                        <h5 class="mb-0">{{ trans('global.systemCalendar') }}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endcan
                        @can('event_access')
                        <div class="col-sm-6 col-lg-3 mb-3">
                            <a href="{{ route('admin.events.index') }}" class="text-decoration-none">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center py-4">
                                        <i class="fas fa-calendar-check fa-2x mb-2"></i>
                                        <h5 class="mb-0">{{ trans('cruds.event.title') }}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endcan
                        @can('meeting_access')
                        <div class="col-sm-6 col-lg-3 mb-3">
                            <a href="{{ route('admin.meetings.index') }}" class="text-decoration-none">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center py-4">
                                        <i class="fas fa-handshake fa-2x mb-2"></i>
                                        <h5 class="mb-0">{{ trans('cruds.meeting.title') }}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endcan
                        @can('venue_access')
                        <div class="col-sm-6 col-lg-3 mb-3">
                            <a href="{{ route('admin.venues.index') }}" class="text-decoration-none">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center py-4">
                                        <i class="fas fa-building fa-2x mb-2"></i>
                                        <h5 class="mb-0">{{ trans('cruds.venue.title') }}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection