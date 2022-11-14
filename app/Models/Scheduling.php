<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scheduling extends Model
{
    use HasFactory;

    protected $table = 'schedulings';

    protected $guarded = [];

    public function pet() {
        return $this->belongsTo(Pet::class);
    }

    public function ong() {
        return $this->belongsTo(Ong::class);
    }

    public function schedule() {
        return $this->belongsTo(Schedule::class);
    }

    public function typeScheduling() {
        return $this->belongsTo(TypeScheduling::class, 'type_scheduling_id');
    }
}
