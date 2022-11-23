<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    use HasFactory;

    protected $table = 'specialities';

    protected $fillable = [
        'name',
        'description'
    ];

    public function ongs()
    {
        return $this->belongsToMany(Ong::class, 'ongs_specialities')->using(OngSpeciality::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_specialities')->using(UserSpeciality::class);
    }
}
