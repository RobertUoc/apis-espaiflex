<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    // Mostrar Events del Calendari
    // Paso Any i Edidifi
    public function lecturaReserves($any, $edifici)
    {
       $reserves = DB::table('_t_reserves as res')
        ->leftJoin('_t_sales as sal', 'res.sala', '=', 'sal.id')
        ->select(
            'res.id',
            'res.dia_inici',
            'res.dia_fi',
            'res.hora_inici',
            'res.hora_fi',
            'sal.color',
            'sal.descripcio',
            'res.sala'
        )
        ->where('res.actiu', 'SI')
        ->whereNull('res.data_delete')
        ->whereYear('res.dia_inici', $any)
        ->where('sal.id_edifici', $edifici)
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
    public function lecturaEvent($dia,$ala) 
    {

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
        $ok = DB::table('_t_reserves')
                ->where('id', $id)
                ->update([
                    'data_delete' => now(),
                    'actiu' => 'NO'
                ]);

            return response()->json([
                'success' => $ok ? true : false,
                'id' => $id
            ]);        
    }

}