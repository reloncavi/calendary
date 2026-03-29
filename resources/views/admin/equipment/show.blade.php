@extends('layouts.admin')
@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between">
        <span>{{ trans('cruds.equipment.title_singular') }}</span>
        <a href="{{ route('admin.equipment.index') }}" class="btn btn-sm btn-secondary">← {{ trans('global.back_to_list') }}</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tbody>
                <tr><th>{{ trans('cruds.equipment.fields.id') }}</th><td>{{ $equipment->id }}</td></tr>
                <tr><th>{{ trans('cruds.equipment.fields.name') }}</th><td>{{ $equipment->name }}</td></tr>
                <tr><th>{{ trans('cruds.equipment.fields.type') }}</th><td>{{ ucfirst($equipment->type) }}</td></tr>
                <tr><th>{{ trans('cruds.equipment.fields.code') }}</th><td>{{ $equipment->code ?? '—' }}</td></tr>
                <tr><th>{{ trans('cruds.equipment.fields.description') }}</th><td>{{ $equipment->description ?? '—' }}</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">{{ trans('cruds.equipmentLoan.title') }}</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>{{ trans('cruds.equipmentLoan.fields.borrower_name') }}</th>
                        <th>{{ trans('cruds.equipmentLoan.fields.start_time') }}</th>
                        <th>{{ trans('cruds.equipmentLoan.fields.end_time') }}</th>
                        <th>{{ trans('cruds.equipmentLoan.fields.returned_at') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipment->loans as $loan)
                    <tr>
                        <td>{{ $loan->borrower_name }}</td>
                        <td>{{ $loan->start_time }}</td>
                        <td>{{ $loan->end_time }}</td>
                        <td>
                            @if($loan->returned_at)
                                <span class="badge bg-success">{{ $loan->returned_at->format('Y-m-d H:i') }}</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ trans('cruds.equipmentLoan.fields.pending') }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-muted text-center">Sin préstamos registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
