<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nota de Entrega - {{ $orden->codigo_servicio }}</title>
    <style>
        body { font-family: Courier, monospace; font-size: 11px; color: #111; line-height: 1.4; margin: 10px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 12px; }
        .title { font-size: 14px; font-weight: bold; }
        .section { margin-bottom: 10px; border-bottom: 1px dashed #aaa; padding-bottom: 8px; }
        .section-title { font-weight: bold; text-transform: uppercase; margin-bottom: 4px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .table th, .table td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        .text-right { text-align: right; }
        .signatures { margin-top: 40px; }
        .sig-box { width: 45%; float: left; text-align: center; border-top: 1px solid #000; padding-top: 4px; font-size: 10px; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">SOPORTEC - LIQUIDACIÓN Y CONFORMIDAD</div>
        <div>Ticket Definitivo de Entrega</div>
        <strong>CÓDIGO DE ORDEN: {{ $orden->codigo_servicio }}</strong><br>
        <small>Fecha Entrega: {{ date('Y-m-d H:i') }}</small>
    </div>

    <div class="section">
        <div class="section-title">Cliente & Dispositivo</div>
        Cliente: {{ $orden->cliente_nombre }}<br>
        Equipo: {{ $orden->tipo_equipo }} {{ $orden->marca }} {{ $orden->modelo }} ({{ $orden->numero_serie }})
    </div>

    <div class="section">
        <div class="section-title">DESGLOSE DE REFACCIONES E INSUMOS INSTALADOS</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Refacción / Herramienta</th>
                    <th>Cant.</th>
                    <th class="text-right">Precio Unitario</th>
                </tr>
            </thead>
            <tbody>
                @forelse($refacciones ?? [] as $ref)
                    <tr>
                        <td>{{ $ref->nombre }}</td>
                        <td>{{ $ref->cantidad }}</td>
                        <td class="text-right">${{ number_format($ref->precio_unitario, 2) }} MXN</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Servicio de mantenimiento / reparación general.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Detalle de Costos y Pagos</div>
        <table class="table">
            <tr>
                <th>Concepto</th>
                <th class="text-right">Monto</th>
            </tr>
            <tr>
                <td>Mano de Obra</td>
                <td class="text-right">${{ number_format($orden->costo_mano_obra ?? 0, 2) }} MXN</td>
            </tr>
            <tr>
                <td>Refacciones / Insumos</td>
                <td class="text-right">${{ number_format($orden->costo_refacciones ?? 0, 2) }} MXN</td>
            </tr>
            <tr>
                <th>TOTAL SERVICIO</th>
                <th class="text-right">${{ number_format($orden->costo_total ?? 0, 2) }} MXN</th>
            </tr>
            <tr>
                <td>Anticipo Cobrado (50%)</td>
                <td class="text-right">-${{ number_format($orden->anticipo ?? 0, 2) }} MXN</td>
            </tr>
            <tr>
                <th>LIQUIDADO EN CAJA</th>
                <th class="text-right">${{ number_format(($orden->costo_total ?? 0) - ($orden->anticipo ?? 0), 2) }} MXN</th>
            </tr>
        </table>
    </div>

    <div class="signatures">
        <div class="sig-box">Conformidad Cliente</div>
        <div class="sig-box" style="float: right;">Firma Cajero / Taller</div>
        <div class="clear"></div>
    </div>
</body>
</html>