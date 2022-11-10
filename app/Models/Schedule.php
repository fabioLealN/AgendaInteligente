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
        'available'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'available' => 'boolean'

    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_schedules')->using(UserSchedule::class);
    }

    public function schedulings()
    {
        return $this->hasOne(Scheduling::class);
    }
}
