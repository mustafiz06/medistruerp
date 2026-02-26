<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierPaymentController extends Controller
{
    public function supplierWisePaymentForm()
    {
        $suppliers = Supplier::where('due_amount', '>', 0)->get();
        return view('supplier.supplierPayment', compact('suppliers'));
    }
    // AJAX
    public function getSupplierPOs($supplierId)
{
    $pos = PurchaseOrder::with('payments')
        ->where('supplier_id', $supplierId)
        ->get()
        ->map(function ($po) {
            $paid = $po->payments->sum('paid_amount');
            $po->payments_sum_paid_amount = $paid;
            $po->due = $po->total_amount - $paid;
            return $po;
        })
        ->filter(function ($po) {
            return $po->due > 0; // only include POs with due
        })
        ->values(); // reindex the collection

    return response()->json($pos);
}

    // Store
    public function storeSupplierPayments(Request $request)
    {
        dd($request->all());
    }
}
