<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Recepción - {{ $orden->codigo_servicio ?? 'ST-2026' }}</title>
    <style>
        @page {
            margin: 15px;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10px;
            color: #111;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .subtitle {
            font-size: 9px;
            color: #444;
        }
        .codigo-box {
            margin-top: 3px;
            font-weight: bold;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .section {
            margin-bottom: 12px;
            clear: both;
        }
        .section-title {
            font-weight: bold;
            font-size: 10.5px;
            margin-bottom: 6px;
            text-transform: uppercase;
            background-color: #e5e5e5;
            padding: 4px 6px;
            border-left: 3px solid #000;
            display: block;
            clear: both;
        }
        .grid {
            width: 100%;
            border-collapse: collapse;
        }
        .grid td {
            vertical-align: top;
            padding: 2px 0;
        }
        .label {
            font-weight: bold;
            color: #222;
        }
        .falla-box {
            background-color: #fafafa;
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 9px;
            white-space: pre-line;
            margin-top: 2px;
        }
        .policy {
            border: 1px solid #777;
            padding: 6px;
            font-size: 8.5px;
            background-color: #f9f9f9;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        .signatures {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }
        .signatures td {
            text-align: center;
            width: 50%;
            vertical-align: bottom;
        }
        .line {
            border-top: 1px solid #000;
            width: 75%;
            margin: 0 auto 4px auto;
        }

        /* TABLA Y FORMATO GRANDE DE IMÁGENES (2 COLUMNAS) */
        .fotos-table {
            width: 100%;
            margin-top: 6px;
            border-collapse: collapse;
        }
        .fotos-table td {
            text-align: center;
            vertical-align: middle;
            padding: 6px;
        }
        .img-box {
            border: 1px solid #999;
            padding: 4px;
            background: #fff;
            display: inline-block;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .img-box img {
            width: 220px;
            height: 150px;
            object-fit: cover;
            display: block;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="title">TECNOFIX SOLUTIONS // SOPORTEC V4.2</div>
        <div class="subtitle">COMPROBANTE OFICIAL DE INGRESO A TALLER</div>
        <div class="codigo-box">CÓDIGO: {{ $orden->codigo_servicio }}</div>
        <div class="subtitle">FECHA REGISTRO: {{ $orden->created_at ?? date('Y-m-d H:i') }}</div>
    </div>

    <!-- 1. DATOS CLIENTE -->
    <div class="section">
        <div class="section-title">1. DATOS DEL CLIENTE</div>
        <table class="grid">
            <tr>
                <td width="50%"><span class="label">CLIENTE:</span> {{ $orden->cliente_nombre ?? 'N/A' }}</td>
                <td width="50%"><span class="label">EMAIL:</span> {{ $orden->cliente_email ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><span class="label">TELÉFONO:</span> {{ $orden->cliente_telefono ?? 'N/A' }}</td>
                <td><span class="label">RECEPCIONADO POR:</span> {{ Auth::user()->name ?? 'Recepción' }}</td>
            </tr>
        </table>
    </div>

    <!-- 2. HARDWARE -->
    <div class="section">
        <div class="section-title">2. ESPECIFICACIONES DEL HARDWARE</div>
        <table class="grid">
            <tr>
                <td width="50%"><span class="label">TIPO DISPOSITIVO:</span> {{ $orden->tipo_equipo ?? 'Laptop' }}</td>
                <td width="50%"><span class="label">MARCA / MODELO:</span> {{ $orden->marca ?? '' }} {{ $orden->modelo ?? '' }}</td>
            </tr>
            <tr>
                <td><span class="label">Nº SERIE / IMEI:</span> {{ $orden->numero_serie ?? 'SN-N/A' }}</td>
                <td><span class="label">TIPO DE SERVICIO:</span> {{ $orden->tipo_servicio ?? 'Diagnostico' }}</td>
            </tr>
        </table>
    </div>

    <!-- 3. FALLA & REPORTES -->
    <div class="section">
        <div class="section-title">3. FALLA & REPORTE INICIAL</div>
        <div class="falla-box">{{ $orden->falla_reportada }}</div>
    </div>

    <!-- PROCESAMIENTO ESTRICTO DE IMÁGENES CON BASE64 -->
    @php
        $fotosRaw = json_decode($orden->fotos_recepcion ?? '[]', true);
        $fotosEquipoValidas = [];
        $fotosExtrasValidas = [];

        function obtenerBase64SiExiste($fotoPath) {
            if (!is_string($fotoPath) || empty(trim($fotoPath))) return null;
            $fotoLimpia = trim(str_replace('\\', '/', $fotoPath));
            
            $pathApp = storage_path('app/public/' . $fotoLimpia);
            $pathPublic = public_path('storage/' . $fotoLimpia);
            $pathDirect = public_path($fotoLimpia);

            $pathFinal = null;
            if (file_exists($pathApp) && is_file($pathApp)) $pathFinal = $pathApp;
            elseif (file_exists($pathPublic) && is_file($pathPublic)) $pathFinal = $pathPublic;
            elseif (file_exists($pathDirect) && is_file($pathDirect)) $pathFinal = $pathDirect;

            if ($pathFinal) {
                $type = pathinfo($pathFinal, PATHINFO_EXTENSION);
                $data = @file_get_contents($pathFinal);
                if ($data !== false && strlen($data) > 0) {
                    return 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            }
            return null;
        }

        if (is_array($fotosRaw)) {
            if (isset($fotosRaw['equipo']) && is_array($fotosRaw['equipo'])) {
                foreach ($fotosRaw['equipo'] as $f) {
                    $b64 = obtenerBase64SiExiste($f);
                    if ($b64) $fotosEquipoValidas[] = $b64;
                }
            }
            if (isset($fotosRaw['extras']) && is_array($fotosRaw['extras'])) {
                foreach ($fotosRaw['extras'] as $f) {
                    $b64 = obtenerBase64SiExiste($f);
                    if ($b64) $fotosExtrasValidas[] = $b64;
                }
            }
            if (empty($fotosEquipoValidas) && empty($fotosExtrasValidas)) {
                array_walk_recursive($fotosRaw, function($val) use (&$fotosEquipoValidas) {
                    $b64 = obtenerBase64SiExiste($val);
                    if ($b64) $fotosEquipoValidas[] = $b64;
                });
            }
        }
    @endphp

    <!-- 4. EVIDENCIA FOTOGRÁFICA DEL EQUIPO -->
    @if(count($fotosEquipoValidas) > 0)
        <div class="section">
            <div class="section-title">4. EVIDENCIA FOTOGRÁFICA DEL EQUIPO</div>
            <table class="fotos-table">
                <tr>
                    @php $colCount = 0; @endphp
                    @foreach($fotosEquipoValidas as $srcImg)
                        @if($colCount > 0 && $colCount % 2 == 0)
                            </tr><tr>
                        @endif
                        <td width="50%">
                            <div class="img-box">
                                <img src="{{ $srcImg }}">
                            </div>
                        </td>
                        @php $colCount++; @endphp
                    @endforeach
                </tr>
            </table>
        </div>
    @endif

    <!-- 5. EVIDENCIA DE ACCESORIOS & COMPONENTES EXTRAS -->
    @if(count($fotosExtrasValidas) > 0)
        <div class="section">
            <div class="section-title">5. EVIDENCIA DE ACCESORIOS & COMPONENTES EXTRAS</div>
            <table class="fotos-table">
                <tr>
                    @php $colCount = 0; @endphp
                    @foreach($fotosExtrasValidas as $srcImg)
                        @if($colCount > 0 && $colCount % 2 == 0)
                            </tr><tr>
                        @endif
                        <td width="50%">
                            <div class="img-box">
                                <img src="{{ $srcImg }}">
                            </div>
                        </td>
                        @php $colCount++; @endphp
                    @endforeach
                </tr>
            </table>
        </div>
    @endif

    <!-- POLÍTICAS -->
    <div class="policy">
        <strong>POLÍTICA DE SERVICIO & CONDICIONES:</strong><br>
        - Diagnóstico gratuito de 24 a 48 horas.<br>
        - Es requisito indispensable el pago del anticipo del 50% una vez aceptada la cotización para iniciar los trabajos físicos.<br>
        - Presentar este comprobante impreso o en digital para el retiro del equipo.
    </div>

    <!-- FIRMAS -->
    <table class="signatures">
        <tr>
            <td>
                <div class="line"></div>
                <strong>FIRMA CLIENTE</strong>
            </td>
            <td>
                <div class="line"></div>
                <strong>SELLO / FIRMA TALLER</strong>
            </td>
        </tr>
    </table>

</body>
</html>