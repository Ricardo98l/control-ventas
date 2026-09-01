<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // ==========================================
    // 1. MOSTRAR EL DASHBOARD Y CIERRE MENSUAL
    // ==========================================
    public function index(Request $request)
    {
        // 1. Datos base para los formularios y la vista general
        $channels = Channel::all();
        $products = Product::with('channels')->get();
        $packagings = \App\Models\Packaging::all();
        
        // 2. Últimas ventas para la pestaña "Registro Diario"
        $recentSales = Sale::with(['channel', 'product'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 3. Fechas para el reporte de "Cierre Mensual"
        $mesSeleccionado = $request->input('mes', date('Y-m')); 
        $año = substr($mesSeleccionado, 0, 4);
        $mes = substr($mesSeleccionado, 5, 2);
        
        // 4. Preparamos la consulta del reporte
        $query = Sale::with(['channel', 'product.channels'])
            ->whereYear('sale_date', $año)
            ->whereMonth('sale_date', $mes);

        // --- ¡AQUÍ ESTÁ LA MAGIA DEL FILTRO POR RUTA! ---
        if ($request->filled('channel_id')) {
            $query->where('channel_id', $request->channel_id);
        }

        // Ejecutamos la consulta con o sin el filtro de ruta
        $ventasDelMes = $query->get();
            
        // 5. Construcción de la matriz matemática del reporte
        $reporteCierre = [];
        $granTotal = 0;
        
        foreach ($ventasDelMes as $venta) {
            $llave = $venta->channel_id . '-' . $venta->product_id;
            
            if (!isset($reporteCierre[$llave])) {
                $canalAsignado = $venta->product->channels->where('id', $venta->channel_id)->first();
                $precio = $canalAsignado ? $canalAsignado->pivot->price : 0;
                
                $reporteCierre[$llave] = [
                    'canal' => $venta->channel->name,
                    'producto' => $venta->product->name . ' (' . $venta->product->size . ')',
                    'precio' => $precio,
                    'cantidad_total' => 0,
                    'dinero_total' => 0,
                ];
            }
            
            $reporteCierre[$llave]['cantidad_total'] += $venta->quantity;
            $dineroVenta = $venta->quantity * $reporteCierre[$llave]['precio'];
            $reporteCierre[$llave]['dinero_total'] += $dineroVenta;
            $granTotal += $dineroVenta;
        }
        
        $reporteCierre = collect($reporteCierre)->sortBy('canal');

        // 6. Enviamos todo a la vista
        return view('dashboard', compact(
            'channels', 'products', 'recentSales', 
            'reporteCierre', 'mesSeleccionado', 'granTotal', 'packagings'
        ));
    }

    // ==========================================
    // 2. GUARDAR NUEVA VENTA
    // ==========================================
    public function store(Request $request)
    {
        $request->validate([
            'sale_date' => 'required|date',
            'channel_id' => 'required|exists:channels,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        Sale::create([
            'user_id' => 1,
            'channel_id' => $request->channel_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'sale_date' => $request->sale_date,
        ]);

        return redirect()->route('dashboard')->with([
            'success' => '¡Carga registrada correctamente!',
            'tab' => 'ventas'
        ]);
    }

    // ==========================================
    // 3. MOSTRAR PANTALLA DE EDICIÓN
    // ==========================================
    public function edit($id)
    {
        $sale = Sale::findOrFail($id);
        $channels = Channel::all();
        $products = Product::all();
        
        return view('sales.edit', compact('sale', 'channels', 'products'));
    }

    // ==========================================
    // 4. ACTUALIZAR VENTA MODIFICADA
    // ==========================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'sale_date' => 'required|date',
            'channel_id' => 'required|exists:channels,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $sale = Sale::findOrFail($id);
        
        $sale->update([
            'channel_id' => $request->channel_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'sale_date' => $request->sale_date,
        ]);

        return redirect()->route('dashboard')->with([
            'success' => '¡Carga actualizada correctamente!',
            'tab' => 'ventas'
        ]);
    }

    // ==========================================
    // 5. ELIMINAR VENTA
    // ==========================================
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return redirect()->route('dashboard')->with([
            'success' => '¡Registro eliminado correctamente!',
            'tab' => 'ventas'
        ]);
    }
}