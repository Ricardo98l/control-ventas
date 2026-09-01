<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;
use App\Models\Product;

class PriceController extends Controller
{
    public function index()
    {
        // Traemos los canales y los productos (incluyendo los precios que ya tengan asignados)
        $channels = Channel::all();
        $products = Product::with('channels')->get();

        return view('prices.index', compact('channels', 'products'));
    }

    public function store(Request $request)
    {
        // El formulario enviará un arreglo multidimensional: prices[canal_id][producto_id] = precio
        if ($request->has('prices')) {
            foreach ($request->prices as $channelId => $productsData) {
                $channel = Channel::find($channelId);
                
                $syncData = [];
                foreach ($productsData as $productId => $price) {
                    // Si el precio no está vacío, lo preparamos para guardar
                    if ($price !== null) {
                        $syncData[$productId] = ['price' => $price];
                    }
                }
                
                // Sobrescribe y actualiza los precios en la tabla pivote para este canal
                $channel->products()->sync($syncData);
            }
        }

        return redirect()->route('dashboard')->with([
            'success' => '¡Lista de precios guardada con éxito!',
            'tab' => 'precios' // Le decimos a la vista qué pestaña abrir
        ]);
    }
}