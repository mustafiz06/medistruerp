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

    public function returnForm($id)
    {
        $po = PurchaseOrder::with(['items.product', 'returns', 'supplier'])->findOrFail($id);

        if ($po->status !== 'completed') {
            return redirect()->back()->with('notification', [
                'messege' => 'Only completed Purchase Orders can have returns.',
                'alert' => 'warning'
            ]);
        }

        return view('purchase.return', compact('po'));
    }
    //-----------------------------po return start------------------------------------

    public function storeReturn(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'products' => 'required|array',
            'products.*.quantity' => 'nullable|integer|min:0',
            'reason' => 'nullable|string|max:500',
        ]);

        $hasValidQuantity = collect($validated['products'])
            ->filter(fn($p) => ($p['quantity'] ?? 0) > 0)
            ->isNotEmpty();

        if (!$hasValidQuantity) {
            return redirect()->back()
                ->withInput()
                ->with('notification', [
                    'messege' => 'Please enter return quantity for at least one product.',
                    'alert' => 'warning'
                ]);
        }

        try {
            DB::transaction(function () use ($validated, $request) {

                $po = PurchaseOrder::with(['items', 'returns'])->findOrFail($validated['purchase_order_id']);
                $totalReturn = 0;

                foreach ($validated['products'] as $productId => $data) {
                    $qty = (int) ($data['quantity'] ?? 0);

                    if ($qty <= 0) continue;

                    $item = $po->items->firstWhere('product_id', $productId);
                    if (!$item) {
                        throw new \Exception("Product ID {$productId} not found in this Purchase Order");
                    }

                    $alreadyReturned = $po->returns->where('product_id', $productId)->sum('quantity');
                    $available = $item->quantity - $alreadyReturned;

                    if ($qty > $available) {
                        throw new \Exception("Only {$available} units can be returned for product {$item->product->name}");
                    }

                    $inventory = Inventory::where('product_id', $productId)->first();
                    if ($inventory) {
                        $itemValue = $qty * $item->unit_price;

                        $inventory->decrement('current_stock', $qty);
                        $inventory->decrement('available_stock', $qty);
                        $inventory->decrement('current_stock_value', $itemValue);

                        if ($inventory->current_stock <= $inventory->min_stock_level) {
                            $inventory->update(['stock_status' => 'low_stock']);
                        }
                    }
                    $lineTotal = $qty * $item->unit_price;
                    $return = PurchaseReturn::create([
                        'purchase_order_id' => $po->id,
                        'product_id' => $productId,
                        'quantity' => $qty,
                        'unit_price' => $item->unit_price,
                        'total' => $lineTotal,
                        'return_date' => now(),
                        'reason' => $validated['reason'] ?? null,
                    ]);

                    StockMovement::create([
                        'product_id' => $productId,
                        'type' => 'out',
                        'quantity' => $qty,
                        'reference' => "PR-{$return->id}",
                        'notes' => "Return against PO #{$po->po_number}",
                    ]);

                    StockTransaction::create([
                        'product_id' => $productId,
                        'type' => 'return',
                        'quantity' => $qty,
                        'reference' => "PR-{$return->id}",
                    ]);

                    if ($po->supplier) {
                        Supplier::where('id', $po->supplier_id)
                            ->decrement('due_amount', $lineTotal);
                    }

                    $totalReturn += $lineTotal;
                }

                $totalOrdered = $po->items->sum('quantity');
                $totalReturned = $po->returns->sum('quantity');

                if ($totalReturned >= $totalOrdered) {
                    $po->update(['status' => 'fully_returned']);
                }
            });

            return redirect()->back()->with('notification', [
                'messege' => 'Purchase Return processed successfully!',
                'alert' => 'success'
            ]);
        } catch (\Throwable $e) {
            \Log::error('Return Failed: ' . $e->getMessage(), [
                'po_id' => $request->purchase_order_id,
                'user' => auth()->id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('notification', [
                    'messege' => 'Error: ' . $e->getMessage(),
                    'alert' => 'error'
                ]);
        }
    }

    //-----------------------------po return end------------------------------------


    public function returnList()
    {
        $returns = PurchaseReturn::with(['purchaseOrder.supplier', 'product'])->latest()->get();
        return view('purchase.returnList', compact('returns'));
    }
}
