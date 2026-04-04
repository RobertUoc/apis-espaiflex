<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura</title>
</head>
<body>

<h2>Factura Nº {{ $factura->id }}</h2>

<p><strong>Cliente:</strong> {{ $factura->nom_user }}</p>
<p><strong>Sala:</strong> {{ $factura->nom_sala }}</p>
<p><strong>Fecha:</strong> {{ $factura->data_factura }}</p>

<hr>

<table width="100%" border="1" cellspacing="0" cellpadding="6">
    <thead>
        <tr>
            <th>Días</th>
            <th align="right">Precio</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $factura->dias }}</td>
            <td align="right">{{ number_format($factura->precio_dia, 2) }} €</td>
        </tr>
    </tbody>
</table>

<br>

@if(!empty($factura->complements) && count($factura->complements) > 0)

<table width="100%" border="1" cellspacing="0" cellpadding="6">
    <thead>
        <tr>
            <th>Complementos</th>
            <th align="right">Precio</th>
        </tr>
    </thead>
    <tbody>
        @foreach($factura->complements as $c)
        <tr>
            <td>{{ $c->descripcio }}</td>
            <td align="right">{{ number_format($c->preu, 2) }} €</td>
        </tr>
        @endforeach
    </tbody>
</table>
<br>
@endif

<table width="100%" border="1" cellspacing="0" cellpadding="6">
    <thead>
        <tr>
            <th>Base</th>
            <th>IVA ({{ $factura->iva }}%)</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ number_format($factura->base, 2) }} €</td>
            <td>{{ number_format($factura->iva_import, 2) }} €</td>
            <td align="right"><strong>{{ number_format($factura->total_factura, 2) }} €</strong></td>
        </tr>
    </tbody>
</table>


</body>
</html>