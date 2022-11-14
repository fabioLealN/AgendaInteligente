<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeScheduling extends Model
{
    use HasFactory;

    protected $table = 'types_schedulings';

    protected $fillable = [
        'name'
    ];

    public function schedulings()
    {
        return $this->belongsTo(Scheduling::class);
    }
}
