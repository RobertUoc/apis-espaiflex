<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = '_t_factures';

    protected $fillable = [
        'id_reserva',
        'data_factura',
        'dias',
        'precio_dia',
        'base',
        'iva',
        'iva_import',
        'total_factura',
        'enviada',
    ];
}
