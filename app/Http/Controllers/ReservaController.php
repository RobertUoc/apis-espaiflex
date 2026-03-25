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
            'dia.id',            
            'dia.id_reserva',
            'dia.dia_inici',
            'dia.dia_fi',
            'dia.hora_inici',
            'dia.hora_fi',
            'sal.color',
            'sal.descripcio',
            'res.sala',
            'res.id as id_reserva',
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
        $sub = DB::table('_t_reserves_dies as dia')
            ->leftJoin('_t_reserves as res', 'dia.id_reserva', '=', 'res.id')
            ->select(
                'dia.hora_inici',
                'dia.hora_fi',
                'res.sala'
            )
            ->whereNull('dia.data_delete')
            ->where('dia.dia_inici', '<=', $dia)
            ->where('dia.dia_fi', '>=', $dia)
            ->groupBy('res.sala', 'dia.hora_inici', 'dia.hora_fi');

        $result = DB::table('_t_hores as h')
            ->join('_t_sales as sal', function ($join) use ($edifici) {
                $join->on('sal.horari', '=', 'h.tipus')
                    ->where('sal.id_edifici', '=', $edifici)
                    ->where('sal.actiu', '=', 'SI');
            })
            ->leftJoinSub($sub, 'r', function ($join) {
                $join->on('h.hora', '=', 'r.hora_inici')
                    ->on('r.sala', '=', 'sal.id');
            })
            ->select(
                'sal.id',
                'sal.descripcio',
                DB::raw('h.hora as hora_inici'),
                DB::raw("COALESCE(r.hora_fi,'No informado') as estado"),
                'h.tipus'
            )
            ->where('h.activa', 'SI')
            ->orderBy('sal.descripcio')
            ->orderBy('h.hora')
            ->get();
        return response()->json($result);
    }

    //
    //
    public function lecturaEvent($dia,$sala) 
    {
        $sub = DB::table('_t_reserves_dies as dia')
            ->leftJoin('_t_reserves as res', 'dia.id_reserva', '=', 'res.id')
            ->select(
                'dia.hora_inici',
                'dia.hora_fi',
                'res.sala'
            )
            ->whereNull('dia.data_delete')
            ->where('dia.dia_inici', $dia)
            ->groupBy('res.sala', 'dia.hora_inici', 'dia.hora_fi');

        $result = DB::table('_t_hores as h')
            ->join('_t_sales as sal', function ($join) use ($sala) {
                $join->on('h.tipus', '=', 'sal.horari')
                    ->where('sal.id', '=', $sala)
                    ->where('sal.actiu', '=', 'SI');
            })
            ->leftJoinSub($sub, 'r', function ($join) {
                $join->on('h.hora', '=', 'r.hora_inici')
                    ->on('r.sala', '=', 'sal.id');
            })
            ->select(
                'sal.id',
                'sal.descripcio',
                DB::raw('h.hora as hora_inici'),
                DB::raw("COALESCE(r.hora_fi,'No informado') as estado"),
                'h.tipus'
            )
            ->where('h.activa', 'SI')
            ->orderBy('sal.descripcio')
            ->orderBy('h.hora')
            ->get();
        return response()->json($result);
    }

    //
    //
    public function miraReserva($dia,$sala,$reserva) 
    {        
        $sub = DB::table('_t_reserves_dies as dia')
            ->leftJoin('_t_reserves as res', 'dia.id_reserva', '=', 'res.id')
            ->select(
                'dia.hora_inici',
                'dia.hora_fi',
                'res.sala'
            )
            ->whereNull('dia.data_delete')
            ->where('dia.dia_inici', $dia)
            ->where('res.id', $reserva)
            ->groupBy('res.sala', 'dia.hora_inici', 'dia.hora_fi');
        $result = DB::table('_t_hores as h')
            ->join('_t_sales as sal', function ($join) use ($sala) {
                $join->on('h.tipus', '=', 'sal.horari')
                    ->where('sal.id', '=', $sala)
                    ->where('sal.actiu', '=', 'SI');
            })
            ->leftJoinSub($sub, 'r', function ($join) {
                $join->on('h.hora', '=', 'r.hora_inici')
                    ->on('r.sala', '=', 'sal.id');
            })
            ->select(
                'sal.id',
                'sal.descripcio',
                DB::raw('h.hora as hora_inici'),
                DB::raw("COALESCE(r.hora_fi,'No informado') as estado"),
                'h.tipus'
            )
            ->where('h.activa', 'SI')
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
    
    // POST
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sala' => 'required|integer',
            'dia_inici' => 'required|date',
            'dia_fi' => 'required|date',
            'frecuencia' => 'required|string',        
            'diesSeleccionats' => 'required|string',
            'seleccio_mensual' => 'nullable|string',        
            'dia_seleccionado' => 'nullable|number',
            'el_semana' => 'nullable|string',
            'el_dia' => 'nullable|string',     
            'import' => 'required|numeric',
            'id_user' => 'required|integer',
            'horasAgrupadas' => 'required|string',     
            'complements' => 'nullable|string',            
        ]);
        
        return DB::transaction(function () use ($validated) {
            // Dias
            $start = \Carbon\Carbon::parse($validated['dia_inici']);
            $end   = \Carbon\Carbon::parse($validated['dia_fi'])->addDay();            
    		// $end->modify('+1 day'); // incluir último día
            $setmana = explode('#',$validated['diesSeleccionats']);
            $horas = json_decode($validated['horasAgrupadas'],true);
            $periocitat = $validated['frecuencia'];
            $rows = [];

            // Genero los dias de la reserva.
            for ($dia = $start->copy(); $dia->lt($end); $dia->addDay()) {                                      
                $diaSemana = $dia->format('N'); // 1 = lunes, 7 = domingo                
                $dies['actiu'] = 'SI';
                $dies['dia_inici'] = $dia->format('Y-m-d');
                $dies['dia_fi'] = $dia->format('Y-m-d');
                $insertar = false;
                // diario y un dia solo
                if (in_array($periocitat, ['diahoy','diaria'])) {
                   $insertar = true;
                }       
                // Semanal
                if ($periocitat == 'semanal') {
                   $index = $diaSemana - 1;
                    $insertar = isset($setmana[$index]) && $setmana[$index] == 1;
                }                        
                if ($periocitat == 'mensual') {					
                    $diaSemana = $dia->format('N'); // 1 (lunes) - 7 (domingo)
                    $diaDelMes = $dia->format('j'); // 1-31		
                    $semanaMes = ceil($diaDelMes / 7);					
                    // Semana ultima del mes
                    $ultimoDiaMes = (clone $dia)->modify('last day of this month')->format('j');
                    $ultimaSemana = ceil($ultimoDiaMes / 7);
                    if ($validated['seleccio_mensual'] == 'Dia') {
                        $insertar = true;
                    }
                    if ($validated['seleccio_mensual'] == 'El') {
                        $v_semana = $validated['el_semana'];
                        $d_semana   = $validated['el_dia'];
                        $mapaSemana = ['primero' => 1,'segundo' => 2,'tercero' => 3,'cuarto' => 4,'ultimo' => 5];
                        $valor_semana = $mapaSemana[$v_semana];
                        $mapaDias = ['lunes' => 1,'martes' => 2,'miercoles' => 3,'jueves' => 4,'viernes' => 5, 'sabado' => 6, 'domingo' => 7];
                        $dia_dia_semana = $mapaDias[$d_semana];
                        if (($diaSemana == $dia_dia_semana)&&(($valor_semana == $semanaMes)||($valor_semana == 5 && $semanaMes == $ultimaSemana))) {
                            $insertar = true;
                        }						
                    }
                }
                if ($insertar) {
                    foreach ($horas as $h) {
                        $rows[] = [
                            'id_reserva' => 0,
                            'dia_inici' => $dia->format('Y-m-d'),
                            'dia_fi' => $dia->format('Y-m-d'),
                            'hora_inici' => $h['inicio'],
                            'hora_fi' => $h['final'],
                            'actiu' => 'SI'
                        ];
                    }
                }                
            }
            // Conflicto en la reserva
            $conflictos = DB::table('_t_reserves_dies as dia')
                ->join('_t_reserves as res', 'res.id', '=', 'dia.id_reserva')
                ->where('res.sala', $validated['sala'])
                ->whereNull('dia.data_delete')
                ->whereNull('res.data_delete')
                ->where('dia.actiu','SI')                
                ->whereIn('dia.dia_inici', collect($rows)->pluck('dia_inici')->unique())
                ->select('dia.dia_inici','dia.hora_inici','dia.hora_fi')
                ->get();

            foreach ($rows as $r) {
                foreach ($conflictos as $c) {
                    if (
                        $c->dia_inici == $r['dia_inici'] &&
                        $c->hora_inici < $r['hora_fi'] &&
                        $c->hora_fi > $r['hora_inici']
                    ) {
                        return response()->json([
                            'error' => 'Conflicto de reserva',
                            'detalle' => $r
                        ], 409);
                    }
                }
            }

            // Insertamos en tablas.
            $dias = explode('#', $validated['diesSeleccionats']);        
            $lastId = DB::table('_t_reserves')->insertGetId([
                'sala' => $validated['sala'],
                'dia_inici' => $validated['dia_inici'],
                'dia_fi' => $validated['dia_fi'],
                'import' => $validated['import'],
                'id_user' => $validated['id_user'],
                'frequencia' => $validated['frecuencia'],                
                'dilluns'   => $dias[0],
                'dimarts'   => $dias[1],
                'dimecres'  => $dias[2],
                'dijous'    => $dias[3],
                'divendres' => $dias[4],
                'dissabte'  => $dias[5],
                'diumenge'  => $dias[6],
                'tipo' => $validated['seleccio_mensual'],
                'dia_mes' => $validated['dia_seleccionado'],
                'el_semana' => $validated['el_semana'],
                'el_dia' => $validated['el_dia'],
            ]);

            // Complementos
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
            // Inserto Dias x Batch
            if (!empty($rows)) {
                foreach ($rows as &$r) {
                    $r['id_reserva'] = $lastId;
                }
                unset($r);      
                DB::table('_t_reserves_dies')->insert($rows);
            }            
            
            // Creamos la factura
            $base = $validated['import'];
            $iva = 21;
            $iva_import = round($base * ($iva / 100), 2);
            $total = $base + $iva_import;
                $facturaId = DB::table('_t_factures')->insertGetId([
                    'id_reserva' => $lastId,
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

            // Retornem tot lo grabado para mostrar en calendario
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
                ->where('res.id', $lastId)
                ->orderBy('dia.dia_inici')
                ->orderBy('dia.hora_inici')
                ->get();

            return response()->json($reserves);
        });
    }

    //
    //
    public function lecturaHoras($sala,$id_reserva)  {
        $sub = DB::table('_t_reserves_dies')
            ->select('hora_inici', DB::raw('MAX(hora_fi) as hora_fi'))
            ->whereNull('data_delete')
            ->where('id_reserva', $id_reserva)
            ->groupBy('hora_inici');

        $result = DB::table('_t_hores as h')
            ->join('_t_sales as sal', function ($join) use ($sala) {
                $join->on('sal.horari','=','h.tipus')
                    ->where('sal.id',$sala)
                    ->where('sal.actiu','SI');
            })
            ->leftJoinSub($sub, 'dia', function ($join) {
                $join->on('dia.hora_inici','=','h.hora');
            })
            ->select(
                'sal.id',
                'sal.descripcio',
                DB::raw('h.hora as hora_inici'),
                DB::raw("COALESCE(dia.hora_fi,'No informado') as estado"),
                'h.tipus'
            )
            ->where('h.activa','SI')
            ->orderBy('h.hora')
            ->get();

        return response()->json($result);
    }
    

    public function comprobarDisponibilidad(Request $request)
    {
        $validated = $request->validate([
            'sala' => 'required|integer',
            'dia_inici' => 'required|date',
            'dia_fi' => 'required|date',
            'frecuencia' => 'required|string',
            'diesSeleccionats' => 'required|string',
            'seleccio_mensual' => 'required|string',
            'dia_seleccionado' => 'required|integer',
            'el_semana'  => 'required|string',
            'el_dia' => 'required|string',
        ]);

        // Creo matriz de las horas x Sala.
       $result = DB::table('_t_hores as hor')
        ->join('_t_sales as sal', function ($join) use ($validated) {
            $join->on('hor.tipus', '=', 'sal.horari')
                 ->where('sal.id', '=', $validated['sala']);
        })
        ->select(
            'hor.hora',
            'sal.descripcio',
            'sal.horari'
        )
        ->where('hor.activa', 'SI')
        ->orderBy('hor.hora')
        ->get();
        
        $resultado = [];
        foreach ($result as $h) {
            $resultado[] = [
                'descripcio' => $h->descripcio,
                'estado' => 'No informado',
                'hora_inici' => $h->hora,
                'tipus' => $h->horari,                
            ];
        }

        $setmana = explode('#', $validated['diesSeleccionats']);        
        $periocitat = $validated['frecuencia'];
        $start = \Carbon\Carbon::parse($validated['dia_inici']);
        $end   = \Carbon\Carbon::parse($validated['dia_fi'])->addDay();

        $rows = [];
        for ($dia = $start->copy(); $dia->lt($end); $dia->addDay()) {
            $diaSemana = $dia->dayOfWeekIso;
            $diaDelMes = $dia->day;
            $semanaMes = ceil($diaDelMes / 7);
            $insertar = false;
            if (in_array($periocitat, ['diahoy','diaria'])) {
                $insertar = true;
            }
            if ($periocitat === 'semanal') {
                $index = $diaSemana - 1;
                $insertar = isset($setmana[$index]) && $setmana[$index] == 1;
            }
            if ($periocitat === 'mensual') {
                if ($validated['seleccio_mensual'] === '1') {
                    $insertar = ($diaDelMes == $validated['dia_seleccionado']);
                }
                if ($validated['seleccio_mensual'] === '2') {
                    $mapaSemana = ['primero'=>1,'segundo'=>2,'tercero'=>3,'cuarto'=>4,'ultimo'=>5];
                    $mapaDias = ['lunes'=>1,'martes'=>2,'miercoles'=>3,'jueves'=>4,'viernes'=>5,'sabado'=>6,'domingo'=>7];
                    $valorSemana = $mapaSemana[$validated['el_semana']] ?? null;
                    $diaObjetivo = $mapaDias[$validated['el_dia']] ?? null;
                    $ultimoDiaMes = $dia->copy()->endOfMonth()->day;
                    $ultimaSemana = ceil($ultimoDiaMes / 7);
                    if (
                        $diaSemana == $diaObjetivo &&
                        ($valorSemana == $semanaMes || ($valorSemana == 5 && $semanaMes == $ultimaSemana))
                    ) {
                        $insertar = true;
                    }
                }
            }
            if ($insertar) {
                $rows[] = [
                   'dia' => $dia->format('Y-m-d'),
                    ];
            }
        }

        if (!empty($rows)) {
            $conflictos = DB::table('_t_reserves_dies as dia')
                ->join('_t_reserves as res', 'res.id', '=', 'dia.id_reserva')
                ->where('res.sala', $validated['sala'])
                ->whereNull('dia.data_delete')
                ->whereNull('res.data_delete')
                ->where('dia.actiu','SI')
                ->whereIn('dia.dia_inici', $rows)
                ->select('dia.dia_inici','dia.hora_inici','dia.hora_fi')
                ->get();

            foreach ($conflictos as $c) {
                foreach ($resultado as &$slot) {
                    if (
                        $slot['hora_inici'] >= $c->hora_inici &&
                        $slot['hora_inici'] < $c->hora_fi
                    ) {
                        $slot['estado'] = $c->hora_fi; // 👈 lo que querías
                    }
                }
            }
        }
        return response()->json($resultado);        
    }

}