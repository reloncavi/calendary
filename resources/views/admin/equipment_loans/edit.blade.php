@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.equipmentLoan.title_singular') }}
    </div>
    <div class="card-body">
        <form action="{{ route('admin.equipment-loans.update', $equipmentLoan->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label for="equipment_id" class="form-label">{{ trans('cruds.equipmentLoan.fields.equipment') }} <span class="text-danger">*</span></label>
                <select name="equipment_id" id="equipment_id" class="form-select select2" required>
                    @foreach($equipment as $id => $name)
                        <option value="{{ $id }}" {{ old('equipment_id', $equipmentLoan->equipment_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="borrower_name" class="form-label">{{ trans('cruds.equipmentLoan.fields.borrower_name') }} <span class="text-danger">*</span></label>
                <input type="text" id="borrower_name" name="borrower_name" class="form-control {{ $errors->has('borrower_name') ? 'is-invalid' : '' }}" value="{{ old('borrower_name', $equipmentLoan->borrower_name) }}" required>
                @if($errors->has('borrower_name'))
                    <div class="invalid-feedback">{{ $errors->first('borrower_name') }}</div>
                @endif
            </div>
            <div class="mb-3">
                <label for="purpose" class="form-label">{{ trans('cruds.equipmentLoan.fields.purpose') }}</label>
                <input type="text" id="purpose" name="purpose" class="form-control" value="{{ old('purpose', $equipmentLoan->purpose) }}">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_time" class="form-label">{{ trans('cruds.equipmentLoan.fields.start_time') }} <span class="text-danger">*</span></label>
                    <input type="text" id="start_time" name="start_time" class="form-control flatpickr-datetime {{ $errors->has('start_time') ? 'is-invalid' : '' }}" value="{{ old('start_time', $equipmentLoan->start_time) }}" required>
                    @if($errors->has('start_time'))
                        <div class="invalid-feedback">{{ $errors->first('start_time') }}</div>
                    @endif
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_time" class="form-label">{{ trans('cruds.equipmentLoan.fields.end_time') }} <span class="text-danger">*</span></label>
                    <input type="text" id="end_time" name="end_time" class="form-control flatpickr-datetime {{ $errors->has('end_time') ? 'is-invalid' : '' }}" value="{{ old('end_time', $equipmentLoan->end_time) }}" required>
                    @if($errors->has('end_time'))
                        <div class="invalid-feedback">{{ $errors->first('end_time') }}</div>
                    @endif
                </div>
            </div>
            <div class="mb-3">
                <label for="notes" class="form-label">{{ trans('cruds.equipmentLoan.fields.notes') }}</label>
                <textarea name="notes" id="notes" rows="3" class="form-control">{{ old('notes', $equipmentLoan->notes) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">{{ trans('global.save') }}</button>
            <a href="{{ route('admin.equipment-loans.index') }}" class="btn btn-secondary ms-2">{{ trans('global.cancel') }}</a>
        </form>
    </div>
</div>
@endsection
