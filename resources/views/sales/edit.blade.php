<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-800 leading-tight tracking-tight">
            {{ __('Editar Registro de Carga') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8">
                
                <h3 class="text-xl font-extrabold mb-6 text-slate-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Modificar Datos de la Carga
                </h3>

                <form action="{{ route('sales.update', $sale->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Fecha</label>
                            <input type="date" name="sale_date" value="{{ $sale->sale_date }}" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Canal / Ruta</label>
                            <select name="channel_id" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" required>
                                @foreach($channels as $channel)
                                    <option value="{{ $channel->id }}" {{ $sale->channel_id == $channel->id ? 'selected' : '' }}>
                                        {{ $channel->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Producto</label>
                            <select name="product_id" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" required>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $sale->product_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} - {{ $product->size }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Cajas Físicas</label>
                            <input type="number" name="quantity" min="1" value="{{ $sale->quantity }}" class="bg-slate-50 border border-slate-200 text-slate-900 rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full p-2.5" required>
                        </div>

                        <div class="flex justify-between items-center mt-8 pt-6 border-t border-slate-100">
                            <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-slate-700 font-bold transition-colors">Cancelar</a>
                            <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-bold rounded-lg text-sm px-6 py-3 transition-colors">
                                Actualizar Carga
                            </button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</x-app-layout>