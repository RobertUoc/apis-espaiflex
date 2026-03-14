<?php

namespace App\Http\Controllers;

use App\Models\Edifici;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EdificiController extends Controller {
    
    // GET: Listar todos o filtrar
    public function index(Request $request) {
        $query = Edifici::query();

        if ($request->has('id')) {
            return $query->findOrFail($request->id);
        }

        if ($request->filled('provincia')) {
            $query = DB::table('_t_edificis as edi')
                ->join('_t_provincies as pro', 'edi.id_provincia', '=', 'pro.id')
                ->select(
                    'pro.nom as provincia',
                    'edi.id',
                    'edi.nom',
                    'edi.imatge',
                    'edi.latitud',
                    'edi.longitud'
                )
                ->where('edi.actiu', 'SI')
                ->where('pro.nom', 'like', '%' . $request->provincia . '%');
            return $query->get();
        }

        return $query->get();
    }

    // POST: Insertar
    public function store(Request $request) {       
        $validated = $request->validate([
                'nom'           => 'required|string|max:200',
                'id_provincia'  => 'required|integer|exists:_t_provincies,id',
                'imatge'        => 'nullable|string',
                'descripcio'    => 'nullable|string',
                'actiu'         => 'required|string|max:2',
                'latitud'       => 'required|numeric',
                'longitud'      => 'required|numeric',
            ]);         

        $edifici = Edifici::create($validated);
        return response()->json(['success' => true, 'id' => $edifici->id], 201);
    }

    // PUT: Update
    public function update(Request $request, $id) {        
        $edifici = Edifici::findOrFail($id);

        $validated = $request->validate([
                'nom'           => 'required|string|max:200',
                'id_provincia'  => 'required|integer|exists:_t_provincies,id',
                'imatge'        => 'nullable|string',
                'descripcio'    => 'nullable|string',
                'actiu'         => 'required|string|max:2',
                'latitud'       => 'required|numeric',
                'longitud'      => 'required|numeric',
            ]);        
 
        $edifici->update($validated);
        return response()->json(['success' => true, 'data' => $edifici]);
    }

}