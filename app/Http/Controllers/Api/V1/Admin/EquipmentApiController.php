<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\EquipmentResource;
use App\Models\Equipment;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EquipmentApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('equipment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return EquipmentResource::collection(Equipment::all());
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('equipment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string'],
        ]);

        $equipment = Equipment::create($request->all());

        return (new EquipmentResource($equipment))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Equipment $equipment)
    {
        abort_if(Gate::denies('equipment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EquipmentResource($equipment);
    }

    public function update(Request $request, Equipment $equipment)
    {
        abort_if(Gate::denies('equipment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string'],
        ]);

        $equipment->update($request->all());

        return (new EquipmentResource($equipment))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Equipment $equipment)
    {
        abort_if(Gate::denies('equipment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $equipment->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
