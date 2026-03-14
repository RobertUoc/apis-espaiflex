<?php

namespace App\Http\Controllers;

use App\Models\Provincia;
use Illuminate\Http\Request;

class ProvinciaController extends Controller
{
    /**
     * GET /api/provincies
     */
    public function index()
    {
        return response()->json(
            Provincia::all(),
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * GET /api/provincies/{id}
     */
    public function show($id)
    {
        $provincia = Provincia::find($id);

        if (!$provincia) {
            return response()->json([
                'error' => 'No encontrado'
            ], 404);
        }

        return response()->json(
            $provincia,
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }
}