<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sala;

class SalaController extends Controller
{
    // GET
    public function index(Request $request)
    {
        $query = DB::table('_t_sales as sal')
            ->leftJoin('_t_edificis as edi', 'sal.id_edifici', '=', 'edi.id')
            ->select(
                'sal.*',
                'edi.nom as nom_edifici'
            );

        if ($request->filled('id')) {
            return $query->where('sal.id', $request->id)->first();
        }

        // 🔹 DISPONIBLES
        if ($request->filled('disponibles')) {

            $id = (int) $request->disponibles;

            return DB::table('_t_complements as com')
                ->select('com.id', 'com.descripcio', 'com.preu', 'com.actiu')
                ->where('com.actiu', 'SI')
                ->whereNotIn('com.id', function ($query) use ($id) {
                    $query->select('sic.id_complements')
                        ->from('_t_sales_in_complements as sic')
                        ->where('sic.id_sales', $id);
                })
                ->get();
        }

        // 🔹 SELECCIONADOS
        if ($request->filled('seleccionats')) {

            $id = (int) $request->seleccionats;

            return DB::table('_t_complements as com')
                ->select('com.id', 'com.descripcio', 'com.preu', 'com.actiu')
                ->whereIn('com.id', function ($query) use ($id) {
                    $query->select('sic.id_complements')
                        ->from('_t_sales_in_complements as sic')
                        ->where('sic.id_sales', $id);
                })
                ->get();
        }

        return $query->get();
    }

    // GET
    public function salasEdifici($edifici)
    {        
        return DB::table('_t_sales as sal')
        ->where('sal.id_edifici', $edifici)
        ->get();           
    }

    // GET
    public function verSala($id_sala)
    {        
        $sala = DB::table('_t_sales as sal')
                ->leftJoin('_t_edificis as edi', 'sal.id_edifici', '=', 'edi.id')
                ->select(
                    'sal.id',
                    'sal.descripcio',
                    'sal.id_edifici',
                    'sal.preu',
                    'sal.actiu',
                    'sal.color',
                    'sal.missatge',
                    'sal.max_ocupacio',
                    'sal.horari',
                    'sal.imatge',
                    'edi.nom as nom_edifici',
                    DB::raw('0 as reservas_mes')
                )
                ->where('sal.id', $id_sala)
                ->first();
            return response()->json($sala);
    }

    // GET
    public function verComplements($id_sala)    
    {        
        $complements = DB::table('_t_complements as com')
                ->join('_t_sales_in_complements as sic', 'sic.id_complements', '=', 'com.id')
                ->select(
                    'com.id',
                    'com.descripcio',
                    'com.preu',
                    'com.actiu'
                )
                ->where('sic.id_sales', $id_sala)
                ->get();

            return response()->json($complements);        
    }

    // POST
    public function store(Request $request)
    {
        $validated = $request->validate([
            'descripcio'   => 'required|string|max:200',
            'id_edifici'   => 'required|integer',
            'preu'         => 'required|numeric',
            'actiu'        => 'required|string',
            'color'        => 'nullable|string',
            'missatge'     => 'nullable|string',
            'max_ocupacio' => 'required|integer',
            'horari'       => 'nullable|integer',
            'imatge'       => 'nullable'
        ]);

        $sala = Sala::create($validated);

        // Gestión complements
        if ($request->filled('complements')) {
            $sala->complements()->sync($request->complements);
        }

        return response()->json([
            'success' => true,
            'id' => $sala->id
        ], 201);
    }

    // PUT
    public function update(Request $request, $id)
    {
        $sala = Sala::findOrFail($id);

        $validated = $request->validate([
            'descripcio'   => 'sometimes|string|max:200',
            'id_edifici'   => 'sometimes|integer',
            'preu'         => 'sometimes|numeric',
            'actiu'        => 'sometimes|string',
            'color'        => 'nullable|string',
            'missatge'     => 'nullable|string',
            'max_ocupacio' => 'sometimes|integer',
            'horari'       => 'sometimes|integer',
            'imatge'       => 'nullable'
        ]);

        $sala->update($validated);

        if ($request->filled('complements')) {
            $sala->complements()->sync($request->complements);
        }

        return response()->json(['success' => true]);
    }
}