<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ong extends Model
{
    use HasFactory;

    protected $table = 'ongs';

    protected $fillable = [
        'name',
        'address_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_ongs')->using(UserOng::class);
    }

    public function specialities()
    {
        return $this->belongsToMany(Speciality::class, 'ongs_specialities')->using(OngSpeciality::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function distances()
    {
        return $this->hasMany(Distance::class);
    }
}
