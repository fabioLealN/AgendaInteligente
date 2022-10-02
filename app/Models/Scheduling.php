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
        $this->belongsTo(Pet::class);
    }

    public function schedule() {
        $this->belongsTo(Schedule::class);
    }

    public function typeScheduling() {
        $this->belongsTo(TypeScheduling::class, 'type_scheduling_id');
    }
}
