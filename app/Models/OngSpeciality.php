<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OngSpeciality extends Pivot
{
    public $incrementing = true;
    public $table = "ongs_specialities";

}
