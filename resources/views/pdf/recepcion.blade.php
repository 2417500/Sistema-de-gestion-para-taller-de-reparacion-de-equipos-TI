<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Ingreso - {{ $orden->codigo_servicio }}</title>
    <style>
        body { font-family: Courier, monospace; font-size: 11px; color: #111; line-height: 1.4; margin: 10px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 12px; }
        .title { font-size: 14px; font-weight: bold; }
        .section { margin-bottom: 10px; border-bottom: 1px dashed #aaa; padding-bottom: 8px; }
        .section-title { font-weight: bold; text-transform: uppercase; margin-bottom: 4px; }
        .footer { margin-top: 30px; text-align: center; font-size: 9px; }
        .signatures { margin-top: 40px; }
        .sig-box { width: 45%; float: left; text-align: center; border-top: 1px solid #000; padding-top: 4px; font-size: 10px; }
        .clear { clear: both; }

        /* ESTILOS DE GRILLA PARA FOTOS */
        .grid-fotos { margin-top: 10px; width: 100%; }
        .foto-container { width: 48%; display: inline-block; margin-bottom: 10px; text-align: center; vertical-align: top; }
        .foto-img { width: 100%; max-height: 180px; object-fit: cover; border: 1px solid #333; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">SOPORTEC - TALLER DE SERVICIO TÉCNICO</div>
        <div>Comprobante Oficial de Recepción</div>
        <strong>CÓDIGO: {{ $orden->codigo_servicio }}</strong><br>
        <small>Fecha: {{ date('Y-m-d H:i', strtotime($orden->created_at)) }}</small>
    </div>

    <div class="section">
        <div class="section-title">1. Datos del Cliente</div>
        Cliente: {{ $orden->cliente_nombre }}<br>
        Correo: {{ $orden->cliente_email }}<br>
        Teléfono: {{ $orden->cliente_telefono ?? 'N/A' }}
    </div>

    <div class="section">
        <div class="section-title">2. Especificaciones del Hardware</div>
        Equipo: {{ $orden->tipo_equipo }} - {{ $orden->marca }} {{ $orden->modelo }}<br>
        N° Serie / IMEI: {{ $orden->numero_serie }}<br>
        Servicio Solicitado: {{ $orden->tipo_servicio }}
    </div>

    <div class="section">
        <div class="section-title">3. Falla & Compromiso</div>
        Falla Reportada: {{ $orden->falla_reportada }}
    </div>

    <div class="signatures">
        <div class="sig-box">Firma Cliente</div>
        <div class="sig-box" style="float: right;">Firma / Sello Taller</div>
        <div class="clear"></div>
    </div>

    <!-- SECCIÓN DE EVIDENCIA FOTOGRÁFICA DE RECEPCIÓN -->
    @php
        $fotos = !empty($orden->fotos_recepcion) ? json_decode($orden->fotos_recepcion, true) : [];
    @endphp

    @if(is_array($fotos) && count($fotos) > 0)
        <div class="section" style="margin-top: 25px; border-bottom: none;">
            <div class="section-title">4. Evidencia Fotográfica de Entrada</div>
            <div class="grid-fotos">
                @foreach($fotos as $foto)
                    @php
                        $path = storage_path('app/public/' . $foto);
                        $base64 = '';
                        if (file_exists($path)) {
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        }
                    @endphp
                    @if($base64)
                        <div class="foto-container">
                            <img src="{{ $base64 }}" class="foto-img">
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <div class="footer">
        <p>Conservar este comprobante para la entrega o seguimiento del equipo.</p>
    </div>
</body>
</html>