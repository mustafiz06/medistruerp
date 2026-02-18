<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customer/customer', compact('customers'));
    }
public function add()
    {
        return view('customer/customerAdd');
    }




    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
        ]);

        Customer::create([
            'name' => $request->name,
            'designation' => $request->designation,
            'address' => $request->address,
            'contact' => $request->contact,
            'responsible_person' => $request->responsible_person,
            'responsible_person_contact' => $request->responsible_person_contact,
        ]);
        $notification = array(
            'messege' => 'customer Added successfully!',
            'alert' => 'success'
        );
        return redirect()->back()->with('notification', $notification);
    }






    public function delete($id)
    {

        $customer = Customer::findOrFail($id);


        $customer->delete();

        $notification = array(
            'messege' => 'customer Deleted successfully!',
            'alert' => 'success'
        );
        return redirect()->back()->with('notification', $notification);
    }


    public function status($id)
    {
        $customer = Customer::where('id', $id)->first();

        if ($customer->status == 1) {
            $customer = Customer::find($id);
            $customer->status = 0;
            $customer->created_at = now();
            $customer->save();
            $notification = array(
                'messege' => 'Status change successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
        } else {
            $customer = Customer::find($id);
            $customer->status = 1;
            $customer->created_at = now();
            $customer->save();
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

        $customer = Customer::findOrFail($id);
        $customer->title = $request->title;
        $customer->slug = $request->slug;
        $customer->status = $request->status;
        $customer->save();
        $notification = array(
            'messege' => 'customer Updated successfully!',
            'alert' => 'success'
        );
        return redirect()->back()->with('notification', $notification);
    }
}

