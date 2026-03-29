@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.equipment.title_singular') }}
    </div>
    <div class="card-body">
        <form action="{{ route('admin.equipment.store') }}" method="POST">
            @csrf
            <div class="mb-3 {{ $errors->has('name') ? 'has-validation' : '' }}">
                <label for="name" class="form-label">{{ trans('cruds.equipment.fields.name') }} <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                @endif
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">{{ trans('cruds.equipment.fields.type') }} <span class="text-danger">*</span></label>
                <select name="type" id="type" class="form-select {{ $errors->has('type') ? 'is-invalid' : '' }}" required>
                    <option value="">{{ trans('global.pleaseSelect') }}</option>
                    @foreach(\App\Models\Equipment::TYPES as $type)
                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                @endif
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">{{ trans('cruds.equipment.fields.code') }}</label>
                <input type="text" id="code" name="code" class="form-control" value="{{ old('code') }}">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">{{ trans('cruds.equipment.fields.description') }}</label>
                <textarea name="description" id="description" rows="3" class="form-control">{{ old('description') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">{{ trans('global.save') }}</button>
            <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary ms-2">{{ trans('global.cancel') }}</a>
        </form>
    </div>
</div>
@endsection
