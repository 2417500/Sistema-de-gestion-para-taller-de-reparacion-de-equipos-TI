<?php

namespace App\Http\Controllers\Recepcion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrdenServicio;
use App\Models\Inventario;
use App\Models\SolicitudRepuesto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class OrdenServicioController extends Controller
{
    /**
     * Muestra el panel principal del Banco Técnico.
     */
    public function index()
    {
        $ordenes = OrdenServicio::whereIn('estado', ['Pendiente', 'Cotizacion_Enviada', 'En_Reparacion', 'EN_PROCESO', 'En_Proceso'])
            ->orderBy('id', 'desc')
            ->get();

        return view('banco.index', compact('ordenes'));
    }

    /**
     * Muestra la hoja de trabajo / diagnóstico de una orden.
     */
    public function diagnosticar($id)
    {
        $orden = OrdenServicio::findOrFail($id);
        $inventario = Inventario::where('stock_actual', '>', 0)->get();

        return view('banco.diagnostico', compact('orden', 'inventario'));
    }

    /**
     * Permite a un Técnico asignarse/atender la orden (Control de concurrencia).
     */
    public function atender($id)
    {
        $orden = OrdenServicio::findOrFail($id);

        if ($orden->tecnico_id !== null) {
            return back()->with('error', 'Esta orden ya fue tomada por otro técnico.');
        }

        $orden->tecnico_id = Auth::id();
        $orden->estado = 'En_Proceso';
        $orden->save();

        return redirect()->back()->with('success', 'Has tomado la orden de servicio exitosamente.');
    }

    /**
     * Muestra la ficha detallada de la orden en modo lectura (Ver Orden).
     */
    public function show($id)
    {
        $orden = OrdenServicio::with(['equipo', 'cliente.user'])->findOrFail($id);
        return view('recepcion.ver_orden', compact('orden'));
    }

    /**
     * Guarda el diagnóstico y genera la cotización para el cliente.
     */
    public function cotizar(Request $request, $id)
    {
        $request->validate([
            'diagnostico_tecnico' => 'required|string',
            'costo_mano_obra' => 'required|numeric|min:0',
            'costo_refacciones' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $orden = OrdenServicio::findOrFail($id);
            $orden->diagnostico = $request->diagnostico_tecnico;
            $orden->costo_mano_obra = $request->costo_mano_obra;
            $orden->costo_refacciones = $request->costo_refacciones ?? 0;
            $orden->costo_total = $orden->costo_mano_obra + $orden->costo_refacciones;
            $orden->estado = 'Cotizacion_Enviada';
            $orden->save();

            // Registrar refacciones asociadas si existen
            if ($request->has('items_id')) {
                foreach ($request->items_id as $key => $itemId) {
                    if (!empty($itemId)) {
                        $cant = $request->cantidades_items[$key] ?? 1;
                        SolicitudRepuesto::create([
                            'orden_servicio_id' => $orden->id,
                            'inventario_id' => $itemId,
                            'cantidad' => $cant,
                            'estado' => 'Pendiente'
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('banco.index')->with('success', 'Cotización guardada y enviada al Cliente exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar cotización: ' . $e->getMessage());
        }
    }

    /**
     * Sube las evidencias fotográficas de reparación/salida cargadas por el Técnico.
     */
    public function subirFotosEntrega(Request $request, $id)
    {
        $request->validate([
            'fotos_entrega' => 'required',
            'fotos_entrega.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        $orden = OrdenServicio::findOrFail($id);
        $rutas = [];

        if ($request->hasFile('fotos_entrega')) {
            foreach ($request->file('fotos_entrega') as $foto) {
                $nombre = 'entrega_' . time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $ruta = $foto->storeAs('evidencias', $nombre, 'public');
                $rutas[] = $ruta;
            }

            $orden->fotos_entrega = json_encode($rutas);
            $orden->save();
        }

        return back()->with('success', 'Evidencia fotográfica de reparación cargada exitosamente por el Técnico.');
    }

    /**
     * Marca la reparación como finalizada por parte del Técnico.
     */
    public function finalizar($id)
    {
        $orden = OrdenServicio::findOrFail($id);
        $orden->estado = 'Terminado';
        $orden->save();

        return redirect()->route('banco.index')->with('success', 'Orden finalizada y lista para entrega en Recepción.');
    }
}