<?php

namespace App\Http\Controllers\productSetting;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        return view('productSetting.country', compact('countries'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
        ]);

        Country::create([
            'title' => $request->title,
            'status' => 1,
        ]);

        $notification = array(
                'messege' => 'country Added successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
    }


    public function delete($id)
    {

        $country = Country::findOrFail($id);


        $country->delete();

        $notification = array(
                'messege' => 'country Delete successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
    }


    public function status($id)
    {
        $country = Country::where('id', $id)->first();

        if ($country->status == 1) {
            $country = Country::find($id);
            $country->status = 0;
            $country->created_at = now();
            $country->save();
            $notification = array(
                'messege' => 'status change successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
        } else {
            $country = Country::find($id);
            $country->status = 1;
            $country->created_at = now();
            $country->save();
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
        ]);

        $country = Country::findOrFail($id);
        $country->title = $request->title;
        $country->save();
        $notification = array(
                'messege' => 'country Updated successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
    }
}