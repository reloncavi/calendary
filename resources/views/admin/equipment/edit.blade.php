@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.equipment.title_singular') }}
    </div>
    <div class="card-body">
        <form action="{{ route('admin.equipment.update', $equipment->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">{{ trans('cruds.equipment.fields.name') }} <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name', $equipment->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                @endif
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">{{ trans('cruds.equipment.fields.type') }} <span class="text-danger">*</span></label>
                <select name="type" id="type" class="form-select" required>
                    @foreach(\App\Models\Equipment::TYPES as $type)
                        <option value="{{ $type }}" {{ old('type', $equipment->type) == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">{{ trans('cruds.equipment.fields.code') }}</label>
                <input type="text" id="code" name="code" class="form-control" value="{{ old('code', $equipment->code) }}">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">{{ trans('cruds.equipment.fields.description') }}</label>
                <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $equipment->description) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">{{ trans('global.save') }}</button>
            <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary ms-2">{{ trans('global.cancel') }}</a>
        </form>
    </div>
</div>
@endsection
