<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';

    protected $fillable = [
        'city_id',
        'neighborhood',
        'street',
        'number',
        'cep'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function ong()
    {
        return $this->belongsTo(Ong::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }
}
