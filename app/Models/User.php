<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'type_user_id',
        'address_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function ongs()
    {
        return $this->belongsToMany(Ong::class, 'users_ongs')->using(UserOng::class);
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'users_schedules')->using(UserSchedule::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function typeUser()
    {
        return $this->belongsTo(TypeUser::class);
    }

    public function pets()
    {
        return $this->belongsTo(Pet::class);
    }

    public function distances()
    {
        return $this->hasMany(Distance::class);
    }
}
