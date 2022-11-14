<?php

namespace App\Services;

use App\Models\Size;
use Illuminate\Validation\ValidationException;

class SizeService
{
    public function get(int $id)
    {
        return Size::findOrFail($id);
    }

    public function getAll()
    {
        return Size::all();
    }
}
