@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ trans('cruds.equipmentLoan.title') }}</h5>
        @can('equipment_loan_create')
        <a href="{{ route('admin.equipment-loans.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i> {{ trans('global.add') }} {{ trans('cruds.equipmentLoan.title_singular') }}
        </a>
        @endcan
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th width="10">&nbsp;</th>
                        <th>{{ trans('cruds.equipmentLoan.fields.id') }}</th>
                        <th>{{ trans('cruds.equipmentLoan.fields.equipment') }}</th>
                        <th>{{ trans('cruds.equipmentLoan.fields.borrower_name') }}</th>
                        <th>{{ trans('cruds.equipmentLoan.fields.purpose') }}</th>
                        <th>{{ trans('cruds.equipmentLoan.fields.start_time') }}</th>
                        <th>{{ trans('cruds.equipmentLoan.fields.end_time') }}</th>
                        <th>{{ trans('cruds.equipmentLoan.fields.returned_at') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loans as $loan)
                    <tr data-entry-id="{{ $loan->id }}">
                        <td>&nbsp;</td>
                        <td>{{ $loan->id }}</td>
                        <td>{{ $loan->equipment->name ?? '—' }}</td>
                        <td>{{ $loan->borrower_name }}</td>
                        <td>{{ $loan->purpose }}</td>
                        <td>{{ $loan->start_time }}</td>
                        <td>{{ $loan->end_time }}</td>
                        <td>
                            @if($loan->returned_at)
                                <span class="badge bg-success">{{ $loan->returned_at->format('Y-m-d H:i') }}</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ trans('cruds.equipmentLoan.fields.pending') }}</span>
                            @endif
                        </td>
                        <td>
                            @can('equipment_loan_show')
                            <a href="{{ route('admin.equipment-loans.show', $loan->id) }}" class="btn btn-xs btn-primary">{{ trans('global.view') }}</a>
                            @endcan
                            @can('equipment_loan_edit')
                            <a href="{{ route('admin.equipment-loans.edit', $loan->id) }}" class="btn btn-xs btn-info">{{ trans('global.edit') }}</a>
                            @endcan
                            @can('equipment_loan_delete')
                            <form action="{{ route('admin.equipment-loans.destroy', $loan->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display:inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger">{{ trans('global.delete') }}</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
$(function() { $('.datatable').DataTable(); });
</script>
@endsection
