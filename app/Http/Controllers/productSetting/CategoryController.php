<?php

namespace App\Http\Controllers\productSetting;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('productSetting.category', compact('categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'slug' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        Category::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'status' => $request->status,
        ]);
        $notification = array(
            'messege' => 'Category Added successfully!',
            'alert' => 'success'
        );
        return redirect()->back()->with('notification', $notification);
    }


    public function delete($id)
    {

        $category = Category::findOrFail($id);


        $category->delete();

        $notification = array(
            'messege' => 'Category Deleted successfully!',
            'alert' => 'success'
        );
        return redirect()->back()->with('notification', $notification);
    }


    public function status($id)
    {
        $category = Category::where('id', $id)->first();

        if ($category->status == 1) {
            $category = Category::find($id);
            $category->status = 0;
            $category->created_at = now();
            $category->save();
            $notification = array(
                'messege' => 'Status change successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
        } else {
            $category = Category::find($id);
            $category->status = 1;
            $category->created_at = now();
            $category->save();
            $notification = array(
                'messege' => 'status change successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug,' . $id,
            'status' => 'required|in:0,1',
        ]);

        $category = Category::findOrFail($id);
        $category->title = $request->title;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->save();
        $notification = array(
            'messege' => 'Category Updated successfully!',
            'alert' => 'success'
        );
        return redirect()->back()->with('notification', $notification);
    }
}
