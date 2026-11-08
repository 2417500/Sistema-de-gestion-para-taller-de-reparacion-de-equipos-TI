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
        .foto-img { width: 100%; max-height: 180px; object-fit: contain; border: 1px solid #333; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">SOPORTEC - TALLER DE SERVICIO TÉCNICO</div>
        <div>Comprobante Oficial de Recepción</div>
        <strong>CÓDIGO: {{ $orden->codigo_servicio }}</strong><br>
        <small>Fecha: {{ date('Y-m-d H:i', strtotime($orden->created_at ?? now())) }}</small>
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
        $fotos = [];
        if (!empty($orden->fotos_recepcion)) {
            $decoded = json_decode($orden->fotos_recepcion, true);
            $fotos = is_array($decoded) ? $decoded : explode(',', $orden->fotos_recepcion);
        }
    @endphp

    @if(count($fotos) > 0)
        <div class="section" style="margin-top: 25px; border-bottom: none; page-break-inside: avoid;">
            <div class="section-title">4. Evidencia Fotográfica de Entrada</div>
            <div class="grid-fotos">
                @foreach($fotos as $foto)
                    @php
                        $fotoLimpia = trim(str_replace('\\', '/', $foto));
                        // Intentar obtener la ruta desde storage app public o public storage
                        $pathApp = storage_path('app/public/' . $fotoLimpia);
                        $pathPublic = public_path('storage/' . $fotoLimpia);
                        
                        $pathFinal = file_exists($pathApp) ? $pathApp : (file_exists($pathPublic) ? $pathPublic : null);
                        $srcImg = '';

                        if ($pathFinal) {
                            $type = pathinfo($pathFinal, PATHINFO_EXTENSION);
                            $data = file_get_contents($pathFinal);
                            $srcImg = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        }
                    @endphp

                    <div class="foto-container">
                        @if(!empty($srcImg))
                            <img src="{{ $srcImg }}" class="foto-img">
                        @else
                            <div style="border: 1px dashed #999; padding: 20px; font-size: 8px; color: #666;">
                                [Imagen no encontrada]
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="footer">
        <p>Conservar este comprobante para la entrega o seguimiento del equipo.</p>
    </div>
</body>
</html>