<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuración de Precios s/Ruta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <p class="mb-4 text-sm text-gray-600">Asigna el precio unitario (o flete) de cada producto según el canal de distribución.</p>

                    <form action="{{ route('prices.store') }}" method="POST">
                        @csrf
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tamaño</th>
                                    
                                    <!-- Generamos las columnas dinámicamente según las zonas -->
                                    @foreach($channels as $channel)
                                        <th class="px-6 py-3 text-center text-xs font-medium text-blue-600 uppercase tracking-wider">
                                            {{ $channel->name }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($products as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->size }}</td>
                                        
                                        @foreach($channels as $channel)
                                            <!-- Buscamos si este producto ya tiene un precio asignado en este canal -->
                                            @php
                                                $assignedChannel = $product->channels->where('id', $channel->id)->first();
                                                $currentPrice = $assignedChannel ? $assignedChannel->pivot->price : '';
                                            @endphp
                                            
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number" step="0.01" min="0" 
                                                       name="prices[{{ $channel->id }}][{{ $product->id }}]" 
                                                       value="{{ $currentPrice }}" 
                                                       class="w-full text-center border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                       placeholder="0.00">
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-6 flex justify-end">
                            <x-primary-button>
                                {{ __('Guardar Todos los Precios') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>