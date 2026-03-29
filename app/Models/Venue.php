<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use SoftDeletes;

    public $table = 'venues';

    protected $fillable = [
        'name',
        'address',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'venue_id', 'id');
    }
}
