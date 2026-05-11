<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="utf-8">
    <title>Factura</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            color: #212529;
            margin: 40px;
            -webkit-print-color-adjust: exact;
        }

        .container {
            width: 100%;
        }

        .row {
            width: 100%;            
        }

        .col-left {
            width: 55%;
            float: left;
        }

        .col-right {
            width: 40%;
            float: right;
            text-align: right;
        }

        .title {
            color: #0d6efd;
            margin: 0;
        }

        .empresa p,
        .client-box p {
            margin: 4px 0;
        }

        .card {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 25px;
            background: #f8f9fa;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table td {
            padding: 10px;
            border: 1px solid #dee2e6;
        }

        .totals {
            width: 320px;
            float: right;
            margin-top: 25px;
            border-collapse: collapse;
        }

        .totals td {
            padding: 10px;
            border: 1px solid #dee2e6;
        }

        .total-final {
            font-weight: bold;
            font-size: 16px;
            background: #f1f5f9;
        }

    </style>

</head>

<body>

<div class="container">

    <!-- HEADER -->
    <div class="row">

        <!-- EMPRESA -->
        <div class="col-left empresa">

            <h2 class="title">
                EspaiFlex Solutions S.L.
            </h2>

            <p>
                <strong>CIF:</strong> B12345678
            </p>

            <p>
                Rambla Sant Jordi, 42
            </p>

            <p>
                25123 Torrefarrera
            </p>

            <p>
                Lleida
            </p>

            <p>
                info@espaiflex.cat
            </p>

        </div>

        <!-- FACTURA -->
        <div class="col-right">

            <h1 class="title">
                FACTURA
            </h1>

            <p style="margin-top:20px;">
                <strong>Número factura</strong>
            </p>

            <p>
                FAC-{{ str_pad($factura->id, 5, '0', STR_PAD_LEFT) }}
            </p>

            <p style="margin-top:15px;">
                <strong>Data</strong>
            </p>

            <p>
                {{ \Carbon\Carbon::parse($factura->data_factura)->format('d/m/Y') }}
            </p>

        </div>

    </div>

    <!-- CLEAR -->
    <div style="clear: both; margin-bottom: 35px;"></div>

    <!-- CLIENT -->
    <div class="card">

        <h3 style="margin-top:0;">
            Dades del client
        </h3>

        <p>
            <strong>Nom:</strong>
            {{ $factura->nom_user }}
        </p>

        <p>
            <strong>Email:</strong>
            {{ $factura->email_user }}
        </p>

    </div>

    <!-- TAULA -->
    <table class="table">

        <thead>

            <tr>

                <th style="
                    background: #0d6efd;
                    color: white;
                    padding: 10px;
                    text-align: left;
                    border: 1px solid #dee2e6;
                ">
                    Concepte
                </th>

                <th width="150" style="
                    background: #0d6efd;
                    color: white;
                    padding: 10px;
                    text-align: left;
                    border: 1px solid #dee2e6;
                ">
                    Import
                </th>

            </tr>

        </thead>

        <tbody>

            <tr>

                <td>
                    Reserva sala:
                    {{ $factura->nom_sala }}
                </td>

                <td>
                    {{ number_format($factura->base, 2) }} €
                </td>

            </tr>

            @if(isset($factura->complements))

                @foreach($factura->complements as $complement)

                    <tr>

                        <td>
                            Complement:
                            {{ $complement->descripcio }}
                        </td>

                        <td>
                            {{ number_format($complement->preu, 2) }} €
                        </td>

                    </tr>

                @endforeach

            @endif

        </tbody>

    </table>

    <!-- TOTALS -->
    <table class="totals">

        <tr>

            <td>
                Base imposable
            </td>

            <td>
                {{ number_format($factura->base, 2) }} €
            </td>

        </tr>

        <tr>

            <td>
                IVA ({{ $factura->iva }}%)
            </td>

            <td>
                {{ number_format($factura->iva_import, 2) }} €
            </td>

        </tr>

        <tr class="total-final">

            <td>
                Total
            </td>

            <td>
                {{ number_format($factura->total_factura, 2) }} €
            </td>

        </tr>

    </table>

</div>

</body>

</html>