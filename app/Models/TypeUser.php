<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeUser extends Model
{
    use HasFactory;

    const TYPE_ONG = 1;
    const TYPE_CLIENT = 2;

    protected $table = 'types_users';

    protected $fillable = [
        'name'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
