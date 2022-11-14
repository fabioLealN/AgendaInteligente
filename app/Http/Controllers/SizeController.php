<?php

namespace App\Http\Controllers;

use App\Services\SizeService;
use Illuminate\Validation\ValidationException;

class SizeController extends Controller
{

    public function __construct(private SizeService $sizeService)
    {
    }

    public function get($id)
    {
        try
        {
            return $this->sizeService->get($id);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function getAll()
    {
        return response()->json(['data' => $this->sizeService->getAll()]);
    }
}
