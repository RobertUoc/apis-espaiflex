<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\FacturaMail;
use App\Http\Controllers\FacturaController;

class ReservaController extends Controller
{
    // Mostrar Events del Calendari
    // Paso Any i Edidifi
    public function lecturaReserves($any, $edifici)
    {
       $reserves = DB::table('_t_reserves_dies as dia')
        ->leftJoin('_t_reserves as res', 'res.id', '=', 'dia.id_reserva')
        ->leftJoin('_t_sales as sal', 'res.sala', '=', 'sal.id')
        ->select(
            'res.id',
            'dia.id_reserva',
            'dia.dia_inici',
            'dia.dia_fi',
            'dia.hora_inici',
            'dia.hora_fi',
            'sal.color',
            'sal.descripcio',
            'res.sala',
            DB::raw("CONCAT(dia.dia_inici,'T',dia.hora_inici) as start"),
            DB::raw("CONCAT(dia.dia_fi,'T',dia.hora_fi) as end")
        )
        ->where('dia.actiu', 'SI')
        ->whereNull('dia.data_delete')
        ->whereYear('dia.dia_inici', $any)
        ->where('sal.id_edifici', $edifici)
        ->orderBy('dia.dia_inici')
        ->orderBy('dia.hora_inici')
        ->get();
        return response()->json($reserves);
    }

    // Click en un dia 
    // Veure disponibilitat
    public function lecturaDia($dia, $edifici)
    {
        $sub = DB::table('_t_reserves as res')
            ->select('hora_inici','hora_fi','sala')
            ->whereNull('res.data_delete')
            ->where('res.dia_inici','<=',$dia)
            ->where('res.dia_fi','>=',$dia)
            ->groupBy('sala','hora_inici','hora_fi');

        $result = DB::table('_t_hores as h')
            ->join('_t_sales as sal', function ($join) use ($edifici) {
                $join->on('sal.horari','=','h.tipus')
                    ->where('sal.id_edifici','=',$edifici)
                    ->where('sal.actiu','=','SI');
            })
            ->leftJoinSub($sub, 'r', function ($join) {
                $join->on('h.hora','=','r.hora_inici')
                    ->on('r.sala','=','sal.id');
            })
            ->select(
                'sal.id',
                'sal.descripcio',
                DB::raw('h.hora as hora_inici'),
                DB::raw("COALESCE(r.hora_fi,'No informado') as estado"),
                'h.tipus'
            )
            ->where('h.activa','SI')
            ->orderBy('sal.descripcio')
            ->orderBy('h.hora')
            ->get();

        return response()->json($result);        
    }

    //
    //
    public function lecturaEvent($dia,$sala) 
    {
        $sub = DB::table('_t_reserves as res')
            ->select('hora_inici','hora_fi','sala')
            ->whereNull('res.data_delete')
            ->where('res.dia_inici', $dia)
            ->groupBy('sala','hora_inici','hora_fi');

        $result = DB::table('_t_hores as h')
            ->join('_t_sales as sal', function ($join) use ($sala) {
                $join->on('h.tipus','=','sal.horari')
                    ->where('sal.id','=',$sala)
                    ->where('sal.actiu','=','SI');
            })
            ->leftJoinSub($sub, 'r', function ($join) {
                $join->on('h.hora','=','r.hora_inici')
                    ->on('r.sala','=','sal.id');
            })
            ->select(
                'sal.id',
                'sal.descripcio',
                DB::raw('h.hora as hora_inici'),
                DB::raw("COALESCE(r.hora_fi,'No informado') as estado"),
                'h.tipus'
            )
            ->where('h.activa','SI')
            ->orderBy('sal.descripcio')
            ->orderBy('h.hora')
            ->get();

        return response()->json($result);
    }

    //
    //
    public function miraReserva($dia,$sala,$reserva) 
    {
        $sub = DB::table('_t_reserves as res')
            ->select('hora_inici','hora_fi','sala')
            ->whereNull('res.data_delete')
            ->where('res.dia_inici', $dia)
            ->where('res.id', $reserva)
            ->groupBy('sala','hora_inici','hora_fi');

        $result = DB::table('_t_hores as h')
            ->join('_t_sales as sal', function ($join) use ($sala) {
                $join->on('h.tipus','=','sal.horari')
                    ->where('sal.id','=',$sala)
                    ->where('sal.actiu','=','SI');
            })
            ->leftJoinSub($sub, 'r', function ($join) {
                $join->on('h.hora','=','r.hora_inici')
                    ->on('r.sala','=','sal.id');
            })
            ->select(
                'sal.id',
                'sal.descripcio',
                DB::raw('h.hora as hora_inici'),
                DB::raw("COALESCE(r.hora_fi,'No informado') as estado"),
                'h.tipus'
            )
            ->where('h.activa','SI')
            ->orderBy('sal.descripcio')
            ->orderBy('h.hora')
            ->get();

        return response()->json($result);        
    }

    // Click en un event
    // Veure dades de la reserva
    public function lecturaReserva($id) 
    {
        $reserva = DB::table('_t_reserves as res')
                ->leftJoin('_t_reserves_in_complements as ric', 'res.id', '=', 'ric.id_reserves')
                ->leftJoin('_t_complements as com', 'ric.id_complements', '=', 'com.id')
                ->leftJoin('_t_sales as sal', 'res.sala', '=', 'sal.id')
                ->select(
                    'res.id',
                    'res.dia_inici',
                    'res.dia_fi',
                    'res.hora_inici',
                    'res.hora_fi',
                    'res.import as preu_sala',
                    'res.id_user',

                    'ric.id_complements',
                    'com.descripcio as complement_descripcio',
                    'com.preu as complement_preu',

                    'res.sala',
                    'res.frequencia',

                    'res.dilluns',
                    'res.dimarts',
                    'res.dimecres',
                    'res.dijous',
                    'res.divendres',
                    'res.dissabte',
                    'res.diumenge',

                    'res.tipo',
                    'res.dia_mes',
                    'res.el_semana',
                    'res.el_dia',

                    'sal.descripcio as sala_descripcio',
                    'sal.id_edifici',
                    'sal.preu as preu_base',
                    'sal.max_ocupacio',
                    'sal.missatge',
                    'sal.horari'
                )
                ->where('res.id', $id)
                ->get();

            return response()->json($reserva);
    }    

    // Esborrar un event    
    public function deleteEvent($id) 
    {
        return DB::transaction(function () use ($id) {

                // Obtener reserva
                $reserva = DB::table('_t_reserves')
                    ->where('id', $id)
                    ->first();

                if (!$reserva) {
                    return response()->json(['error' => 'Reserva no encontrada'], 404);
                }

                // Cancelar reserva
                DB::table('_t_reserves')
                    ->where('id', $id)
                    ->update([
                        'data_delete' => now(),
                        'actiu' => 'NO'
                    ]);

                // Cancelar días asociados
                DB::table('_t_reserves_dies')
                    ->where('id_reserva', $id)
                    ->update([
                        'data_delete' => now(),
                        'actiu' => 'NO'
                    ]);

                // Generar factura negativa
                $base = -$reserva->import;
                $iva = 21;
                $iva_import = round($base * ($iva / 100), 2);
                $total = $base + $iva_import;

                $facturaId = DB::table('_t_factures')->insertGetId([
                    'id_reserva' => $id,
                    'data_factura' => now(),
                    'base' => $base,
                    'iva' => $iva,
                    'iva_import' => $iva_import,
                    'total_factura' => $total,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'enviada' => 0
                ]);

                // Enviar email con la factura
                $facturaController = new FacturaController();
                $facturaController->enviarEmail($facturaId);

                return response()->json([
                    'success' => true,
                    'reserva' => $id,
                    'factura' => $facturaId
                ]);
            });        

    }
    
    // Buscar confilte
    public function buscarConflicte($sala,$diainici,$diafi,$horainici,$horafi) 
    {
        $query = DB::table('_t_reserves as res')
            ->leftJoin('_t_sales as sal','sal.id','=','res.sala')
            ->select(
                'res.dia_inici',
                'res.dia_fi',
                'res.sala',
                'res.hora_inici',
                'res.hora_fi'
            )
            ->where('res.sala',$sala)
            ->whereNull('res.data_delete')
            ->where(function($q) use ($horainici,$horafi){
                $q->where(function($q2) use ($horainici,$horafi){
                        $q2->where('res.hora_inici','>',$horainici)
                        ->where('res.hora_inici','<',$horafi);
                    })
                ->orWhere(function($q2) use ($horainici,$horafi){
                        $q2->where('res.hora_fi','>',$horainici)
                        ->where('res.hora_fi','<',$horafi);
                    })
                ->orWhere('res.hora_inici',$horainici)
                ->orWhere('res.hora_fi',$horafi);
            });
        //
        // Si es mateix dia
        //
        if($diainici == $diafi){
            $query->where(function($q) use ($diainici,$diafi){
                $q->where('res.dia_inici','<=',$diainici)
                ->where('res.dia_fi','>=',$diafi);
            });

        }else{
            $query->where(function($q) use ($diainici,$diafi){
                $q->where(function($q2) use ($diainici,$diafi){
                        $q2->where('res.dia_inici','<=',$diainici)
                        ->where('res.dia_fi','>=',$diafi);
                    })
                ->orWhere(function($q2) use ($diainici,$diafi){
                        $q2->where('res.dia_fi','>=',$diainici)
                        ->where('res.dia_fi','<=',$diafi);
                    });
            });
        }
        $data = $query->get();
        return response()->json($data);

    }

    // POST
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sala' => 'required|integer',
            'dia_inici' => 'required|date',
            'dia_fi' => 'required|date',
            'hora_inici' => 'required',
            'hora_fi' => 'required',
            'import' => 'required|numeric',
            'id_user' => 'required|integer',
            'frecuencia' => 'required|string',

            'dom' => 'required|integer',
            'lun' => 'required|integer',
            'mar' => 'required|integer',
            'mie' => 'required|integer',
            'jue' => 'required|integer',
            'vie' => 'required|integer',
            'sab' => 'required|integer',

            'tipo' => 'required|integer',
            'dia_mes' => 'required|integer',
            'el_semana' => 'nullable|string',
            'el_dia' => 'nullable|string',     
            
            'complements' => 'nullable|string',     
            
        ]);
        
        return DB::transaction(function () use ($validated) {
            $lastId = DB::table('_t_reserves')->insertGetId([
                'sala' => $validated['sala'],
                'dia_inici' => $validated['dia_inici'],
                'dia_fi' => $validated['dia_fi'],
                'hora_inici' => $validated['hora_inici'],
                'hora_fi' => $validated['hora_fi'],
                'import' => $validated['import'],
                'id_user' => $validated['id_user'],
                'frequencia' => $validated['frecuencia'],

                'diumenge' => $validated['dom'],
                'dilluns' => $validated['lun'],
                'dimarts' => $validated['mar'],
                'dimecres' => $validated['mie'],
                'dijous' => $validated['jue'],
                'divendres' => $validated['vie'],
                'dissabte' => $validated['sab'],

                'tipo' => $validated['tipo'],
                'dia_mes' => $validated['dia_mes'],
                'el_semana' => $validated['el_semana'],
                'el_dia' => $validated['el_dia'],
            ]);

            if (!empty($validated['complements'])) {
                $ids = explode('#', $validated['complements']);
                $data = [];
                foreach ($ids as $id) {
                    $id = intval($id);
                    if ($id > 0) {
                        $data[] = [
                            'id_reserves' => $lastId,
                            'id_complements' => $id
                        ];
                    }
                }
                if (!empty($data)) {
                    DB::table('_t_reserves_in_complements')->insert($data);
                }
            }

            return response()->json([
                'success' => true,
                'id' => $lastId
            ]);
        });
    }

}