<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\FacturaMail;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        return DB::table('_t_factures as fra')
        ->join('_t_reserves as res', 'fra.id_reserva', '=', 'res.id')
        ->leftJoin('_t_sales as sal', 'res.sala', '=', 'sal.id')
        ->leftJoin('users as usr', 'res.id_user', '=', 'usr.id')
        ->select(
            'fra.id',
            'fra.id_reserva',
            'fra.data_factura',
            'fra.base',
            'fra.iva',
            'fra.iva_import',
            'fra.total_factura',
            'fra.enviada',
            'res.sala',
            'res.id_user',
            'sal.descripcio as nom_sala',
            'usr.name as nom_user'
        )
        ->orderBy('fra.data_factura', 'desc')
        ->get();

    }

    public function show($id)
    {

        $rows = DB::table('_t_factures as fra')
            ->leftJoin('_t_reserves as res', 'res.id', '=', 'fra.id_reserva')
            ->leftJoin('_t_sales as sal', 'res.sala', '=', 'sal.id')
            ->leftJoin('users as usr', 'res.id_user', '=', 'usr.id')
            ->leftJoin('_t_reserves_in_complements as rco', 'rco.id_reserves', '=', 'res.id')
            ->leftJoin('_t_complements as com', 'rco.id_complements', '=', 'com.id')
            ->select(
                'fra.*',
                'res.dia_inici',
                'res.dia_fi',
                'sal.descripcio as nom_sala',
                'usr.name as nom_user',
                'usr.email as email_user',
                'com.descripcio as nom_complement',
                'com.preu as preu_complement'
            )
            ->where('fra.id', $id)
            ->get();

        // base
        $factura = (array) $rows[0];
        // complementos
        $factura['complements'] = $rows
            ->filter(fn($r) => $r->nom_complement !== null)
            ->map(fn($r) => [
                'nombre' => $r->nom_complement,
                'precio' => $r->preu_complement
            ])
            ->values();

        return $factura;        
    }

    public function enviarEmail($id)
    {
        $factura = DB::table('_t_factures as fra')
            ->join('_t_reserves as res', 'fra.id_reserva', '=', 'res.id')
            ->leftJoin('_t_sales as sal', 'res.sala', '=', 'sal.id')
            ->leftJoin('users as usr', 'res.id_user', '=', 'usr.id')
            ->select(
                'fra.*',
                'sal.descripcio as nom_sala',
                'usr.name as nom_user',
                'usr.email as email_user'
            )
            ->where('fra.id', $id)
            ->first();

        if (!$factura) {
            return response()->json(['error' => 'Factura no encontrada'], 404);
        }

        $complements = DB::table('_t_reserves_in_complements as rco')
            ->join('_t_complements as com', 'rco.id_complements', '=', 'com.id')
            ->where('rco.id_reserves', $factura->id_reserva)
            ->select(
                'com.descripcio',
                'com.preu'
            )
            ->get();
                    
        $factura->complements = $complements;

        // Generar PDF
        $pdf = Pdf::loadView('pdf.factura', ['factura' => $factura]);

        // Enviar email
        Mail::to($factura->email_user)
            ->send(new FacturaMail($factura, $pdf->output()));

        // Marcar como enviada
        DB::table('_t_factures')
            ->where('id', $id)
            ->update(['enviada' => 1]);

        return response()->json(['success' => true]);
    }

    // Años disponibles
    public function anios()
    {
        return Factura::selectRaw('YEAR(data_factura) as anio')
            ->distinct()
            ->orderBy('anio', 'desc')
            ->pluck('anio');
    }

    // Facturación agrupada por mes
    public function porMes($anio)
    {
        return Factura::selectRaw('
                MONTH(data_factura) as mes,
                SUM(total_factura) as total
            ')
            ->whereYear('data_factura', $anio)
            ->groupByRaw('MONTH(data_factura)')
            ->orderBy('mes')
            ->get();
    }

    // Facturación agrupada por día
    public function porDia($anio, $mes)
    {
        return Factura::selectRaw('
                DAY(data_factura) as dia,
                SUM(total_factura) as total
            ')
            ->whereYear('data_factura', $anio)
            ->whereMonth('data_factura', $mes)
            ->groupByRaw('DAY(data_factura)')
            ->orderBy('dia')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_reserva'    => 'required|integer',            
            'data_factura'  => 'required|date',
            'dia'           => 'required|numeric',
            'precio_dia'    => 'required|numeric',
            'base'          => 'required|numeric',
            'iva'           => 'required|numeric',
            'iva_import'    => 'required|numeric',
            'total_factura' => 'required|numeric'
        ]);

        $id = DB::table('_t_factures')->insertGetId([
            'id_reserva' => $validated['id_reserva'],            
            'data_factura' => $validated['data_factura'],
            'dia' => $validated['dia'],
            'precio_dia' => $validated['precio_dia'],
            'base' => $validated['base'],
            'iva' => $validated['iva'],
            'iva_import' => $validated['iva_import'],
            'total_factura' => $validated['total_factura'],
            'created_at' => now(),
            'updated_at' => now(),
            'enviada' => 0
        ]);

        $this->enviarEmail($id);
                
        return response()->json([
            'success' => true,
            'id' => $id
        ]);
    }        

}