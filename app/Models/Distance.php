<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distance extends Model
{
    use HasFactory;

    protected $table = 'distances';

    protected $fillable = [
        'user_id',
        'ong_id',
        'distance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ong()
    {
        return $this->belongsTo(Ong::class);
    }
}
