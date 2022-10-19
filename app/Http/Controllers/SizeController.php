<?php

namespace App\Http\Controllers;

use App\Services\SizeService;
use Illuminate\Validation\ValidationException;

class SizeController extends Controller
{
    private $sizeService;

    public function __construct(SizeService $sizeService)
    {
        $this->sizeService = $sizeService;
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
        try
        {
            return $this->sizeService->getAll();
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
