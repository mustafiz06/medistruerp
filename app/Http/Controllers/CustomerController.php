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



    public function edit_view($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer/customerEdit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:50',
            'responsible_person' => 'nullable|string|max:255',
            'responsible_person_contact' => 'nullable|string|max:50',
        ]);

        $customer->update($request->all());

        return redirect()
            ->route('customer.index')
            ->with('success', 'Customer updated successfully.');
    }
}
