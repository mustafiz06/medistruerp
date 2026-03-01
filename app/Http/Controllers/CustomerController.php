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
        $rules = [
            'customer_type' => 'required|in:individual,organization',
            'email'         => 'nullable|email|max:150',
            'phone'         => 'nullable|string|max:20',
            'credit_limit'  => 'nullable|numeric|min:0',
        ];

        if ($request->customer_type === 'individual') {
            $rules['name'] = 'required|string|max:150';
        }

        if ($request->customer_type === 'organization') {
            $rules['company_name'] = 'required|string|max:150';
        }

        $validated = $request->validate($rules);

        $customerCode = $this->generateCustomerCode($request->customer_type);

        $customer = Customer::create([
            'customer_code'            => $customerCode,
            'customer_type'            => $request->customer_type,

            // Individual
            'name'                     => $request->name,
            'designation'              => $request->designation,
            'work_place'               => $request->work_place,
            'gender'                   => $request->gender,

            // Organization
            'company_name'             => $request->company_name,
            'contact_person'           => $request->contact_person,
            'contact_person_position'  => $request->contact_person_position,
            'contact_person_phone'     => $request->contact_person_phone,
            'bin_no'                   => $request->bin_no,

            // Common
            'email'                    => $request->email,
            'phone'                    => $request->phone,
            'address'                  => $request->address,
            'credit_limit'             => $request->credit_limit ?? 0,
            'status'                   => 'active',
            'priority'                 => $request->priority,
            'notes'                 => $request->notes,
        ]);

        return redirect()
            ->route('customer.index')
            ->with('success', 'Customer created successfully.');
    }

    private function generateCustomerCode($type)
    {
        $prefix = $type === 'individual' ? 'IND' : 'ORG';

        $lastCustomer = Customer::where('customer_type', $type)
            ->latest()
            ->first();

        if ($lastCustomer) {
            $number = intval(substr($lastCustomer->customer_code, -5)) + 1;
        } else {
            $number = 1;
        }

        return $prefix . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
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

   public function edit(Customer $customer)
    {
        return view('customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
{
    $customer = Customer::findOrFail($id);
    
    $request->validate([
        'customer_type' => 'required',
        'name' => 'required_if:customer_type,individual',
        'company_name' => 'required_if:customer_type,organization',
        'contact_person' => 'required_if:customer_type,organization',
        'phone' => 'required',
        'status' => 'required',
    ]);
    
    $customer->update($request->all());
    
    return redirect()->route('customer.index')
        ->with('success', 'Customer updated successfully!');
}


    //account
    public function dueList(Request $request)
    {
        $query = Customer::query()
            ->where('due_amount', '>', 0);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('responsible_person', 'like', "%{$search}%")
                    ->orWhere('responsible_person_contact', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderByDesc('due_amount')
            ->paginate(20)
            ->withQueryString();

        $totalDue = (clone $query)->sum('due_amount');

        return view('customer.dueList', compact('customers', 'totalDue'));
    }
}
