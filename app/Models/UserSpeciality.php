<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserSpeciality extends Pivot
{
    use HasFactory;

    protected $table = "user_specialities";
    public $incrementing = true;
    
    public function ongs()
    {
        return $this->belongsToMany(Ong::class, 'user_speciality_ongs', 'ong_id', 'user_speciality_id')->using(UserSpecialityOng::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'user_speciality_schedule', 'schedule_id', 'user_speciality_id')->using(UserSpecialitySchedule::class);

    }

}
