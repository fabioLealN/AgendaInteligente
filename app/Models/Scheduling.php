<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scheduling extends Model
{
    use HasFactory;

    protected $table = 'schedulings';

    protected $fillable = [
        'description',
        'pet_id',
        'schedule_id',
        'type_scheduling_id'
    ];

    public function pet() {
        return $this->belongsTo(Pet::class);
    }

    public function schedule() {
        return $this->belongsTo(Schedule::class);
    }

    public function typeScheduling() {
        return $this->belongsTo(TypeScheduling::class, 'type_scheduling_id');
    }
}
