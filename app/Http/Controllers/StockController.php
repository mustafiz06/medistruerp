<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function openingStockForm()
    {
        $products = Product::orderBy('name')->get();
        return view('stock.bulkOpeningStock', compact('products'));
    }

    public function openingStockSave(Request $request)
    {
        foreach ($request->stocks as $stock) {
            $product = Product::find($stock['product_id']);
            $product->opening_stock = $stock['quantity'];
            $product->save();

            StockTransaction::create([
                'product_id' => $product->id,
                'type'       => 'opening',
                'quantity'   => $stock['quantity'],
                'reference'  => 'Opening Stock',
            ]);
        }

        return redirect()->back()->with('success', 'Bulk opening stock saved.');
    }

    public function stockReport()
    {
        return view('stock.stockReport');
    }
}
