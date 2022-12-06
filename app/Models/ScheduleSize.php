<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ScheduleSize extends Pivot
{
    use HasFactory;

    protected $table = "schedule_sizes";
    public $incrementing = true;
}
