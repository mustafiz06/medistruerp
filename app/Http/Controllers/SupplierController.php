<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('supplier/supplier', compact('suppliers'));
    }
    public function add()
    {
        return view('supplier/supplierAdd');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
        ]);

        Supplier::create([
            'name' => $request->name,
            'address' => $request->address,
            'responsible_person' => $request->responsible_person,
            'responsible_person_contact' => $request->responsible_person_contact,
        ]);
        $notification = array(
            'messege' => 'supplier Added successfully!',
            'alert' => 'success'
        );
        return redirect()
            ->route('supplier.index')
            ->with('notification', $notification);
    }

    public function delete($id)
    {

        $supplier = Supplier::findOrFail($id);


        $supplier->delete();

        $notification = array(
            'messege' => 'supplier Deleted successfully!',
            'alert' => 'success'
        );
        return redirect()->back()->with('notification', $notification);
    }



    public function edit_view($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('supplier/supplierEdit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'responsible_person' => 'nullable|string|max:255',
            'responsible_person_contact' => 'nullable|string|max:50',
        ]);

        $supplier->update($request->all());

        $notification = array(
            'messege' => 'supplier updated successfully!',
            'alert' => 'success'
        );
        return redirect()
            ->route('supplier.index')
            ->with('notification', $notification);
    }

    //account
    public function dueList(Request $request)
{
    $query = Supplier::query()
        ->where('due_amount', '>', 0);

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('responsible_person', 'like', "%{$search}%")
              ->orWhere('responsible_person_contact', 'like', "%{$search}%");
        });
    }

    $suppliers = $query->orderByDesc('due_amount')
        ->paginate(20)
        ->withQueryString();

    $totalDue = (clone $query)->sum('due_amount');

    return view('supplier.dueList', compact('suppliers', 'totalDue'));
}
}
