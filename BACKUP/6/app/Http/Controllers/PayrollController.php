<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PayrollImport;

class PayrollController extends Controller



{

public function showUploadForm()
    {
        return view('payroll.upload');
    }

    public function uploadExcel(Request $request)
    {
        $this->validate($request, ['file' => 'required|file']);
        Excel::import(new PayrollImport, $request->file('file'));

        return back()->with('success', 'Payroll data imported successfully!');
    }

    public function getPayrollData($check_number)
    {
        $payroll = Payroll::where('check_number', $check_number)->first();
        return response()->json($payroll);
    }
}
