<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
        'name',
        'date',
        'start_time',
        'end_time',
        'interval_time',
        'available'
    ];

    protected $casts = [
        'start_time' => 'hh:mm',
        'end_time' => 'hh:mm',
        'interval_time' => 'hh:mm',
        'available' => 'boolean'

    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function schedulings()
    {
        return $this->hasMany(Scheduling::class);
    }
}
