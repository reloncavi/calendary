@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ trans('cruds.equipmentLoan.title_singular') }}</span>
        <a href="{{ route('admin.equipment-loans.index') }}" class="btn btn-sm btn-secondary">← {{ trans('global.back_to_list') }}</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tbody>
                <tr><th>{{ trans('cruds.equipmentLoan.fields.id') }}</th><td>{{ $equipmentLoan->id }}</td></tr>
                <tr><th>{{ trans('cruds.equipmentLoan.fields.equipment') }}</th><td>{{ $equipmentLoan->equipment->name ?? '—' }} ({{ $equipmentLoan->equipment->type ?? '' }})</td></tr>
                <tr><th>{{ trans('cruds.equipmentLoan.fields.borrower_name') }}</th><td>{{ $equipmentLoan->borrower_name }}</td></tr>
                <tr><th>{{ trans('cruds.equipmentLoan.fields.purpose') }}</th><td>{{ $equipmentLoan->purpose ?? '—' }}</td></tr>
                <tr><th>{{ trans('cruds.equipmentLoan.fields.start_time') }}</th><td>{{ $equipmentLoan->start_time }}</td></tr>
                <tr><th>{{ trans('cruds.equipmentLoan.fields.end_time') }}</th><td>{{ $equipmentLoan->end_time }}</td></tr>
                <tr>
                    <th>{{ trans('cruds.equipmentLoan.fields.returned_at') }}</th>
                    <td>
                        @if($equipmentLoan->returned_at)
                            <span class="badge bg-success">{{ $equipmentLoan->returned_at->format('Y-m-d H:i') }}</span>
                        @else
                            <span class="badge bg-warning text-dark">{{ trans('cruds.equipmentLoan.fields.pending') }}</span>
                        @endif
                    </td>
                </tr>
                @if($equipmentLoan->notes)
                <tr><th>{{ trans('cruds.equipmentLoan.fields.notes') }}</th><td>{{ $equipmentLoan->notes }}</td></tr>
                @endif
            </tbody>
        </table>

        @if(!$equipmentLoan->returned_at)
        @can('equipment_loan_edit')
        <form action="{{ route('admin.equipment-loans.return', $equipmentLoan->id) }}" method="POST" class="mt-3" onsubmit="return confirm('¿Confirmar devolución del equipo?');">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check-circle me-1"></i> {{ trans('cruds.equipmentLoan.actions.mark_returned') }}
            </button>
        </form>
        @endcan
        @endif
    </div>
</div>
@endsection
