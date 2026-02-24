<?php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessSetupController extends Controller
{
    public function company_profile()
    {
        $company_info = CompanyInfo::first();
        return view('businessSetup/index', compact('company_info'));
    }


    public function company_update(Request $request)
    {
        $company = CompanyInfo::firstOrNew([]);

        $company->name = $request->name;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->website = $request->website;
        $company->address = $request->address;
        $company->vat_number = $request->vat_number;
        $company->tax_number = $request->tax_number;
        $company->default_vat_percent = $request->default_vat_percent ?? 0;
        $company->default_ait_percent = $request->default_ait_percent ?? 0;
        $company->invoice_prefix = $request->invoice_prefix ?? 'INV-';
        $company->challan_prefix = $request->challan_prefix ?? 'CN-';
        $company->po_prefix = $request->po_prefix ?? 'PO-';
        $company->invoice_start_number = $request->invoice_start_number ?? 1;
        $company->invoice_footer_title = $request->invoice_footer_title;
        $company->footer_message = $request->footer_message;
        $company->currency_name = $request->currency_name ?? 'Taka';
        $company->currency_symbol = $request->currency_symbol ?? '৳';
        $company->currency_position = $request->currency_position ?? 'left';
        $company->registration_number = $request->registration_number;

        if ($request->hasFile('logo')) {
            if ($company->logo && Storage::exists('public/company/' . $company->logo)) {
                Storage::delete('public/company/' . $company->logo);
            }
            $file = $request->file('logo');
            $filename = 'logo.' . $file->getClientOriginalExtension();
            $file->storeAs('company', $filename, 'public');
            $company->logo = 'company/' . $filename;
        }

        if ($request->hasFile('favicon')) {
            if ($company->favicon && Storage::exists('public/company/' . $company->favicon)) {
                Storage::delete('public/company/' . $company->favicon);
            }
            $file = $request->file('favicon');
            $filename = 'favicon.' . $file->getClientOriginalExtension();
            $file->storeAs('company', $filename, 'public');
            $company->favicon = 'company/' . $filename;
            
        }
        $company->save();

        $notification = array(
            'messege' => 'Company information updated successfully.',
            'alert' => 'success'
        );
        return redirect()
            ->back()
            ->with('notification', $notification);
    }
}
