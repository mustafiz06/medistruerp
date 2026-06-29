<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class SaleController extends Controller
{
    //-------------------sale form----------------------------
    public function create()
    {
        $customers = Customer::where('status', true)
            ->orderBy('name')
            ->get(['id', 'name', 'phone', 'email', 'address']);

        return view('sales.create', compact('customers'));
    }
}
