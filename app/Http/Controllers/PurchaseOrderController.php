<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        $lastPo = PurchaseOrder::latest()->first();
        $poNumber = 'PO-' . str_pad($lastPo ? $lastPo->id + 1 : 1, 5, '0', STR_PAD_LEFT);
        return view('purchase.purchase', compact('products', 'suppliers', 'poNumber'));
    }
    public function getProductPrice($id)
    {
        $product = Product::find($id);
        return response()->json(['price' => $product->purchase_price]);
    }

//-----------------------------------------------------------------------------------------------------------

    public function store(Request $request)
    {
        $request->validate([
            'po_number' => 'required|unique:purchase_orders,po_number',
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $po = PurchaseOrder::create([
                'po_number' => $request->po_number,
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'total_amount' => collect($request->products)->sum(function ($p) {
                    return $p['quantity'] * $p['unit_price'];
                }),
                'status' => 'completed',
            ]);

            foreach ($request->products as $p) {
                $item = PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $p['id'],
                    'quantity' => $p['quantity'],
                    'unit_price' => $p['unit_price'],
                ]);

                $product = Product::find($p['id']);
                $product->stock += $p['quantity'];
                $product->save();

                StockMovement::create([
                    'product_id' => $p['id'],
                    'type' => 'in',
                    'quantity' => $p['quantity'],
                    'reference' => $po->po_number,
                    'notes' => 'Stock added via PO',
                ]);
            }
        });
        $notification = array(
            'messege' => 'Purchase Order successfully!',
            'alert' => 'success'
        );
        return redirect()
            ->route('po.list')
            ->with('notification', $notification);
    }


    //===============================================================================

    public function poList()
    {
        $purchaseOrders = PurchaseOrder::all();
        return view('purchase.purchaseOrderList', compact('purchaseOrders'));
    }

    
}
