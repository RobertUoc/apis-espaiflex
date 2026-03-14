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

<p>Base: {{ $factura->base }} €</p>
<p>IVA: {{ $factura->iva_import }} €</p>
<p><strong>Total: {{ $factura->total_factura }} €</strong></p>

</body>
</html>