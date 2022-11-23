<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserSpecialityOng extends Pivot
{
    use HasFactory;
    public $incrementing = true;

    public function ong()
    {
        return $this->belongsTo(Ong::class);
    }

    public function userSpeciality()
    {
        return $this->belongsTo(UserSpeciality::class, 'user_speciality_id');
    }
}
