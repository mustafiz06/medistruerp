<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('productSetting.brand', compact('brands'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'slug' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        Brand::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'status' => $request->status,
        ]);

        $notification = array(
                'messege' => 'Brand Added successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
    }


    public function delete($id)
    {

        $brand = Brand::findOrFail($id);


        $brand->delete();

        $notification = array(
                'messege' => 'Brand Deleted successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
    }


    public function status($id)
    {
        $brand = Brand::where('id', $id)->first();

        if ($brand->status == 1) {
            $brand = Brand::find($id);
            $brand->status = 0;
            $brand->created_at = now();
            $brand->save();
            $notification = array(
                'messege' => 'status change successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
        } else {
            $brand = Brand::find($id);
            $brand->status = 1;
            $brand->created_at = now();
            $brand->save();
            $notification = array(
                'messege' => 'status change successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
        }
    }


    public function edit(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug,' . $id,
            'status' => 'required|in:0,1',
        ]);

        $brand = Brand::findOrFail($id);
        $brand->title = $request->title;
        $brand->slug = $request->slug;
        $brand->status = $request->status;
        $brand->save();
        $notification = array(
                'messege' => 'Brand Update successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
    }
}
