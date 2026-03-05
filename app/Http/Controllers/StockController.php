<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display stock report
     */
    public function stockReport(Request $request)
    {
        // Build query
        $query = Inventory::with(['product.category'])
            ->whereHas('product', function ($q) {
                $q->where('is_active', true);
            });

        // Search filter
        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('sku', 'LIKE', "%{$request->search}%");
            });
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Status filter
        if ($request->filled('stock_status')) {
            $status = $request->stock_status;
            if ($status === 'out_of_stock') {
                $query->where('current_stock', '<=', 0);
            } elseif ($status === 'low_stock') {
                $query->whereColumn('current_stock', '<=', 'min_stock_level')
                      ->where('current_stock', '>', 0);
            } elseif ($status === 'in_stock') {
                $query->whereColumn('current_stock', '>', 'min_stock_level');
            }
        }

        // Low stock only
        if ($request->boolean('low_stock_only')) {
            $query->whereColumn('current_stock', '<=', 'min_stock_level')
                  ->where('current_stock', '>', 0);
        }

        // Get results
        $inventories = $query->orderBy('product_id')->paginate(25);

        // Get statistics
        $stats = DB::table('inventories')
            ->join('products', 'inventories.product_id', '=', 'products.id')
            ->where('products.is_active', true)
            ->selectRaw('
                COUNT(*) as total_products,
                SUM(current_stock_value) as total_value,
                SUM(CASE WHEN current_stock <= 0 THEN 1 ELSE 0 END) as out_of_stock_count,
                SUM(CASE WHEN current_stock <= min_stock_level AND current_stock > 0 THEN 1 ELSE 0 END) as low_stock_count
            ')
            ->first();

        $categories = Category::orderBy('title')->pluck('title', 'id');

        return view('stock.stockReport', compact('inventories', 'stats', 'categories'));
    }

    //----------------------------Export CSV stock report ------------------------------------
    public function reportExport(Request $request)
    {
        $filename = 'stock_report_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['SKU', 'Product Name', 'Category', 'Current Stock', 'Available', 'Unit Cost', 'Total Value', 'Status']);
            
            Inventory::with(['product.category'])
                ->whereHas('product', function ($q) {
                    $q->where('is_active', true);
                })
                ->chunk(500, function ($inventories) use ($file) {
                    foreach ($inventories as $inventory) {
                        $product = $inventory->product;
                        $currentStock = $inventory->current_stock ?? 0;
                        $stockValue = $inventory->current_stock_value ?? 0;
                        $unitCost = $currentStock > 0 ? ($stockValue / $currentStock) : 0;
                        
                        fputcsv($file, [
                            $product->sku ?? 'N/A',
                            $product->name ?? 'Unknown',
                            $product->category?->title ?? '',
                            number_format($currentStock, 2),
                            number_format($inventory->available_stock ?? 0, 2),
                            number_format($unitCost, 2),
                            number_format($stockValue, 2),
                            $inventory->stock_status ?? 'in_stock',
                        ]);
                    }
                });
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}