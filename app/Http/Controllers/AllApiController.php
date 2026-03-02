<?php

namespace App\Http\Controllers;
use App\Models\Product;

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
}
