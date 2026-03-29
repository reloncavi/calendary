<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentLoan extends Model
{
    use SoftDeletes;

    public $table = 'equipment_loans';

    protected $fillable = [
        'equipment_id',
        'borrower_name',
        'purpose',
        'start_time',
        'end_time',
        'returned_at',
        'notes',
    ];

    protected $casts = [
        'returned_at' => 'datetime',
        'deleted_at'  => 'datetime',
    ];

    public function getStartTimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getEndTimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function isReturned(): bool
    {
        return $this->returned_at !== null;
    }
}
