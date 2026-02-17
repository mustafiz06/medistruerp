<?php

namespace App\Http\Controllers\productSetting;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('productSetting.unit', compact('units'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'status' => 'required|boolean',
        ]);

        Unit::create([
            'title' => $request->title,
            'status' => $request->status,
        ]);

        $notification = array(
                'messege' => 'Unit Added successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
    }


    public function delete($id)
    {

        $unit = Unit::findOrFail($id);


        $unit->delete();

        $notification = array(
                'messege' => 'Unit Delete successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
    }


    public function status($id)
    {
        $unit = Unit::where('id', $id)->first();

        if ($unit->status == 1) {
            $unit = Unit::find($id);
            $unit->status = 0;
            $unit->created_at = now();
            $unit->save();
            $notification = array(
                'messege' => 'status change successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
        } else {
            $unit = Unit::find($id);
            $unit->status = 1;
            $unit->created_at = now();
            $unit->save();
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
            'status' => 'required|in:0,1',
        ]);

        $unit = Unit::findOrFail($id);
        $unit->title = $request->title;
        $unit->status = $request->status;
        $unit->save();
        $notification = array(
                'messege' => 'Unit Updatd successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
    }
}

