<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseReturn;
use App\Models\StockMovement;
use App\Models\StockTransaction;
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


    //-----------------------------------------------------------------------------------------------------------

    public function store(Request $request)
    {
        $request->validate([
            'po_number' => 'required|unique:purchase_orders,po_number',
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $totalAmount = collect($request->products)->sum(function ($p) {
                return $p['quantity'] * $p['unit_price'];
            });
            $due_amount = $totalAmount - $request->paid_amount;
            $po = PurchaseOrder::create([
                'po_number' => $request->po_number,
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'paid_amount' => $request->paid_amount,
                'total_amount' => $totalAmount,
                'due_amount' => $due_amount,
                'status' => 'pending',
            ]);

            foreach ($request->products as $p) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $p['id'],
                    'quantity' => $p['quantity'],
                    'unit_price' => $p['unit_price'],
                ]);
            }

            Supplier::where('id', $request->supplier_id)
                ->increment('due_amount', $due_amount);
        });

        return redirect()
            ->route('po.list')
            ->with('notification', [
                'message' => 'Purchase Order created successfully!',
                'alert' => 'success'
            ]);
    }


    //===============================================================================

    public function poList()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')->latest()->get();
        return view('purchase.purchaseOrderList', compact('purchaseOrders'));
    }


    //==================================================update status strat====================

    public function updateStatus($id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancel',
        ]);

        $po = PurchaseOrder::with('items.product')->findOrFail($id);
        $oldStatus = $po->status;
        $newStatus = $request->status;

        // Prevent changes if already in final state
        if (in_array($oldStatus, ['completed', 'cancel'])) {
            $notification = array(
                'messege' => 'Status is at Final Stage. Cannot change it!',
                'alert' => 'warning'
            );
            return redirect()->back()->with('notification', $notification);
        }

        if ($newStatus === 'completed') {

            // Check if stock was already added
            $stockAlreadyAdded = StockMovement::where('reference', $po->po_number)
                ->where('type', 'in')
                ->exists();

            if (!$stockAlreadyAdded) {

                foreach ($po->items as $item) {
                    if ($item->product && $item->quantity > 0 && $item->unit_price > 0) {

                        // 1. Find or create inventory record
                        $inventory = Inventory::firstOrCreate(
                            [
                                'product_id' => $item->product_id,
                            ],
                            [
                                'current_stock' => 0,
                                'available_stock' => 0,
                                'current_stock_value' => 0,
                                'min_stock_level' => $item->product->alert_quantity ?? 0,
                                'stock_status' => 'in_stock',
                            ]
                        );

                        // 2. Calculate new values
                        $itemValue = $item->quantity * $item->unit_price;
                        $newStock = $inventory->current_stock + $item->quantity;
                        $newValue = $inventory->current_stock_value + $itemValue;

                        // 3. Update inventory
                        $inventory->update([
                            'current_stock' => $newStock,
                            'available_stock' => $newStock - $inventory->reserved_stock,
                            'current_stock_value' => round($newValue, 4),
                            'last_movement_at' => now(),
                            'stock_status' => $newStock <= $inventory->min_stock_level ? 'low_stock' : 'in_stock',
                        ]);

                        // 4. Log to stock_movements
                        StockMovement::create([
                            'product_id' => $item->product_id,
                            'type' => 'in',
                            'quantity' => $item->quantity,
                            'reference' => $po->po_number,
                        ]);

                        // 5. Log to stock_transactions
                        StockTransaction::create([
                            'product_id' => $item->product_id,
                            'type' => 'purchase',
                            'quantity' => $item->quantity,
                            'reference' => $po->po_number,
                        ]);
                    }
                }
            }

            // Update PO
            $po->update([
                'status' => 'completed',
                'due_amount' => 0,
                'status_changed_at' => now(),
            ]);
        } elseif ($newStatus === 'cancel') {

            if ($po->due_amount > 0) {
                Supplier::where('id', $po->supplier_id)->decrement('due_amount', $po->due_amount);
            }

            $po->update([
                'status' => 'cancel',
                'status_changed_at' => now(),
            ]);
        } elseif ($newStatus === 'pending') {

            $dueAmount = max(0, $po->total_amount - $po->paid_amount);
            $po->update([
                'status' => 'pending',
                'due_amount' => $dueAmount,
                'status_changed_at' => now(),
            ]);
        }

        $notification = array(
            'messege' => 'Status updated successfully!',
            'alert' => 'success'
        );
        return redirect()->back()->with('notification', $notification);
    }


    //-----------------------------update status end--------------------------------------------


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
