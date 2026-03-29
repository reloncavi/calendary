<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EquipmentController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('equipment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $equipment = Equipment::all();

        return view('admin.equipment.index', compact('equipment'));
    }

    public function create()
    {
        abort_if(Gate::denies('equipment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.equipment.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('equipment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        Equipment::create($request->all());

        Session()->flash('message', 'Equipo registrado con éxito');
        Session()->flash('alert-class', 'alert-success');

        return redirect()->route('admin.equipment.index');
    }

    public function edit(Equipment $equipment)
    {
        abort_if(Gate::denies('equipment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.equipment.edit', compact('equipment'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        abort_if(Gate::denies('equipment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        $equipment->update($request->all());

        Session()->flash('message', 'Equipo actualizado con éxito');
        Session()->flash('alert-class', 'alert-success');

        return redirect()->route('admin.equipment.index');
    }

    public function show(Equipment $equipment)
    {
        abort_if(Gate::denies('equipment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $equipment->load('loans');

        return view('admin.equipment.show', compact('equipment'));
    }

    public function destroy(Equipment $equipment)
    {
        abort_if(Gate::denies('equipment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $equipment->delete();

        Session()->flash('message', 'Equipo eliminado');
        Session()->flash('alert-class', 'alert-warning');

        return back();
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('equipment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Equipment::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
