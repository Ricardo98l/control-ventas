<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;
use App\Models\Product;

class CatalogController extends Controller
{
    public function storeChannel(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:channels,name']);
        Channel::create(['name' => $request->name]);
        return redirect()->route('dashboard')->with(['success' => '¡Nuevo Canal/Ruta agregado exitosamente!', 'tab' => 'catalogo']);
    }

    public function storeProduct(Request $request)
    {
        // 1. Validamos que el envase exista en la base de datos
        $request->validate([
            'name' => 'required|string|max:255', 
            'size' => 'required|string|max:50',
            'packaging_id' => 'required|exists:packagings,id'
        ]);

        // 2. Guardamos con el nuevo campo packaging_id
        Product::create([
            'name' => $request->name,
            'size' => $request->size,
            'packaging_id' => $request->packaging_id
        ]);
        
        return redirect()->route('dashboard')->with(['success' => '¡Nueva bebida agregada!', 'tab' => 'precios']);
    }

    public function updateProduct(Request $request, $id)
    {
        // 1. Validamos también en la edición
        $request->validate([
            'name' => 'required|string|max:255', 
            'size' => 'required|string|max:50',
            'packaging_id' => 'required|exists:packagings,id'
        ]);

        $product = Product::findOrFail($id);
        
        // 2. Actualizamos con el nuevo campo
        $product->update([
            'name' => $request->name,
            'size' => $request->size,
            'packaging_id' => $request->packaging_id
        ]);
        
        return redirect()->route('dashboard')->with(['success' => '¡Bebida actualizada correctamente!', 'tab' => 'precios']);
    }

    public function destroyProduct($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('dashboard')->with(['success' => '¡Producto eliminado del sistema!', 'tab' => 'precios']);
    }

    public function storePackaging(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        \App\Models\Packaging::create(['name' => $request->name]);
        return redirect()->route('dashboard')->with(['success' => '¡Nuevo tipo de envase agregado!', 'tab' => 'catalogo']);
    }

    public function destroyPackaging($id)
    {
        \App\Models\Packaging::findOrFail($id)->delete();
        return redirect()->route('dashboard')->with(['success' => '¡Envase eliminado!', 'tab' => 'catalogo']);
    }
}