<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseReturn;
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
        $purchaseOrders = PurchaseOrder::with('supplier')->latest()->get();
        return view('purchase.purchaseOrderList', compact('purchaseOrders'));
    }

    //delte
    public function destroy($id)
    {

        $purchaseOrder = PurchaseOrder::findOrFail($id);


        $purchaseOrder->delete();

        $notification = array(
            'messege' => 'Purchase Order Delete successfully!',
            'alert' => 'success'
        );
        return redirect()->back()->with('notification', $notification);
    }


    //======================================po View============================
    public function view($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        return view('purchase.purchaseOrderView', compact('po'));
    }


    //====================Return=================================

    public function returnForm($id)
    {
        $po = PurchaseOrder::with('items.product')->findOrFail($id);
        return view('purchase.return', compact('po'));
    }

    public function storeReturn(Request $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->products as $productId => $productData) {
                $qty = $productData['quantity'] ?? 0;

                if ($qty > 0) {
                    $product = Product::findOrFail($productId);
                    $product->stock -= $qty;
                    $product->save();

                    PurchaseReturn::create([
                        'purchase_order_id' => $request->purchase_order_id,
                        'product_id' => $productId,
                        'quantity' => $qty,
                        'unit_price' => $product->purchase_price,
                        'total' => $qty * $product->purchase_price,
                        'return_date' => now(),
                    ]);

                    StockMovement::create([
                        'product_id' => $productId,
                        'type' => 'out',
                        'quantity' => $qty,
                        'reference' => 'PO-RETURN',
                    ]);
                }
            }
        });

        return redirect()->back()->with('notification', [
            'messege' => 'Purchase Return Completed!',
            'alert' => 'success'
        ]);
    }

    public function returnList()
    {
        $returns = PurchaseReturn::with(['purchaseOrder.supplier', 'product'])->latest()->get();
        return view('purchase.returnList', compact('returns'));
    }
}
