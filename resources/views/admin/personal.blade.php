@extends('layouts.private')

@section('title', 'Gestión de Personal')
@section('page-title', 'Cuentas Activas de Empleados')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 font-mono text-xs">
    @if (session('success'))
        <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 space-y-4">
        <div class="flex justify-between items-center pb-4 border-b border-[#2d3038]">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#ffb800]">manage_accounts</span>
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">GESTIÓN DE PERSONAL // CUENTAS ACTIVAS</h2>
            </div>
            <a href="{{ route('admin.users.create') }}" class="px-3 py-1.5 bg-[#ffb800] text-[#0d0f12] font-bold uppercase rounded hover:bg-white transition-colors">
                + Crear Empleado
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#182238] text-white uppercase text-[10px] border-b border-[#2d3038]">
                        <th class="p-3 font-semibold tracking-wider">NOMBRE EMPLEADO</th>
                        <th class="p-3 font-semibold tracking-wider">CORREO ELECTRÓNICO</th>
                        <th class="p-3 font-semibold tracking-wider">ROL</th>
                        <th class="p-3 font-semibold tracking-wider">FECHA REGISTRO</th>
                        <th class="p-3 font-semibold tracking-wider">ESTADO</th>
                        <th class="p-3 font-semibold tracking-wider text-right">ACCIÓN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d3038] text-white">
                    @forelse ($empleados as $emp)
                        <tr class="hover:bg-[#16181f]/50 transition-colors">
                            <td class="p-3 font-bold">{{ $emp->name }}</td>
                            <td class="p-3 text-[#8a8e9b]">{{ $emp->email }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 text-[10px] font-bold border rounded uppercase bg-[#1d212b] border-sky-500/40 text-sky-400">
                                    {{ $emp->role->nombre ?? 'Sin Rol' }}
                                </span>
                            </td>
                            <td class="p-3 text-[#8a8e9b]">{{ $emp->created_at ? $emp->created_at->format('d/m/Y') : 'N/A' }}</td>
                            <td class="p-3">
                                @if(boolval($emp->activo))
                                    <span class="px-2 py-0.5 text-[10px] font-bold border rounded uppercase bg-emerald-950/80 border-emerald-500/50 text-emerald-400">
                                        ACTIVO
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 text-[10px] font-bold border rounded uppercase bg-rose-950/80 border-rose-500/50 text-rose-400">
                                        DADO DE BAJA
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 text-right">
                                <form action="{{ route('admin.personal.toggle', $emp->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Deseas cambiar el estado operacional de esta cuenta?');">
                                    @csrf
                                    @method('PATCH')
                                    @if(boolval($emp->activo))
                                        <button type="submit" class="px-3 py-1 bg-rose-600/20 border border-rose-500 text-rose-400 hover:bg-rose-600 hover:text-white font-bold uppercase rounded transition-colors text-[10px]">
                                            DAR DE BAJA
                                        </button>
                                    @else
                                        <button type="submit" class="px-3 py-1 bg-emerald-600/20 border border-emerald-500 text-emerald-400 hover:bg-emerald-600 hover:text-white font-bold uppercase rounded transition-colors text-[10px]">
                                            ACTIVAR CUENTA
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-[#8a8e9b] uppercase">No hay empleados registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection