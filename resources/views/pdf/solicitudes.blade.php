<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Solicitud de Salidas</title>
    <style>
        @page {
            margin: 40px;
        }

        body {
            font-family: "Helvetica", "Arial", sans-serif;
            font-size: 12px;
            color: #333;
            position: relative;
        }

        h2 {
            float: left;
            font-size: 22px;
            margin: 0;
            padding: 0;
        }

        h3 {
            clear: both;
            margin: 5px 0 0 0;
            font-weight: normal;
            color: #555;
        }

        .header-info {
            margin-bottom: 30px;
            overflow: hidden;
        }

        .header-info img {
            float: right;
            height: 60px;
        }

        .table-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            page-break-inside: auto;
            background: transparent;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd; /* líneas suaves gris */
            background: rgba(255, 255, 255, 0.8); /* semi-transparente */
        }

        th {
            background-color: rgba(242, 242, 242, 0.9);
            font-weight: bold;
        }

        tbody tr:nth-child(odd) {
            background-color: rgba(250, 250, 250, 0.8);
        }

        /* Marca de agua */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 400px;
            height: 400px;
            margin-left: -200px;
            margin-top: -200px;
            opacity: 0.5; /* visible pero ligera */
            z-index: -1;
        }

        /* Footer dinámico */
        .footer {
            position: fixed;
            bottom: 10px;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #555;
        }

        .page-number:after {
            content: counter(page) " / " counter(pages);
        }
    </style>
</head>

<body>
    <div class="header-info">
        <h2>Solicitud de Material NO {{ $numeroTraslado }}</h2>
        <img src="{{ public_path('storage/logo_dash1.png') }}" alt="Logo">
        <h3>Fecha de generación pdf: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</h3>
        <h3>Proyecto: <b>{{ $proyecto }}</b></h3>
        <h4>Elaborado por: {{$usuarioNombre}} </h4>
        <h4>Fecha Solicitud: {{$fechaTraslado}} </h4>
    </div>

    <div class="table-title">Detalle de Insumos</div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Insumo</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item['Insumo Codigo'] }}</td>
                    <td>{{ $item['Insumo Descripcion'] }}</td>
                    <td>{{ $item['CantidadTotal'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <img class="watermark" src="{{ public_path('storage/marcaGua.jpeg') }}" alt="Watermark">

    <!-- Footer -->
    <div class="footer">
        @if($data->count() <= 20)
            <b>Fin</b>
        @else
            Página <span class="page-number"></span>
        @endif
    </div>
</body>

</html>
