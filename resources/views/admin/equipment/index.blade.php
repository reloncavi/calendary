@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ trans('cruds.equipment.title') }}</h5>
        @can('equipment_create')
        <a href="{{ route('admin.equipment.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i> {{ trans('global.add') }} {{ trans('cruds.equipment.title_singular') }}
        </a>
        @endcan
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th width="10">&nbsp;</th>
                        <th>{{ trans('cruds.equipment.fields.id') }}</th>
                        <th>{{ trans('cruds.equipment.fields.name') }}</th>
                        <th>{{ trans('cruds.equipment.fields.type') }}</th>
                        <th>{{ trans('cruds.equipment.fields.code') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipment as $item)
                    <tr data-entry-id="{{ $item->id }}">
                        <td>&nbsp;</td>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->type }}</td>
                        <td>{{ $item->code }}</td>
                        <td>
                            @can('equipment_show')
                            <a href="{{ route('admin.equipment.show', $item->id) }}" class="btn btn-xs btn-primary">{{ trans('global.view') }}</a>
                            @endcan
                            @can('equipment_edit')
                            <a href="{{ route('admin.equipment.edit', $item->id) }}" class="btn btn-xs btn-info">{{ trans('global.edit') }}</a>
                            @endcan
                            @can('equipment_delete')
                            <form action="{{ route('admin.equipment.destroy', $item->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display:inline-block">
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
