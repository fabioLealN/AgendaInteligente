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

    public function specialists()
    {
        return $this->belongsToMany(UserSpeciality::class, 'user_speciality_schedule', 'schedule_id', 'user_speciality_id')->using(UserSpecialitySchedule::class);

    }

    public function speciality()
    {
        return $this->belongsTo(Speciality::class);
    }

    public function schedulings()
    {
        return $this->hasOne(Scheduling::class);
    }
}
