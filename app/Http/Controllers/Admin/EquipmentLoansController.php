<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentLoan;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EquipmentLoansController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('equipment_loan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $loans = EquipmentLoan::with('equipment')->orderByDesc('start_time')->get();

        return view('admin.equipment_loans.index', compact('loans'));
    }

    public function create()
    {
        abort_if(Gate::denies('equipment_loan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $equipment = Equipment::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.equipment_loans.create', compact('equipment'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('equipment_loan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'equipment_id'  => ['required', 'exists:equipment,id'],
            'borrower_name' => ['required', 'string', 'max:255'],
            'purpose'       => ['nullable', 'string', 'max:255'],
            'start_time'    => ['required', 'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format')],
            'end_time'      => ['required', 'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'), 'after:start_time'],
            'notes'         => ['nullable', 'string'],
        ]);

        $startTime   = date('Y-m-d H:i:s', strtotime($request->input('start_time')));
        $endTime     = date('Y-m-d H:i:s', strtotime($request->input('end_time')));
        $equipmentId = $request->input('equipment_id');

        $conflictExists = EquipmentLoan::where('equipment_id', $equipmentId)
            ->whereNull('returned_at')
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        if ($conflictExists) {
            Session()->flash('message', 'El equipo ya está prestado en ese horario');
            Session()->flash('alert-class', 'alert-danger');

            return redirect()->back()->withInput();
        }

        EquipmentLoan::create($request->all());

        Session()->flash('message', 'Préstamo registrado con éxito');
        Session()->flash('alert-class', 'alert-success');

        return redirect()->route('admin.equipment-loans.index');
    }

    public function edit(EquipmentLoan $equipmentLoan)
    {
        abort_if(Gate::denies('equipment_loan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $equipment = Equipment::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.equipment_loans.edit', compact('equipmentLoan', 'equipment'));
    }

    public function update(Request $request, EquipmentLoan $equipmentLoan)
    {
        abort_if(Gate::denies('equipment_loan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'equipment_id'  => ['required', 'exists:equipment,id'],
            'borrower_name' => ['required', 'string', 'max:255'],
            'purpose'       => ['nullable', 'string', 'max:255'],
            'start_time'    => ['required', 'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format')],
            'end_time'      => ['required', 'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'), 'after:start_time'],
            'notes'         => ['nullable', 'string'],
        ]);

        $startTime   = date('Y-m-d H:i:s', strtotime($request->input('start_time')));
        $endTime     = date('Y-m-d H:i:s', strtotime($request->input('end_time')));
        $equipmentId = $request->input('equipment_id');

        $conflictExists = EquipmentLoan::where('equipment_id', $equipmentId)
            ->where('id', '!=', $equipmentLoan->id)
            ->whereNull('returned_at')
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        if ($conflictExists) {
            Session()->flash('message', 'El equipo ya está prestado en ese horario');
            Session()->flash('alert-class', 'alert-danger');

            return redirect()->back()->withInput();
        }

        $equipmentLoan->update($request->all());

        Session()->flash('message', 'Préstamo actualizado con éxito');
        Session()->flash('alert-class', 'alert-success');

        return redirect()->route('admin.equipment-loans.index');
    }

    public function show(EquipmentLoan $equipmentLoan)
    {
        abort_if(Gate::denies('equipment_loan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $equipmentLoan->load('equipment');

        return view('admin.equipment_loans.show', compact('equipmentLoan'));
    }

    public function destroy(EquipmentLoan $equipmentLoan)
    {
        abort_if(Gate::denies('equipment_loan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $equipmentLoan->delete();

        Session()->flash('message', 'Préstamo eliminado');
        Session()->flash('alert-class', 'alert-warning');

        return back();
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('equipment_loan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        EquipmentLoan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function markReturned(EquipmentLoan $equipmentLoan)
    {
        abort_if(Gate::denies('equipment_loan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $equipmentLoan->update(['returned_at' => Carbon::now()]);

        Session()->flash('message', 'Devolución registrada con éxito');
        Session()->flash('alert-class', 'alert-success');

        return redirect()->route('admin.equipment-loans.show', $equipmentLoan);
    }
}
