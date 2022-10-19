<?php

namespace App\Services;

use App\Models\Size;
use Illuminate\Validation\ValidationException;

class SizeService
{
    public function get(int $id)
    {
        $size = Size::find($id);

        if(!!!$size) {
            throw ValidationException::withMessages(['Porte não encontrado.']);
        }

        return $size;
    }

    public function getAll()
    {
        $sizes = Size::all();

        if(!!!$sizes) {
            throw ValidationException::withMessages(['Não há portes salvos.']);
        }

        return $sizes;
    }
}
