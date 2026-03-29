<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\EquipmentLoanResource;
use App\Models\EquipmentLoan;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EquipmentLoansApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('equipment_loan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return EquipmentLoanResource::collection(EquipmentLoan::with('equipment')->get());
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('equipment_loan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'equipment_id'  => ['required', 'exists:equipment,id'],
            'borrower_name' => ['required', 'string'],
            'start_time'    => ['required'],
            'end_time'      => ['required', 'after:start_time'],
        ]);

        $loan = EquipmentLoan::create($request->all());

        return (new EquipmentLoanResource($loan->load('equipment')))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(EquipmentLoan $equipmentLoan)
    {
        abort_if(Gate::denies('equipment_loan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EquipmentLoanResource($equipmentLoan->load('equipment'));
    }

    public function update(Request $request, EquipmentLoan $equipmentLoan)
    {
        abort_if(Gate::denies('equipment_loan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $equipmentLoan->update($request->all());

        return (new EquipmentLoanResource($equipmentLoan))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(EquipmentLoan $equipmentLoan)
    {
        abort_if(Gate::denies('equipment_loan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $equipmentLoan->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
