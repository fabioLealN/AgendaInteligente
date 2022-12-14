<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $table = 'sizes';

    protected $fillable = [
        'name'
    ];

    public function pets()
    {
        return $this->belongsTo(Pet::class);
    }

    public function users()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_sizes')->using(ScheduleSize::class);
    }
}
