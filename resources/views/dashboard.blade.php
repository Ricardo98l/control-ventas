<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <h2 class="font-extrabold text-2xl text-slate-800 leading-tight tracking-tight">
                    {{ __('Panel de Control - Sistema de Cargas') }}
                </h2>
                <span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest hidden md:inline-block">Versión 1.0</span>
            </div>
            
            <a href="{{ route('reports.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl text-sm transition-colors shadow-lg shadow-indigo-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                Ver Estadísticas e Historial
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50" x-data="{ tab: '{{ request('tab', session('tab', 'ventas')) }}', showCreateModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center mb-8">
                <div class="bg-slate-200/60 p-1.5 rounded-2xl inline-flex space-x-1 shadow-inner">
                    <button @click="tab = 'ventas'" :class="{'bg-white shadow-sm text-indigo-700': tab === 'ventas', 'text-slate-600 hover:text-slate-800 hover:bg-slate-200/50': tab !== 'ventas'}" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 flex items-center gap-2">
                        📊 Registro Diario
                    </button>
                    <button @click="tab = 'precios'" :class="{'bg-white shadow-sm text-indigo-700': tab === 'precios', 'text-slate-600 hover:text-slate-800 hover:bg-slate-200/50': tab !== 'precios'}" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 flex items-center gap-2">
                        💲 Matriz de Precios
                    </button>
                    <button @click="tab = 'cierre'" :class="{'bg-white shadow-sm text-indigo-700': tab === 'cierre', 'text-slate-600 hover:text-slate-800 hover:bg-slate-200/50': tab !== 'cierre'}" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 flex items-center gap-2">
                        📈 Cierre Mensual
                    </button>
                    <button @click="tab = 'catalogo'" 
                            :class="{'bg-white shadow-sm text-indigo-700': tab === 'catalogo', 'text-slate-600 hover:text-slate-800 hover:bg-slate-200/50': tab !== 'catalogo'}" 
                            class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 flex items-center gap-2">
                        🚚  Canales
                    </button>
                </div>
            </div>

            <div class="transition-all duration-300">
                <!-- PESTAÑA 1: REGISTRO DE VENTAS  -->
                <div x-show="tab === 'ventas'" style="display: none;" x-transition.opacity.duration.400ms>
                    
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 w-full p-6">
                        
                        <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                            <h3 class="text-xl font-extrabold text-slate-800 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Historial de Movimientos
                            </h3>
                            <button @click="showCreateModal = true" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-bold rounded-lg text-sm px-5 py-2.5 flex items-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Registrar Carga
                            </button>
                        </div>
                        
                        @if($recentSales->isEmpty())
                            <div class="flex flex-col items-center justify-center h-48 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                                <svg class="w-12 h-12 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-slate-500 font-medium">No hay registros hoy.</p>
                            </div>
                        @else
                            <div class="overflow-hidden rounded-xl border border-slate-200">
                                <ul class="divide-y divide-slate-200">
                                    @foreach($recentSales as $sale)
                                        <li x-data="{ showEditModal: false, showDeleteModal: false }" class="p-4 flex justify-between items-center hover:bg-slate-50 transition-colors group">
                                            
                                            <div class="flex items-center gap-4">
                                                <div class="bg-indigo-100 p-2.5 rounded-lg text-indigo-600">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                                </div>
                                                <div>
                                                    <p class="text-base font-extrabold text-slate-900">{{ $sale->product->name }} <span class="text-slate-500 font-medium">{{ $sale->product->size }}</span></p>
                                                    <p class="text-sm text-slate-500 mt-0.5">
                                                        <span class="font-bold text-indigo-600">{{ $sale->channel->name }}</span> • {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M, Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center gap-5">
                                                <span class="inline-flex items-center px-4 py-1.5 rounded-lg text-sm font-bold bg-emerald-100 text-emerald-800">
                                                    {{ $sale->quantity }} Cajas
                                                </span>
                                                
                                                <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-2">
                                                    <button type="button" @click="showEditModal = true" class="p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors" title="Editar">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </button>
                                                    <button type="button" @click="showDeleteModal = true" class="p-2 text-rose-600 bg-rose-50 rounded-lg hover:bg-rose-100 transition-colors" title="Eliminar">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <div x-show="showDeleteModal" style="display: none;" class="relative z-50">
                                                <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" x-show="showDeleteModal" x-transition.opacity @click="showDeleteModal = false"></div>
                                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                                                        <div x-show="showDeleteModal" x-transition class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-md p-6">
                                                            <div class="flex items-center gap-4 mb-4">
                                                                <div class="bg-rose-100 p-3 rounded-full text-rose-600">
                                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                                </div>
                                                                <h3 class="text-xl font-extrabold text-slate-800">¿Eliminar Registro?</h3>
                                                            </div>
                                                            <p class="text-sm text-slate-500 mb-6">Estás a punto de eliminar <b>{{ $sale->quantity }} cajas</b> de <b>{{ $sale->product->name }}</b> de la ruta <b>{{ $sale->channel->name }}</b>. Esta acción restará este valor del cierre mensual y no se puede deshacer.</p>
                                                            <div class="flex justify-end gap-3 mt-4">
                                                                <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Cancelar</button>
                                                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="m-0">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-lg transition-colors">Sí, eliminar registro</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div x-show="showEditModal" style="display: none;" class="relative z-50">
                                                <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" x-show="showEditModal" x-transition.opacity @click="showEditModal = false"></div>
                                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                                                        <div x-show="showEditModal" x-transition class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-lg p-6 border border-slate-100">
                                                            
                                                            <div class="flex justify-between items-center mb-6">
                                                                <h3 class="text-xl font-extrabold text-slate-800 flex items-center gap-2">
                                                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                                    Editar Registro
                                                                </h3>
                                                                <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                                            </div>

                                                            <form action="{{ route('sales.update', $sale->id) }}" method="POST">
                                                                @csrf @method('PUT')
                                                                <div class="space-y-5">
                                                                    <div>
                                                                        <label class="block text-sm font-bold text-slate-700 mb-1">Fecha</label>
                                                                        <input type="date" name="sale_date" value="{{ $sale->sale_date }}" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" required>
                                                                    </div>
                                                                    <div>
                                                                        <label class="block text-sm font-bold text-slate-700 mb-1">Canal / Ruta</label>
                                                                        <select name="channel_id" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" required>
                                                                            @foreach($channels as $channel)
                                                                                <option value="{{ $channel->id }}" {{ $sale->channel_id == $channel->id ? 'selected' : '' }}>{{ $channel->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div>
                                                                        <label class="block text-sm font-bold text-slate-700 mb-1">Producto</label>
                                                                        <select name="product_id" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" required>
                                                                            @foreach($products as $product)
                                                                                <option value="{{ $product->id }}" {{ $sale->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }} - {{ $product->size }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div>
                                                                        <label class="block text-sm font-bold text-slate-700 mb-1">Cajas Físicas</label>
                                                                        <input type="number" name="quantity" min="1" value="{{ $sale->quantity }}" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" required>
                                                                    </div>
                                                                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100">
                                                                        <button type="button" @click="showEditModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Cancelar</button>
                                                                        <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">Guardar Cambios</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                <div x-show="showCreateModal" style="display: none;" class="relative z-50">
                    <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" x-show="showCreateModal" x-transition.opacity @click="showCreateModal = false"></div>
                    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4 text-center">
                            <div x-show="showCreateModal" x-transition class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-lg p-6 border border-slate-100">
                                
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-xl font-extrabold text-slate-800 flex items-center gap-2">
                                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        Registrar Nueva Carga
                                    </h3>
                                    <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                </div>

                                <form action="{{ route('sales.store') }}" method="POST">
                                    @csrf
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1">Fecha</label>
                                            <input type="date" name="sale_date" value="{{ date('Y-m-d') }}" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1">Canal / Ruta</label>
                                            <select name="channel_id" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" required>
                                                <option value="">Seleccione ruta...</option>
                                                @foreach($channels as $channel)
                                                    <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1">Producto</label>
                                            <select name="product_id" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" required>
                                                <option value="">Seleccione bebida...</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->size }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1">Cajas Físicas</label>
                                            <input type="number" name="quantity" min="1" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" placeholder="Ej: 250" required>
                                        </div>
                                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100">
                                            <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Cancelar</button>
                                            <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">Registrar Carga</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- PESTAÑA 2: MATRIZ DE PRECIOS   -->
                <div x-show="tab === 'precios'" x-data="{ showAddModal: false, editModal: 0, deleteModal: 0 }" style="display: none;" x-transition.opacity.duration.400ms>
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                        
                        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <h3 class="text-xl font-extrabold text-slate-800">Matriz de Fletes y Productos</h3>
                                <p class="text-sm text-slate-500 mt-1">Configura precios, y administra tu catálogo de bebidas.</p>
                            </div>
                            <button @click="showAddModal = true" type="button" class="text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 font-bold rounded-lg text-sm px-5 py-2.5 flex items-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Agregar Bebida
                            </button>
                        </div>

                        <form action="{{ route('prices.store') }}" method="POST">
                            @csrf
                            <div class="overflow-x-auto overflow-y-hidden rounded-xl border border-slate-200">
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead class="bg-slate-800 text-white">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Acciones</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Bebida / Producto</th>
                                            @foreach($channels as $channel)
                                                <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider border-l border-slate-700">
                                                    {{ $channel->name }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @foreach($products as $product)
                                            <tr class="hover:bg-slate-50 transition-colors">
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="flex items-center gap-2">
                                                        <button type="button" @click="editModal = {{ $product->id }}" class="p-1.5 text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 transition-colors" title="Editar Producto">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        </button>
                                                        <button type="button" @click="deleteModal = {{ $product->id }}" class="p-1.5 text-rose-600 bg-rose-50 rounded-md hover:bg-rose-100 transition-colors" title="Eliminar Producto">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </div>
                                                </td>

                                                <td class="px-6 py-3 whitespace-nowrap">
                                                    <span class="text-sm font-bold text-slate-900">{{ $product->name }}</span>
                                                    <span class="text-xs text-slate-500 ml-1">{{ $product->size }}</span>
                                                </td>
                                                
                                                @foreach($channels as $channel)
                                                    @php
                                                        $assignedChannel = $product->channels->where('id', $channel->id)->first();
                                                        $currentPrice = $assignedChannel ? $assignedChannel->pivot->price : '';
                                                    @endphp
                                                    <td class="px-4 py-2 border-l border-slate-100">
                                                        <input type="number" step="0.01" min="0" 
                                                            name="prices[{{ $channel->id }}][{{ $product->id }}]" 
                                                            value="{{ $currentPrice }}" 
                                                            class="block w-full text-center text-sm font-semibold text-slate-700 bg-slate-50 border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                                            placeholder="0.00">
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-bold rounded-lg text-sm px-6 py-3 flex items-center gap-2 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                    Guardar Precios
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div x-show="showAddModal" style="display: none;" class="relative z-50">
                        <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" x-show="showAddModal" x-transition.opacity @click="showAddModal = false"></div>
                        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                            <div class="flex min-h-full items-center justify-center p-4 text-center">
                                <div x-show="showAddModal" x-transition class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md p-6 border border-slate-100">
                                    <div class="flex justify-between items-center mb-6">
                                        <h3 class="text-xl font-extrabold text-slate-800">Agregar Nueva Bebida</h3>
                                        <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                    </div>
                                    <form action="{{ route('catalog.product.store') }}" method="POST">
                                        @csrf
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-bold text-slate-700 mb-1">Nombre</label>
                                                <input type="text" name="name" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" placeholder="Ej: Coca Cola" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-bold text-slate-700 mb-1">Tamaño</label>
                                                <input type="text" name="size" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" placeholder="Ej: 3 Litros" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-bold text-slate-700 mb-1">Tipo de Envase</label>
                                                <select name="packaging_id" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" required>
                                                    <option value="">Seleccionar envase...</option>
                                                    @foreach($packagings as $envase)
                                                        <option value="{{ $envase->id }}">{{ $envase->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="flex justify-end gap-3 mt-6">
                                                <button type="button" @click="showAddModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg">Cancelar</button>
                                                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg">Guardar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach($products as $product)
                        <div x-show="editModal === {{ $product->id }}" style="display: none;" class="relative z-50">
                            <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" x-show="editModal === {{ $product->id }}" x-transition.opacity @click="editModal = 0"></div>
                            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                <div class="flex min-h-full items-center justify-center p-4 text-center">
                                    <div x-show="editModal === {{ $product->id }}" x-transition class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md p-6 border border-slate-100">
                                        <div class="flex justify-between items-center mb-6">
                                            <h3 class="text-xl font-extrabold text-slate-800">Editar Producto</h3>
                                            <button type="button" @click="editModal = 0" class="text-slate-400 hover:text-slate-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                        </div>
                                        <form action="{{ route('catalog.product.update', $product->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-bold text-slate-700 mb-1">Nombre</label>
                                                    <input type="text" name="name" value="{{ $product->name }}" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" required>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-bold text-slate-700 mb-1">Tamaño</label>
                                                    <input type="text" name="size" value="{{ $product->size }}" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" required>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-bold text-slate-700 mb-1">Tipo de Envase</label>
                                                    <select name="packaging_id" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" required>
                                                        <option value="">Seleccionar envase...</option>
                                                        @foreach($packagings as $envase)
                                                            <option value="{{ $envase->id }}" {{ $product->packaging_id == $envase->id ? 'selected' : '' }}>
                                                                {{ $envase->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="flex justify-end gap-3 mt-6">
                                                    <button type="button" @click="editModal = 0" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg">Cancelar</button>
                                                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg">Actualizar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="deleteModal === {{ $product->id }}" style="display: none;" class="relative z-50">
                            <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" x-show="deleteModal === {{ $product->id }}" x-transition.opacity @click="deleteModal = 0"></div>
                            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                <div class="flex min-h-full items-center justify-center p-4 text-center">
                                    <div x-show="deleteModal === {{ $product->id }}" x-transition class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md p-6 border border-slate-100">
                                        <div class="flex items-center gap-4 mb-4">
                                            <div class="bg-rose-100 p-3 rounded-full text-rose-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            </div>
                                            <h3 class="text-xl font-extrabold text-slate-800">Eliminar Producto</h3>
                                        </div>
                                        <p class="text-sm text-slate-500 mb-6">¿Estás seguro de que deseas eliminar <b>{{ $product->name }} {{ $product->size }}</b> de todo el sistema? Esta acción también borrará sus precios.</p>
                                        <div class="flex justify-end gap-3 mt-4">
                                            <button type="button" @click="deleteModal = 0" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg">Cancelar</button>
                                            <form action="{{ route('catalog.product.destroy', $product->id) }}" method="POST" class="m-0">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-lg">Sí, Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                </div>
                <!-- PESTAÑA 3: CIERRE MENSUAL      -->
                <div x-show="tab === 'cierre'" 
                     x-data="cierreMensualData()"
                     x-init="$watch('search', value => currentPage = 1); $watch('perPage', value => currentPage = 1);"
                     style="display: none;" 
                     x-transition.opacity.duration.400ms>
                     
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8">
                        <div class="flex flex-col md:flex-row justify-between items-center bg-slate-800 rounded-2xl p-6 mb-8 text-white shadow-md">
                            <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-end gap-4 w-full md:w-auto mb-4 md:mb-0">
                                <input type="hidden" name="tab" value="cierre">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-300 mb-1 uppercase tracking-wider">Período Fiscal</label>
                                    <input type="month" name="mes" value="{{ $mesSeleccionado }}" class="bg-slate-700 border-none text-white rounded-lg focus:ring-2 focus:ring-indigo-400 p-2.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-300 mb-1 uppercase tracking-wider">Ruta / Canal</label>
                                    <select name="channel_id" class="bg-slate-700 border-none text-white rounded-lg focus:ring-2 focus:ring-indigo-400 p-2.5 min-w-[200px]">
                                        <option value="">Todas las rutas</option>
                                        @foreach($channels as $channel)
                                            <option value="{{ $channel->id }}" {{ request('channel_id') == $channel->id ? 'selected' : '' }}>
                                                {{ $channel->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-lg px-5 py-2.5 transition-colors">
                                    Generar Hoja
                                </button>
                            </form>

                            <div class="text-right w-full md:w-auto">
                                <p class="text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Liquidación Total</p>
                                <p class="text-4xl font-black text-emerald-400">Bs {{ number_format($granTotal, 2) }}</p>
                            </div>
                        </div>

                        @if(count($reporteCierre) > 0)
                            <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
                                <div class="relative w-full md:w-1/2">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <input type="text" x-model="search" placeholder="Buscar por ruta o producto..." class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2.5 shadow-sm">
                                </div>

                                <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                                    <label class="text-sm font-bold text-slate-600">Mostrar:</label>
                                    <select x-model="perPage" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 shadow-sm">
                                        <option value="10">10 registros</option>
                                        <option value="50">50 registros</option>
                                        <option value="100">100 registros</option>
                                    </select>
                                </div>
                            </div>

                            <div class="overflow-x-auto rounded-xl border border-slate-200 shadow-sm">
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Ruta / Canal</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Detalle Producto</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Tarifa Un.</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Volumen (Cajas)</th>
                                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        <template x-for="fila in paginatedData" :key="fila.canal + fila.producto">
                                            <tr class="hover:bg-slate-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-indigo-700" x-text="fila.canal"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-800" x-text="fila.producto"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 text-center font-medium" x-text="'Bs ' + formatMoney(fila.precio)"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700" x-text="fila.cantidad_total"></span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-slate-900 text-right" x-text="'Bs ' + formatMoney(fila.dinero_total)"></td>
                                            </tr>
                                        </template>
                                        <tr x-show="filteredData.length === 0" style="display: none;">
                                            <td colspan="5" class="px-6 py-8 text-center text-slate-500 font-medium">
                                                No se encontraron coincidencias para "<span x-text="search" class="font-bold text-slate-700"></span>"
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm" x-show="filteredData.length > 0">
                                <span class="text-sm text-slate-600 font-medium">
                                    Mostrando página <span class="font-bold text-slate-900" x-text="currentPage"></span> de <span class="font-bold text-slate-900" x-text="totalPages"></span>
                                </span>
                                <div class="inline-flex shadow-sm rounded-md">
                                    <button @click="if(currentPage > 1) currentPage--" :disabled="currentPage === 1" :class="{'opacity-50 cursor-not-allowed': currentPage === 1}" class="px-4 py-2 text-sm font-bold text-slate-700 bg-white border border-slate-300 rounded-l-lg hover:bg-slate-50 focus:z-10 focus:ring-2 focus:ring-indigo-500 transition-colors">
                                        Anterior
                                    </button>
                                    <button @click="if(currentPage < totalPages) currentPage++" :disabled="currentPage === totalPages" :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages}" class="px-4 py-2 text-sm font-bold text-slate-700 bg-white border border-slate-300 rounded-r-lg hover:bg-slate-50 focus:z-10 focus:ring-2 focus:ring-indigo-500 transition-colors -ml-px">
                                        Siguiente
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-16 bg-slate-50 rounded-xl border border-dashed border-slate-300 mt-6">
                                <svg class="mx-auto h-12 w-12 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-slate-600 font-bold text-lg">Sin Movimientos</p>
                                <p class="text-slate-500 text-sm">No se registraron ventas en este período.</p>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- PESTAÑA 4: GESTIÓN DE RUTAS    -->
                 <div x-show="tab === 'catalogo'" style="display: none;" x-transition.opacity.duration.400ms>
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 w-full md:w-1/2 p-8">
                            <h3 class="text-xl font-extrabold mb-4 text-slate-800 flex items-center gap-2">
                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Agregar Nuevo Canal Distribucion
                            </h3>
                            <form action="{{ route('catalog.channel.store') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <input type="text" name="name" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-emerald-500 block w-full p-3" placeholder="Ej: Ruta 0342A" required>
                                </div>
                                <button type="submit" class="w-full text-white bg-slate-800 hover:bg-slate-900 font-bold rounded-xl text-sm px-5 py-3 transition-colors">Guardar Ruta</button>
                            </form>
                        </div>

                        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 w-full md:w-1/2 p-8">
                            <h3 class="text-xl font-extrabold mb-4 text-slate-800 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                Tipos de Envase
                            </h3>
                            
                            <form action="{{ route('catalog.packaging.store') }}" method="POST" class="mb-6">
                                @csrf
                                <div class="flex gap-2">
                                    <input type="text" name="name" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-3" placeholder="Ej: Vidrio, TetraPak..." required>
                                    <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-xl px-5 py-3 transition-colors">Añadir</button>
                                </div>
                            </form>

                            <div class="overflow-y-auto max-h-48 rounded-lg border border-slate-100">
                                <ul class="divide-y divide-slate-100">
                                    @forelse($packagings as $envase)
                                        <li class="flex justify-between items-center p-3 hover:bg-slate-50">
                                            <span class="font-bold text-slate-700">{{ $envase->name }}</span>
                                            <form action="{{ route('catalog.packaging.destroy', $envase->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este envase?');" class="m-0">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-500 hover:text-rose-700 p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </form>
                                        </li>
                                    @empty
                                        <li class="p-3 text-sm text-slate-500 text-center">No hay envases registrados.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
        </div>
    </div>
        <script>
            function cierreMensualData() {
                return {
                    search: '',
                    perPage: 10,
                    currentPage: 1,
                    // Aquí Laravel inyecta los datos de forma segura sin romper el HTML
                    cierreData: {!! json_encode($reporteCierre->values()) !!},
                    
                    get filteredData() {
                        if (this.search === '') return this.cierreData;
                        return this.cierreData.filter(item => 
                            item.canal.toLowerCase().includes(this.search.toLowerCase()) || 
                            item.producto.toLowerCase().includes(this.search.toLowerCase())
                        );
                    },
                    get paginatedData() {
                        let start = (this.currentPage - 1) * this.perPage;
                        let end = start + parseInt(this.perPage);
                        return this.filteredData.slice(start, end);
                    },
                    get totalPages() {
                        let total = Math.ceil(this.filteredData.length / this.perPage);
                        return total === 0 ? 1 : total;
                    },
                    formatMoney(amount) {
                        return parseFloat(amount).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // Disparador automático de SweetAlert si hay un mensaje de éxito
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Listo!',
                    text: '{{ session('success') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-xl shadow-lg border border-slate-100'
                    }
                });
            @endif

            // Lógica del Cierre Mensual
            function cierreMensualData() {
                return {
                    search: '',
                    perPage: 10,
                    currentPage: 1,
                    cierreData: {!! json_encode($reporteCierre->values()) !!},
                    
                    get filteredData() {
                        if (this.search === '') return this.cierreData;
                        return this.cierreData.filter(item => 
                            item.canal.toLowerCase().includes(this.search.toLowerCase()) || 
                            item.producto.toLowerCase().includes(this.search.toLowerCase())
                        );
                    },
                    get paginatedData() {
                        let start = (this.currentPage - 1) * this.perPage;
                        let end = start + parseInt(this.perPage);
                        return this.filteredData.slice(start, end);
                    },
                    get totalPages() {
                        let total = Math.ceil(this.filteredData.length / this.perPage);
                        return total === 0 ? 1 : total;
                    },
                    formatMoney(amount) {
                        return parseFloat(amount).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            }
        </script>
</x-app-layout> 