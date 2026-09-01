<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Historial Completo Paginado (15 registros por página)
        $allSales = Sale::with(['channel', 'product'])->orderBy('sale_date', 'desc')->orderBy('created_at', 'desc')->paginate(15);

        // 2. Datos para el Gráfico de Barras (Últimos 7 días)
        $ventasPorDia = Sale::selectRaw('sale_date, SUM(quantity) as total')
            ->groupBy('sale_date')
            ->orderBy('sale_date', 'desc')
            ->take(7)
            ->get()
            ->reverse(); // Lo invertimos para que el gráfico vaya de más viejo a más nuevo

        $diasFormateados = $ventasPorDia->pluck('sale_date')->map(function($date) {
            return Carbon::parse($date)->format('d M');
        });
        $totalesPorDia = $ventasPorDia->pluck('total');

        // 3. Datos para el Gráfico de Dona (Participación por Canal este mes)
        $ventasPorCanal = Sale::with('channel')
            ->selectRaw('channel_id, SUM(quantity) as total')
            ->whereMonth('sale_date', date('m'))
            ->whereYear('sale_date', date('Y'))
            ->groupBy('channel_id')
            ->get();
            
        $nombresCanales = $ventasPorCanal->map(function($item) { return $item->channel->name; });
        $totalesPorCanal = $ventasPorCanal->pluck('total');

        return view('reports.index', compact(
            'allSales', 'diasFormateados', 'totalesPorDia', 'nombresCanales', 'totalesPorCanal'
        ));
    }
}