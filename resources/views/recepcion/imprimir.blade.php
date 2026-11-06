<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Servicio - {{ $orden->codigo_servicio }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 font-mono min-h-screen p-4 md:p-8">

    <div class="max-w-3xl mx-auto mb-6 flex justify-between items-center no-print">
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-800 text-white rounded text-xs hover:bg-gray-700 transition-colors uppercase font-bold">
            ← Ir al Dashboard
        </a>
        <div class="flex gap-2">
            <a href="{{ route('recepcion.pdf', $orden->id) }}" class="px-4 py-2 bg-amber-500 text-black rounded text-xs font-bold hover:bg-amber-400 transition-colors uppercase">
                Descargar PDF
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-black text-white rounded text-xs font-bold hover:bg-gray-800 transition-colors uppercase">
                Imprimir Comprobante
            </button>
        </div>
    </div>

    <div class="max-w-3xl mx-auto bg-white border border-gray-300 p-8 shadow-sm">
        <div class="text-center border-b border-gray-400 pb-4 mb-6">
            <h1 class="text-xl font-bold uppercase tracking-wider">TECNOFIX SOLUTIONS // SOPORTEC V4.2</h1>
            <p class="text-xs text-gray-600 uppercase">Comprobante Oficial de Ingreso a Taller</p>
            <div class="mt-2 text-sm font-bold text-gray-800">CÓDIGO: {{ $orden->codigo_servicio }}</div>
            <p class="text-[10px] text-gray-500">FECHA REGISTRO: {{ $orden->created_at }}</p>
        </div>

        <div class="space-y-6 text-xs">
            <!-- 1. DATOS DEL CLIENTE -->
            <div class="border-b border-gray-200 pb-4">
                <h2 class="font-bold uppercase text-gray-700 mb-2">1. DATOS DEL CLIENTE</h2>
                <div class="grid grid-cols-2 gap-2">
                    <div><span class="text-gray-500">CLIENTE:</span> {{ $orden->cliente_nombre }}</div>
                    <div><span class="text-gray-500">EMAIL:</span> {{ $orden->cliente_email }}</div>
                    <div><span class="text-gray-500">TELÉFONO:</span> {{ $orden->cliente_telefono ?? 'N/A' }}</div>
                    <div><span class="text-gray-500">RECEPCIONADO POR:</span> {{ $orden->recepcionista ?? 'Staff' }}</div>
                </div>
            </div>

            <!-- 2. ESPECIFICACIONES -->
            <div class="border-b border-gray-200 pb-4">
                <h2 class="font-bold uppercase text-gray-700 mb-2">2. ESPECIFICACIONES DEL HARDWARE</h2>
                <div class="grid grid-cols-2 gap-2">
                    <div><span class="text-gray-500">TIPO DISPOSITIVO:</span> {{ $orden->tipo_equipo }}</div>
                    <div><span class="text-gray-500">MARCA / MODELO:</span> {{ $orden->marca }} {{ $orden->modelo }}</div>
                    <div><span class="text-gray-500">N° SERIE / IMEI:</span> {{ $orden->numero_serie }}</div>
                    <div><span class="text-gray-500">TIPO DE SERVICIO:</span> {{ $orden->tipo_servicio }}</div>
                </div>
            </div>

            <!-- 3. FALLA -->
            <div class="border-b border-gray-200 pb-4">
                <h2 class="font-bold uppercase text-gray-700 mb-2">3. FALLA & REPORTE INICIAL</h2>
                <p class="whitespace-pre-line text-gray-800">{{ $orden->falla_reportada }}</p>
            </div>

            <!-- POLÍTICA -->
            <div class="border border-gray-300 p-4 rounded text-[10px] text-gray-600 space-y-1 bg-gray-50">
                <p class="font-bold uppercase text-gray-800">POLÍTICA DE SERVICIO & CONDICIONES:</p>
                <p>- Diagnóstico gratuito de 24 a 48 horas.</p>
                <p>- Es requisito indispensable el pago de al menos el 50% de anticipo una vez aceptada la cotización para iniciar trabajos físicos.</p>
                <p>- Presentar este comprobante impreso o en digital para el retiro del equipo.</p>
            </div>

            <!-- FIRMAS -->
            <div class="pt-12 grid grid-cols-2 gap-8 text-center text-[10px]">
                <div class="border-t border-gray-400 pt-2">FIRMA CLIENTE</div>
                <div class="border-t border-gray-400 pt-2">SELLO / FIRMA TALLER</div>
            </div>
        </div>
    </div>

</body>
</html>