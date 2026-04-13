<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Comentari;

class ComentariController extends Controller
{
    /**
     * GET /api/comentaris
     * Filtros opcionales:
     *  ?id_reserva=1
     *  ?id_sala=3
     */
    public function index(Request $request)
    {
        $query = DB::table('_t_reserves_comentaris as com')
            ->leftJoin('_t_reserves as res', 'res.id', '=', 'com.id_reserves')
            ->leftJoin('_t_sales as sal', 'res.sala', '=', 'sal.id')
            ->leftJoin('users as usu', 'res.id_user', '=', 'usu.id')
            ->select(
                'usu.name as nom',
                'sal.descripcio',
                'res.dia_inici',
                'res.dia_fi',
                'com.comentari',
                'com.puntuacio',
                'com.created_at'
            )
            ->orderBy('usu.name')
            ->orderBy('com.created_at');

        if ($request->filled('id_reserva')) {
            $query->where('com.id_reserves', $request->id_reserva);
        }

        if ($request->filled('id_sala')) {
            $query->where('res.sala', $request->id_sala);
        }

        return response()->json(
            $query->get(),
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * POST /api/comentaris
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_reserves' => 'required|integer',
            'id_user'     => 'required|integer',
            'comentari'   => 'required|string',
            'puntuacio'   => 'required|integer|min:1|max:5',
            'nom'         => 'nullable|string',
        ]);

        $comentari = Comentari::create($validated);

        return response()->json([
            'success' => true,
            'id' => $comentari->id
        ], 201);
    }
}