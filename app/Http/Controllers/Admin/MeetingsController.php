<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMeetingRequest;
use App\Http\Requests\StoreMeetingRequest;
use App\Http\Requests\UpdateMeetingRequest;
use App\Models\Meeting;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MeetingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('meeting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meetings = Meeting::all();

        return view('admin.meetings.index', compact('meetings'));
    }

    public function create()
    {
        abort_if(Gate::denies('meeting_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.meetings.create');
    }

    public function store(StoreMeetingRequest $request)
    {
        $startTime = date('Y-m-d H:i:s', strtotime($request->input('start_time')));
        $endTime   = date('Y-m-d H:i:s', strtotime($request->input('end_time')));

        $conflictExists = Meeting::where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        if ($conflictExists) {
            Session()->flash('message', 'Ya existe una reunión programada en ese horario');
            Session()->flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        Meeting::create($request->all());

        Session()->flash('message', 'Reunión creada con éxito');
        Session()->flash('alert-class', 'alert-success');

        return redirect()->route('admin.meetings.index');
    }

    public function edit(Meeting $meeting)
    {
        abort_if(Gate::denies('meeting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.meetings.edit', compact('meeting'));
    }

    public function update(UpdateMeetingRequest $request, Meeting $meeting)
    {
        $startTime = date('Y-m-d H:i:s', strtotime($request->input('start_time')));
        $endTime   = date('Y-m-d H:i:s', strtotime($request->input('end_time')));

        $conflictExists = Meeting::where('id', '!=', $meeting->id)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        if ($conflictExists) {
            Session()->flash('message', 'Ya existe una reunión programada en ese horario');
            Session()->flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        $meeting->update($request->all());

        Session()->flash('message', 'Reunión actualizada con éxito');
        Session()->flash('alert-class', 'alert-success');

        return redirect()->route('admin.meetings.index');
    }

    public function show(Meeting $meeting)
    {
        abort_if(Gate::denies('meeting_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.meetings.show', compact('meeting'));
    }

    public function destroy(Meeting $meeting)
    {
        abort_if(Gate::denies('meeting_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meeting->delete();

        Session()->flash('message', 'Reunión eliminada');
        Session()->flash('alert-class', 'alert-warning');

        return back();
    }

    public function massDestroy(MassDestroyMeetingRequest $request)
    {
        Meeting::whereIn('id', request('ids'))->delete();

        Session()->flash('message', 'Reuniones eliminadas');
        Session()->flash('alert-class', 'alert-warning');

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
