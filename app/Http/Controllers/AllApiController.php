<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AllApiController extends Controller
{

    public function getProduct($id)
    {
        $product = Product::where('id', $id)->where('is_active', true)->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'error' => 'Product not found or inactive',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'product_id' => $product->id,
                'sku' => $product->sku,
                'product_name' => $product->name,
                'product_description' => $product->description,
                'purchase_price' => $product->purchase_price,
                'sales_price' => $product->sales_price,
                'product_name' => $product->name,
                'alert_quantity' => $product->alert_quantity,

            ]
        ]);
    }

    
    
    /**
     * Search products by name, SKU, or barcode
     */
    public function searchProducts(Request $request): JsonResponse
    {
        $query = trim($request->input('q', ''));
        $limit = min((int) $request->input('limit', 10), 50);

        // Return empty if query too short
        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'data' => [],
                'count' => 0,
                'message' => 'Enter at least 2 characters'
            ]);
        }

        // Search query
        $products = Product::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('sku', 'LIKE', "%{$query}%")
                  ->orWhere('barcode', 'LIKE', "%{$query}%");
            })
            ->with(['inventory'])  // Only load what we need
            ->limit($limit)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku ?? '',
                    'barcode' => $product->barcode ?? '',
                    'price' => number_format($product->sales_price ?? 0, 2),
                    'stock' => $product->inventory?->current_stock ?? 0,
                    'available' => $product->inventory?->available_stock ?? 0,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $products,
            'count' => $products->count()
        ]);
    }

}
