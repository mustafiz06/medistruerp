<?php

namespace App\Http\Controllers\productSetting;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('productSetting/product', compact('products'));
    }
    public function add()
    {
        $categories = Category::where('status', 1)->get();
        $brands     = Brand::where('status', 1)->get();
        $units      = Unit::where('status', 1)->get();
        $countries  = Country::where('status', 1)->get();

        return view('productSetting/productAdd', compact('categories', 'brands', 'units', 'countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'sku'            => 'nullable|string|max:255|unique:products,sku',
            'description'    => 'nullable|string',
            'category_id'    => 'nullable|exists:categories,id',
            'brand_id'       => 'nullable|exists:brands,id',
            'unit_id'        => 'nullable|exists:units,id',
            'origin_id'      => 'nullable|exists:countries,id',
            'purchase_price' => 'nullable|numeric|min:0',
            'sales_price'    => 'nullable|numeric|min:0',
            'alert_quantity' => 'nullable|integer|min:0',
        ]);

        $sku = $validated['sku'] ?? 'SKU-' . strtoupper(uniqid());
        while (Product::where('sku', $sku)->exists()) {
            $sku = 'SKU-' . strtoupper(uniqid());
        }


        Product::create([
            'name'           => $validated['name'],
            'sku'            => $sku,
            'description'    => $validated['description'] ?? null,
            'category_id'    => $validated['category_id'] ?? null,
            'brand_id'       => $validated['brand_id'] ?? null,
            'unit_id'        => $validated['unit_id'] ?? null,
            'origin_id'      => $validated['origin_id'] ?? null,
            'purchase_price' => $validated['purchase_price'] ?? 0,
            'sales_price'    => $validated['sales_price'] ?? 0,
            'alert_quantity' => $validated['alert_quantity'] ?? 0,
            'is_active'      => '1',
        ]);

        $notification = array(
            'messege' => 'Product Added successfully!',
            'alert' => 'success'
        );
        return redirect()
            ->route('product.index')
            ->with('notification', $notification);
    }

    public function delete($id)
    {

        $product = Product::findOrFail($id);


        $product->delete();

        $notification = array(
            'messege' => 'Product Deleted successfully!',
            'alert' => 'success'
        );
        return redirect()->back()->with('notification', $notification);
    }


    public function edit_view($id)
    {  
        $categories = Category::where('status', 1)->get();
        $brands     = Brand::where('status', 1)->get();
        $units      = Unit::where('status', 1)->get();
        $countries  = Country::where('status', 1)->get();

        $product = Product::findOrFail($id);
        return view('productSetting/productEdit', compact('product', 'categories', 'brands', 'units', 'countries'));
    }
}
