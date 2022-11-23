<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Ong extends Model
{
    use HasFactory;

    protected $table = 'ongs';

    protected $fillable = [
        'name',
        'address_id',
        'image'
    ];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }

        return Storage::url('no-image.png');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_ongs')->using(UserOng::class);
    }

    public function specialists()
    {
        return $this->belongsToMany(UserSpeciality::class, 'user_speciality_ongs', 'ong_id', 'user_speciality_id')->using(UserSpecialityOng::class)->withTimestamps();
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
