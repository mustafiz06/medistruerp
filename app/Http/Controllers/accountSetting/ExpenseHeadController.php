<?php

namespace App\Http\Controllers\accountSetting;

use App\Http\Controllers\Controller;
use App\Models\ExpenseHead;
use Illuminate\Http\Request;

class ExpenseHeadController extends Controller
{
    public function index()
    {
        $methods = ExpenseHead::all();
        return view('accountSetting.expenseHead', compact('methods'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
        ]);

        ExpenseHead::create([
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

        $expenseHead = ExpenseHead::findOrFail($id);


        $expenseHead->delete();

        $notification = array(
                'messege' => 'payment Method Delete successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
    }


    public function status($id)
    {
        $expenseHead = ExpenseHead::where('id', $id)->first();

        if ($expenseHead->status == 1) {
            $expenseHead = ExpenseHead::find($id);
            $expenseHead->status = 0;
            $expenseHead->created_at = now();
            $expenseHead->save();
            $notification = array(
                'messege' => 'status change successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
        } else {
            $expenseHead = ExpenseHead::find($id);
            $expenseHead->status = 1;
            $expenseHead->created_at = now();
            $expenseHead->save();
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

        $expenseHead = ExpenseHead::findOrFail($id);
        $expenseHead->title = $request->title;
        $expenseHead->save();
        $notification = array(
                'messege' => 'payment Method Updated successfully!',
                'alert' => 'success'
            );
            return redirect()->back()->with('notification', $notification);
    }
}
