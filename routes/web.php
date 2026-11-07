<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

/*
|--------------------------------------------------------------------------
| Web Routes - SoporTec (Flujo Completo Sincronizado + Módulo Herramientas + Fotos Entrada/Salida)
|--------------------------------------------------------------------------
*/

// 1. Rutas Públicas (Portal y Seguimiento de Cliente)
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/seguimiento', function () {
    $codigo = request('codigo');
    $orden = null;
    $refaccionesCotizadas = [];
    
    // Si el usuario logueado es CLIENTE y no especifica código manual, consulta su equipo automáticamente
    if (!$codigo && Auth::check()) {
        $user = Auth::user();
        $rol = strtolower(trim($user->role->nombre ?? ''));
        if (str_contains($rol, 'client')) {
            $cliente = DB::table('clientes')->where('user_id', $user->id)->first();
            if ($cliente) {
                $equipo = DB::table('equipos')->where('cliente_id', $cliente->id)->latest()->first();
                if ($equipo) {
                    $codigo = $equipo->numero_serie;
                }
            }
        }
    }

    if ($codigo) {
        $orden = DB::table('orden_servicios')
            ->leftJoin('equipos', 'orden_servicios.equipo_id', '=', 'equipos.id')
            ->leftJoin('clientes', 'equipos.cliente_id', '=', 'clientes.id')
            ->leftJoin('users', 'clientes.user_id', '=', 'users.id')
            ->select(
                'orden_servicios.*',
                'equipos.marca',
                'equipos.modelo',
                'equipos.numero_serie',
                'equipos.tipo_equipo',
                'users.name as cliente_nombre',
                'users.email as cliente_email',
                'users.telefono as cliente_telefono'
            )
            ->where('orden_servicios.codigo_servicio', $codigo)
            ->orWhere('equipos.numero_serie', $codigo)
            ->first();

        if ($orden) {
            $refaccionesCotizadas = DB::table('solicitudes_repuestos')
                ->join('inventario', 'solicitudes_repuestos.item_id', '=', 'inventario.id')
                ->where('solicitudes_repuestos.orden_id', $orden->id)
                ->select('inventario.nombre', 'solicitudes_repuestos.cantidad', 'inventario.precio_unitario')
                ->get();
        }
    }
    
    return view('welcome', compact('orden', 'codigo', 'refaccionesCotizadas'));
})->name('seguimiento');

// RESPUESTA DEL CLIENTE: ACEPTAR Ó RECHAZAR COTIZACIÓN
Route::post('/cotizacion/responder', function () {
    $ordenId = request('orden_id');
    $accion = request('accion');
    $nota = request('nota_cliente');

    $ordenActual = DB::table('orden_servicios')->where('id', $ordenId)->first();
    $notaActualizada = ($ordenActual->falla_reportada ?? '') . "\n\n[Respuesta Cliente " . date('Y-m-d H:i') . "]: " . $nota;

    if ($accion === 'rechazar') {
        DB::table('orden_servicios')->where('id', $ordenId)->update([
            'estado' => 'Cancelado',
            'falla_reportada' => $notaActualizada,
            'updated_at' => now(),
        ]);
        DB::table('solicitudes_repuestos')->where('orden_id', $ordenId)->update(['estado' => 'RECHAZADO']);
        return back()->with('success', 'Has RECHAZADO la cotización. La orden ha sido cancelada.');
    } else {
        $notaAceptada = $notaActualizada . "\n[ESTADO COTIZACIÓN]: ACEPTADA POR EL CLIENTE";
        DB::table('orden_servicios')->where('id', $ordenId)->update([
            'estado' => 'En_Proceso',
            'falla_reportada' => $notaAceptada,
            'updated_at' => now(),
        ]);
        return back()->with('success', '¡Cotización ACEPTADA! El técnico comenzará la reparación tras la autorización de Almacén.');
    }
})->name('cotizacion.responder');

// Impresión de Ticket de Recepción
Route::get('/recepcion/imprimir/{id}', function ($id) {
    $orden = DB::table('orden_servicios')
        ->leftJoin('equipos', 'orden_servicios.equipo_id', '=', 'equipos.id')
        ->leftJoin('clientes', 'equipos.cliente_id', '=', 'clientes.id')
        ->leftJoin('users as cliente_user', 'clientes.user_id', '=', 'cliente_user.id')
        ->leftJoin('users as recepcion_user', 'orden_servicios.recepcion_id', '=', 'recepcion_user.id')
        ->select(
            'orden_servicios.*',
            'equipos.marca',
            'equipos.modelo',
            'equipos.numero_serie',
            'equipos.tipo_equipo',
            'cliente_user.name as cliente_nombre',
            'cliente_user.email as cliente_email',
            'cliente_user.telefono as cliente_telefono',
            'recepcion_user.name as recepcionista'
        )
        ->where('orden_servicios.id', $id)
        ->first();

    return view('recepcion.imprimir', compact('orden'));
})->name('recepcion.imprimir');


// 2. Rutas Autenticadas (Panel Privado)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // DASHBOARD PRINCIPAL CON DIRECCIONAMIENTO POR ROL
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $rolNombre = strtolower(trim($user->role->nombre ?? 'cliente'));

        if (str_contains($rolNombre, 'client')) {
            $cliente = DB::table('clientes')->where('user_id', $user->id)->first();
            
            $ordenes = DB::table('orden_servicios')
                ->join('equipos', 'orden_servicios.equipo_id', '=', 'equipos.id')
                ->where('equipos.cliente_id', $cliente->id ?? 0)
                ->select('orden_servicios.*', 'equipos.marca', 'equipos.modelo', 'equipos.numero_serie', 'equipos.tipo_equipo')
                ->orderBy('orden_servicios.id', 'desc')
                ->get();

            return view('cliente.dashboard', compact('ordenes'));
        }

        if (str_contains($rolNombre, 'almac')) {
            return redirect()->route('almacen.index');
        }

        $ordenes = DB::table('orden_servicios')
            ->leftJoin('equipos', 'orden_servicios.equipo_id', '=', 'equipos.id')
            ->leftJoin('clientes', 'equipos.cliente_id', '=', 'clientes.id')
            ->leftJoin('users as cliente_user', 'clientes.user_id', '=', 'cliente_user.id')
            ->leftJoin('users as tecnico', 'orden_servicios.tecnico_id', '=', 'tecnico.id')
            ->select(
                'orden_servicios.*',
                'equipos.marca',
                'equipos.modelo',
                'equipos.numero_serie',
                'equipos.tipo_equipo',
                'cliente_user.name as cliente_nombre',
                'tecnico.name as tecnico_nombre'
            )
            ->orderBy('orden_servicios.id', 'desc')
            ->get();

        return view('dashboard', compact('ordenes'));
    })->name('dashboard');

    // MENÚ CLIENTE 2: COTIZACIONES PENDIENTES O POR APROBAR
    Route::get('/cliente/cotizaciones', function () {
        $user = Auth::user();
        $cliente = DB::table('clientes')->where('user_id', $user->id)->first();
        
        $ordenes = DB::table('orden_servicios')
            ->join('equipos', 'orden_servicios.equipo_id', '=', 'equipos.id')
            ->where('equipos.cliente_id', $cliente->id ?? 0)
            ->select('orden_servicios.*', 'equipos.marca', 'equipos.modelo', 'equipos.numero_serie', 'equipos.tipo_equipo')
            ->orderBy('orden_servicios.id', 'desc')
            ->get();

        $ordenesIds = $ordenes->pluck('id')->toArray();

        $refaccionesGrouped = DB::table('solicitudes_repuestos')
            ->join('inventario', 'solicitudes_repuestos.item_id', '=', 'inventario.id')
            ->whereIn('solicitudes_repuestos.orden_id', $ordenesIds)
            ->select('solicitudes_repuestos.orden_id', 'inventario.nombre', 'solicitudes_repuestos.cantidad', 'inventario.precio_unitario')
            ->get()
            ->groupBy('orden_id');

        return view('cliente.cotizaciones', compact('ordenes', 'refaccionesGrouped'));
    })->name('cliente.cotizaciones');

    // MENÚ CLIENTE 3: HISTORIAL DE MIS PEDIDOS
    Route::get('/cliente/historial', function () {
        $user = Auth::user();
        $cliente = DB::table('clientes')->where('user_id', $user->id)->first();
        
        $ordenes = DB::table('orden_servicios')
            ->join('equipos', 'orden_servicios.equipo_id', '=', 'equipos.id')
            ->where('equipos.cliente_id', $cliente->id ?? 0)
            ->select('orden_servicios.*', 'equipos.marca', 'equipos.modelo', 'equipos.numero_serie', 'equipos.tipo_equipo')
            ->orderBy('orden_servicios.id', 'desc')
            ->get();

        return view('cliente.historial', compact('ordenes'));
    })->name('cliente.historial');

    // MÓDULO ALMACÉN & STOCK
    Route::get('/almacen', function () {
        $refacciones = DB::table('inventario')->where('tipo', '!=', 'herramienta')->get();
        $herramientas = DB::table('inventario')->where('tipo', 'herramienta')->get();

        $herramientasParaEnviar = DB::table('prestamos_herramientas')
            ->join('users', 'prestamos_herramientas.tecnico_id', '=', 'users.id')
            ->join('inventario', 'prestamos_herramientas.item_id', '=', 'inventario.id')
            ->where('prestamos_herramientas.estado', 'AUTORIZADO_ADMIN')
            ->select('prestamos_herramientas.*', 'users.name as tecnico_nombre', 'inventario.nombre as herramienta_nombre')
            ->orderBy('prestamos_herramientas.id', 'desc')
            ->get();

        $prestamosActivos = DB::table('prestamos_herramientas')
            ->join('users', 'prestamos_herramientas.tecnico_id', '=', 'users.id')
            ->join('inventario', 'prestamos_herramientas.item_id', '=', 'inventario.id')
            ->whereIn('prestamos_herramientas.estado', ['ENVIADO_TECNICO', 'EN_USO'])
            ->select('prestamos_herramientas.*', 'users.name as tecnico_nombre', 'inventario.nombre as herramienta_nombre')
            ->orderBy('prestamos_herramientas.id', 'desc')
            ->get();

        $solicitudesAutorizadas = DB::table('solicitudes_repuestos')
            ->join('orden_servicios', 'solicitudes_repuestos.orden_id', '=', 'orden_servicios.id')
            ->join('users', 'solicitudes_repuestos.tecnico_id', '=', 'users.id')
            ->join('inventario', 'solicitudes_repuestos.item_id', '=', 'inventario.id')
            ->where('solicitudes_repuestos.estado', 'PENDIENTE')
            ->select('solicitudes_repuestos.*', 'orden_servicios.codigo_servicio', 'users.name as tecnico_nombre', 'inventario.nombre as item_nombre')
            ->orderBy('solicitudes_repuestos.id', 'desc')
            ->get();

        return view('almacen.index', compact('refacciones', 'herramientas', 'herramientasParaEnviar', 'prestamosActivos', 'solicitudesAutorizadas'));
    })->name('almacen.index');

    // ALMACÉN ENVÍA HERRAMIENTA Y DESCUENTA STOCK
    Route::post('/almacen/herramientas/enviar/{id}', function ($id) {
        $prestamo = DB::table('prestamos_herramientas')->where('id', $id)->first();
        if ($prestamo) {
            $item = DB::table('inventario')->where('id', $prestamo->item_id)->first();
            if ($item && ($item->stock_actual - $prestamo->cantidad) >= 0) {
                DB::table('inventario')->where('id', $item->id)->decrement('stock_actual', $prestamo->cantidad);
                DB::table('prestamos_herramientas')->where('id', $id)->update([
                    'estado' => 'ENVIADO_TECNICO',
                    'updated_at' => now()
                ]);
                return back()->with('success', 'Herramienta ENVIADA al Técnico. Stock actualizado en Almacén.');
            }
            return back()->with('error', 'No hay suficiente stock en Almacén para enviar esta herramienta.');
        }
        return back()->with('error', 'Registro de préstamo no encontrado.');
    })->name('almacen.herramientas.enviar');

    // ENTREGAR REFACCIONES EN ALMACÉN
    Route::post('/almacen/despachar/{orden_id}', function ($orden_id) {
        $solicitudes = DB::table('solicitudes_repuestos')
            ->where('orden_id', $orden_id)
            ->where('estado', 'PENDIENTE')
            ->get();

        foreach ($solicitudes as $sol) {
            $item = DB::table('inventario')->where('id', $sol->item_id)->first();
            if ($item && ($item->stock_actual - $sol->cantidad) >= 0) {
                DB::table('inventario')->where('id', $item->id)->decrement('stock_actual', $sol->cantidad);
                DB::table('solicitudes_repuestos')->where('id', $sol->id)->update([
                    'estado' => 'APROBADO',
                    'updated_at' => now()
                ]);
            }
        }

        return back()->with('success', 'Refacciones ENTREGADAS al Técnico y stock descontado del Almacén correctamente.');
    })->name('almacen.despachar');

    // REGRESAR HERRAMIENTA A ALMACÉN
    Route::post('/almacen/devolver/{id}', function ($id) {
        $sol = DB::table('solicitudes_repuestos')->where('id', $id)->first();
        if ($sol) {
            DB::table('inventario')->where('id', $sol->item_id)->increment('stock_actual', $sol->cantidad);
            DB::table('solicitudes_repuestos')->where('id', $id)->delete();
        }
        return back()->with('success', 'Herramienta DEVUELTA al Almacén correctamente y stock restaurado.');
    })->name('almacen.devolver');

    // MÓDULO APROBACIONES ADMIN
    Route::get('/aprobaciones', function () {
        $solicitudesRaw = DB::table('solicitudes_repuestos')
            ->join('orden_servicios', 'solicitudes_repuestos.orden_id', '=', 'orden_servicios.id')
            ->join('users', 'solicitudes_repuestos.tecnico_id', '=', 'users.id')
            ->join('inventario', 'solicitudes_repuestos.item_id', '=', 'inventario.id')
            ->where('solicitudes_repuestos.estado', 'PENDIENTE')
            ->select(
                'solicitudes_repuestos.orden_id',
                'orden_servicios.codigo_servicio',
                'users.name as tecnico_nombre',
                'inventario.nombre as item_nombre',
                'solicitudes_repuestos.cantidad'
            )
            ->get();

        $solicitudes = $solicitudesRaw->groupBy('orden_id')->map(function ($items) {
            $primero = $items->first();
            $listaItems = $items->map(function ($i) {
                return $i->item_nombre . ' (' . $i->cantidad . ' pzs)';
            })->implode(' + ');

            return (object) [
                'orden_id' => $primero->orden_id,
                'codigo_servicio' => $primero->codigo_servicio,
                'tecnico_nombre' => $primero->tecnico_nombre,
                'item_nombre' => $listaItems,
                'cantidad' => $items->sum('cantidad'),
                'estado' => 'PENDIENTE'
            ];
        })->values();

        $herramientasPendientes = DB::table('prestamos_herramientas')
            ->join('users', 'prestamos_herramientas.tecnico_id', '=', 'users.id')
            ->join('inventario', 'prestamos_herramientas.item_id', '=', 'inventario.id')
            ->where('prestamos_herramientas.estado', 'PENDIENTE_ADMIN')
            ->select('prestamos_herramientas.*', 'users.name as tecnico_nombre', 'inventario.nombre as herramienta_nombre')
            ->get();

        return view('aprobaciones.index', compact('solicitudes', 'herramientasPendientes'));
    })->name('aprobaciones.index');

    // APROBAR O RECHAZAR HERRAMIENTAS DESDE ADMIN
    Route::post('/aprobaciones/herramientas/aprobar/{id}', function ($id) {
        DB::table('prestamos_herramientas')->where('id', $id)->update([
            'estado' => 'AUTORIZADO_ADMIN',
            'updated_at' => now()
        ]);
        return back()->with('success', 'Petición de Herramienta APROBADA por el Administrador. Notificación enviada a Almacén.');
    })->name('aprobaciones.herramientas.aprobar');

    Route::post('/aprobaciones/herramientas/rechazar/{id}', function ($id) {
        DB::table('prestamos_herramientas')->where('id', $id)->update([
            'estado' => 'RECHAZADO',
            'updated_at' => now()
        ]);
        return back()->with('success', 'Petición de Herramienta RECHAZADA.');
    })->name('aprobaciones.herramientas.rechazar');

    // APROBAR / AUTORIZAR REFACCIONES DESDE ADMIN
    Route::post('/aprobaciones/aprobar/{orden_id}', function ($orden_id) {
        $solicitudes = DB::table('solicitudes_repuestos')
            ->where('orden_id', $orden_id)
            ->where('estado', 'PENDIENTE')
            ->get();

        foreach ($solicitudes as $sol) {
            $item = DB::table('inventario')->where('id', $sol->item_id)->first();
            if ($item && ($item->stock_actual - $sol->cantidad) >= 0) {
                DB::table('inventario')->where('id', $item->id)->decrement('stock_actual', $sol->cantidad);
                DB::table('solicitudes_repuestos')->where('id', $sol->id)->update([
                    'estado' => 'APROBADO',
                    'updated_at' => now()
                ]);
            }
        }

        return back()->with('success', 'Todas las refacciones fueron APROBADAS por el Administrador y el stock ha sido actualizado en Almacén.');
    })->name('aprobaciones.aprobar');

    // RECHAZAR TODAS LAS REFACCIONES DE UNA MISMA ORDEN
    Route::post('/aprobaciones/rechazar/{orden_id}', function ($orden_id) {
        DB::table('solicitudes_repuestos')
            ->where('orden_id', $orden_id)
            ->where('estado', 'PENDIENTE')
            ->update([
                'estado' => 'RECHAZADO',
                'updated_at' => now()
            ]);

        return back()->with('success', 'Solicitudes RECHAZADAS para esta orden.');
    })->name('aprobaciones.rechazar');

    // DESCARGA DE PDF - RECEPCIÓN (ENTRADA CON FOTOS)
    Route::get('/recepcion/pdf/{id}', function ($id) {
        $orden = DB::table('orden_servicios')
            ->leftJoin('equipos', 'orden_servicios.equipo_id', '=', 'equipos.id')
            ->leftJoin('clientes', 'equipos.cliente_id', '=', 'clientes.id')
            ->leftJoin('users as cliente_user', 'clientes.user_id', '=', 'cliente_user.id')
            ->select(
                'orden_servicios.*',
                'equipos.marca',
                'equipos.modelo',
                'equipos.numero_serie',
                'equipos.tipo_equipo',
                'cliente_user.name as cliente_nombre',
                'cliente_user.email as cliente_email',
                'cliente_user.telefono as cliente_telefono'
            )
            ->where('orden_servicios.id', $id)
            ->first();

        $pdf = Pdf::loadView('pdf.recepcion', compact('orden'));
        return $pdf->download('Ticket_Recepcion_' . $orden->codigo_servicio . '.pdf');
    })->name('recepcion.pdf');

    // DESCARGA DE PDF - ENTREGA (SALIDA CON FOTOS)
    Route::get('/entrega/pdf/{id}', function ($id) {
        $orden = DB::table('orden_servicios')
            ->leftJoin('equipos', 'orden_servicios.equipo_id', '=', 'equipos.id')
            ->leftJoin('clientes', 'equipos.cliente_id', '=', 'clientes.id')
            ->leftJoin('users as cliente_user', 'clientes.user_id', '=', 'cliente_user.id')
            ->select(
                'orden_servicios.*',
                'equipos.marca',
                'equipos.modelo',
                'equipos.numero_serie',
                'equipos.tipo_equipo',
                'cliente_user.name as cliente_nombre',
                'cliente_user.email as cliente_email',
                'cliente_user.telefono as cliente_telefono'
            )
            ->where('orden_servicios.id', $id)
            ->first();

        $refacciones = DB::table('solicitudes_repuestos')
            ->join('inventario', 'solicitudes_repuestos.item_id', '=', 'inventario.id')
            ->where('solicitudes_repuestos.orden_id', $id)
            ->where('solicitudes_repuestos.estado', 'APROBADO')
            ->select('inventario.nombre', 'solicitudes_repuestos.cantidad', 'inventario.precio_unitario')
            ->get();

        $pdf = Pdf::loadView('pdf.entrega', compact('orden', 'refacciones'));
        return $pdf->download('Nota_Entrega_' . $orden->codigo_servicio . '.pdf');
    })->name('entrega.pdf');

    // Módulos de Recepción
    Route::get('/recepcion', function () {
        return view('recepcion.index');
    })->name('recepcion.index');

    // GUARDADO CON FOTOS DE RECEPCIÓN Y REUTILIZACIÓN DE EQUIPO
    Route::post('/recepcion', function () {
        try {
            $ordenId = DB::transaction(function () {
                $email = trim(strtolower(request('cliente_email', 'cliente_' . time() . '@soportec.com')));
                
                $rol = DB::table('roles')->where('nombre', 'Cliente')->first();
                $roleId = $rol ? $rol->id : DB::table('roles')->insertGetId([
                    'nombre' => 'Cliente',
                    'descripcion' => 'Cliente final',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $userCliente = DB::table('users')->where('email', $email)->first();
                if (!$userCliente) {
                    $userId = DB::table('users')->insertGetId([
                        'name' => request('cliente_name', 'Cliente Nuevo'),
                        'email' => $email,
                        'password' => Hash::make(request('cliente_password', '12345678')),
                        'role_id' => $roleId,
                        'telefono' => request('cliente_telefono', '0000000000'),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    $userId = $userCliente->id;
                }

                $cliente = DB::table('clientes')->where('user_id', $userId)->first();
                if (!$cliente) {
                    $clienteId = DB::table('clientes')->insertGetId([
                        'user_id' => $userId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    $clienteId = $cliente->id;
                }

                $numeroSerie = trim(request('numero_serie', 'SN-' . time()));
                $equipo = DB::table('equipos')->where('numero_serie', $numeroSerie)->first();
                
                if (!$equipo) {
                    $equipoId = DB::table('equipos')->insertGetId([
                        'cliente_id' => $clienteId,
                        'tipo_equipo' => request('tipo_equipo', 'Laptop'),
                        'marca' => request('marca', 'Genérica'),
                        'modelo' => request('modelo', 'Genérico'),
                        'numero_serie' => $numeroSerie,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    $equipoId = $equipo->id;
                    DB::table('equipos')->where('id', $equipoId)->update([
                        'cliente_id' => $clienteId,
                        'updated_at' => now()
                    ]);
                }

                $rawTipo = request('tipo_servicio', 'Diagnostico');
                if (str_contains(strtolower($rawTipo), 'reparac')) {
                    $tipoSaneado = 'Reparacion';
                } elseif (str_contains(strtolower($rawTipo), 'mantenim')) {
                    $tipoSaneado = 'Mantenimiento';
                } elseif (str_contains(strtolower($rawTipo), 'garant')) {
                    $tipoSaneado = 'Garantia';
                } else {
                    $tipoSaneado = 'Diagnostico';
                }

                // Guardado de fotos de entrada
                $rutasFotosRecepcion = [];
                if (request()->hasFile('fotos_recepcion')) {
                    foreach (request()->file('fotos_recepcion') as $foto) {
                        $path = $foto->store('recepcion', 'public');
                        $rutasFotosRecepcion[] = $path;
                    }
                }

                $codigoServicio = 'ST-2026-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                $falla = request('falla_reportada', 'Sin reporte') . 
                    "\n\n[FECHA DESEADA CLIENTE]: " . request('fecha_deseada', date('Y-m-d')) . 
                    "\n[FECHA LÍMITE OFICIAL]: " . request('fecha_limite', date('Y-m-d'));

                return DB::table('orden_servicios')->insertGetId([
                    'codigo_servicio' => $codigoServicio,
                    'equipo_id' => $equipoId,
                    'recepcion_id' => Auth::id() ?? 1,
                    'tipo_servicio' => $tipoSaneado,
                    'falla_reportada' => $falla,
                    'fotos_recepcion' => json_encode($rutasFotosRecepcion),
                    'estado' => 'Pendiente',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            });

            return redirect()->route('recepcion.imprimir', $ordenId);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al guardar la orden: ' . $e->getMessage())->withInput();
        }
    })->name('recepcion.store');

    // Módulos de Banco Técnico
    Route::get('/banco-tecnico', function () {
        $ordenes = DB::table('orden_servicios')
            ->leftJoin('equipos', 'orden_servicios.equipo_id', '=', 'equipos.id')
            ->select('orden_servicios.*', 'equipos.marca', 'equipos.modelo', 'equipos.numero_serie')
            ->orderBy('orden_servicios.id', 'desc')
            ->get();

        return view('banco.index', compact('ordenes'));
    })->name('banco.index');

    // VISTA PETICIÓN Y CONTROL DE HERRAMIENTAS DEL TÉCNICO
    Route::get('/banco-tecnico/herramientas', function () {
        $catHerramientas = DB::table('inventario')->where('tipo', 'herramienta')->get();
        
        $misPrestamos = DB::table('prestamos_herramientas')
            ->join('inventario', 'prestamos_herramientas.item_id', '=', 'inventario.id')
            ->where('prestamos_herramientas.tecnico_id', Auth::id())
            ->whereIn('prestamos_herramientas.estado', ['PENDIENTE_ADMIN', 'AUTORIZADO_ADMIN', 'ENVIADO_TECNICO', 'EN_USO'])
            ->select('prestamos_herramientas.*', 'inventario.nombre as herramienta_nombre')
            ->orderBy('prestamos_herramientas.id', 'desc')
            ->get();

        return view('banco.herramientas', compact('catHerramientas', 'misPrestamos'));
    })->name('banco.herramientas');

    // TÉCNICO ENVÍA SOLICITUD MÚLTIPLE DE HERRAMIENTAS
    Route::post('/banco-tecnico/herramientas/solicitar', function () {
        $items = request('items_id', []);
        $cantidades = request('cantidades', []);

        if (is_array($items) && count($items) > 0) {
            $solicitados = 0;
            foreach ($items as $index => $itemId) {
                if ($itemId) {
                    $qty = intval($cantidades[$index] ?? 1);
                    DB::table('prestamos_herramientas')->insert([
                        'orden_id' => 0,
                        'tecnico_id' => Auth::id(),
                        'item_id' => $itemId,
                        'cantidad' => $qty,
                        'estado' => 'PENDIENTE_ADMIN',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $solicitados++;
                }
            }

            if ($solicitados > 0) {
                return back()->with('success', 'Se enviaron ' . $solicitados . ' solicitud(es) de herramientas al Administrador correctamente.');
            }
        }

        return back()->with('error', 'Por favor seleccione al menos una herramienta válida.');
    })->name('banco.herramientas.solicitar');

    // TÉCNICO PRESIONA "RECIBIR HERRAMIENTAS"
    Route::post('/banco-tecnico/herramientas/recibir/{id}', function ($id) {
        DB::table('prestamos_herramientas')->where('id', $id)->where('tecnico_id', Auth::id())->update([
            'estado' => 'EN_USO',
            'updated_at' => now()
        ]);
        return back()->with('success', '¡Herramientas recibidas! Ahora las tienes en uso. El botón de regresar se ha activado.');
    })->name('banco.herramientas.recibir');

    // TÉCNICO PRESIONA "REGRESAR HERRAMIENTAS" -> RESTAURA STOCK EN ALMACÉN
    Route::post('/banco-tecnico/herramientas/devolver/{id}', function ($id) {
        $prestamo = DB::table('prestamos_herramientas')->where('id', $id)->where('tecnico_id', Auth::id())->first();
        if ($prestamo && $prestamo->estado === 'EN_USO') {
            DB::table('inventario')->where('id', $prestamo->item_id)->increment('stock_actual', $prestamo->cantidad);
            DB::table('prestamos_herramientas')->where('id', $id)->update([
                'estado' => 'DEVUELTO_FINALIZADO',
                'updated_at' => now()
            ]);
            return back()->with('success', 'Herramientas DEVUELTAS al Almacén correctamente. El Stock ha sido restaurado.');
        }
        return back()->with('error', 'No se puede regresar esta herramienta hasta que haya sido recibida primeramente.');
    })->name('banco.herramientas.devolver');

    // VISTA DE DIAGNÓSTICO
    Route::get('/banco-tecnico/diagnosticar/{id}', function ($id) {
        $orden = DB::table('orden_servicios')
            ->leftJoin('equipos', 'orden_servicios.equipo_id', '=', 'equipos.id')
            ->select('orden_servicios.*', 'equipos.marca', 'equipos.modelo', 'equipos.numero_serie')
            ->where('orden_servicios.id', $id)
            ->first();

        $inventario = DB::table('inventario')->where('tipo', '!=', 'herramienta')->get();

        if ($orden && !$orden->tecnico_id) {
            DB::table('orden_servicios')->where('id', $id)->update(['tecnico_id' => Auth::id()]);
        }

        return view('banco.diagnostico', compact('orden', 'inventario'));
    })->name('banco.diagnosticar');

    // GUARDAR COTIZACIÓN Y REGISTRAR REFACCIONES CON ESTADO "En_Proceso"
    Route::post('/banco-tecnico/cotizar/{id}', function ($id) {
        $manoObra = floatval(request('costo_mano_obra', 0));
        $refaccionesCosto = floatval(request('costo_refacciones', 0));
        
        $items = request('items_id', []);
        $cantidades = request('cantidades_items', []);

        DB::table('solicitudes_repuestos')->where('orden_id', $id)->delete();

        if (is_array($items) && count($items) > 0) {
            foreach ($items as $index => $itemId) {
                if ($itemId) {
                    $qty = intval($cantidades[$index] ?? 1);
                    DB::table('solicitudes_repuestos')->insert([
                        'orden_id' => $id,
                        'tecnico_id' => Auth::id() ?? 1,
                        'item_id' => $itemId,
                        'cantidad' => $qty,
                        'estado' => 'PENDIENTE',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        } elseif (request('item_id')) {
            DB::table('solicitudes_repuestos')->insert([
                'orden_id' => $id,
                'tecnico_id' => Auth::id() ?? 1,
                'item_id' => request('item_id'),
                'cantidad' => intval(request('cantidad_item', 1)),
                'estado' => 'PENDIENTE',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $total = $manoObra + $refaccionesCosto;
        $ordenActual = DB::table('orden_servicios')->where('id', $id)->first();
        $fallaConDiag = ($ordenActual->falla_reportada ?? '') . "\n\n[DIAGNÓSTICO TÉCNICO]: " . request('diagnostico_tecnico');

        DB::table('orden_servicios')->where('id', $id)->update([
            'estado' => 'En_Proceso',
            'falla_reportada' => $fallaConDiag,
            'costo_mano_obra' => $manoObra,
            'costo_refacciones' => $refaccionesCosto,
            'costo_total' => $total,
            'anticipo' => $total * 0.50,
            'updated_at' => now(),
        ]);

        return redirect()->route('banco.index')->with('success', 'Cotización guardada y enviada al Cliente exitosamente.');
    })->name('banco.cotizar');

    // Finalizar Servicio
    Route::post('/banco-tecnico/finalizar/{id}', function ($id) {
        DB::table('orden_servicios')->where('id', $id)->update([
            'estado' => 'Terminado',
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Orden marcada como TERMINADA exitosamente.');
    })->name('banco.finalizar');

    // Módulo Cotizaciones
    Route::get('/cotizaciones', function () {
        $cotizaciones = DB::table('orden_servicios')
            ->leftJoin('equipos', 'orden_servicios.equipo_id', '=', 'equipos.id')
            ->leftJoin('clientes', 'equipos.cliente_id', '=', 'clientes.id')
            ->leftJoin('users as cliente_user', 'clientes.user_id', '=', 'cliente_user.id')
            ->select(
                'orden_servicios.*',
                'equipos.marca',
                'equipos.modelo',
                'equipos.numero_serie',
                'equipos.tipo_equipo',
                'cliente_user.name as cliente_nombre'
            )
            ->orderBy('orden_servicios.id', 'desc')
            ->get();

        return view('cotizaciones.index', compact('cotizaciones'));
    })->name('cotizaciones.index');

    Route::get('/cotizaciones/revisar/{id}', function ($id) {
        $orden = DB::table('orden_servicios')
            ->leftJoin('equipos', 'orden_servicios.equipo_id', '=', 'equipos.id')
            ->select('orden_servicios.*', 'equipos.marca', 'equipos.modelo', 'equipos.numero_serie', 'equipos.tipo_equipo')
            ->where('orden_servicios.id', $id)
            ->first();

        return view('cotizaciones.show', compact('orden'));
    })->name('cotizaciones.show');

    // Cobrar Anticipo
    Route::post('/recepcion/cobrar-anticipo/{id}', function ($id) {
        DB::table('orden_servicios')->where('id', $id)->update([
            'estado' => 'En_Proceso',
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Anticipo del 50% registrado.');
    })->name('recepcion.cobrar');

    // Liquidar y Entregar (con guardado de fotos de salida)
    Route::post('/recepcion/entregar/{id}', function ($id) {
        $rutasFotosEntrega = [];
        if (request()->hasFile('fotos_entrega')) {
            foreach (request()->file('fotos_entrega') as $foto) {
                $path = $foto->store('entrega', 'public');
                $rutasFotosEntrega[] = $path;
            }
        }

        DB::table('orden_servicios')->where('id', $id)->update([
            'estado' => 'Entregado',
            'fotos_entrega' => json_encode($rutasFotosEntrega),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Pago liquidado y equipo ENTREGADO exitosamente.');
    })->name('recepcion.entregar');

    // Perfil y Administración
    Route::get('/admin/usuarios/crear', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/usuarios', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';