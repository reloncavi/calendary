<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;

    public $table = 'equipment';

    const TYPES = ['proyector', 'notebook', 'micrófono', 'otro'];

    protected $fillable = [
        'name',
        'type',
        'code',
        'description',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function loans()
    {
        return $this->hasMany(EquipmentLoan::class);
    }

    public function activeLoans()
    {
        return $this->hasMany(EquipmentLoan::class)->whereNull('returned_at');
    }
}
