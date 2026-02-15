<?php

namespace App\Http\Controllers\accountSetting;

use App\Http\Controllers\Controller;
use App\Models\paymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = paymentMethod::all();
        return view('accountSetting.paymentMethod', compact('methods'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
        ]);

        paymentMethod::create([
            'title' => $request->title,
            'status' => 1,
        ]);

        $notification = array(
                'messege' => 'payment Method Added successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
    }


    public function delete($id)
    {

        $paymentMethod = paymentMethod::findOrFail($id);


        $paymentMethod->delete();

        $notification = array(
                'messege' => 'payment Method Delete successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
    }


    public function status($id)
    {
        $paymentMethod = paymentMethod::where('id', $id)->first();

        if ($paymentMethod->status == 1) {
            $paymentMethod = paymentMethod::find($id);
            $paymentMethod->status = 0;
            $paymentMethod->created_at = now();
            $paymentMethod->save();
            $notification = array(
                'messege' => 'status change successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
        } else {
            $paymentMethod = paymentMethod::find($id);
            $paymentMethod->status = 1;
            $paymentMethod->created_at = now();
            $paymentMethod->save();
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

        $paymentMethod = paymentMethod::findOrFail($id);
        $paymentMethod->title = $request->title;
        $paymentMethod->save();
        $notification = array(
                'messege' => 'payment Method Updated successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
    }
}
