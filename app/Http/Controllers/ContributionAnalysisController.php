<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContributionAnalysisController extends Controller
{
    public function index(Request $request)
    {
        // Fetch distinct department names for the dropdown
        $departments = DB::table('deduction_details')->distinct()->pluck('deptName');

        // Base query with grouping by checkNumber
        $query = DB::table('deduction_details')
            ->select(
                'checkNumber',
                DB::raw('MAX(nationalId) as nationalId'),
                DB::raw('MAX(firstName) as firstName'),
                DB::raw('MAX(middleName) as middleName'),
                DB::raw('MAX(lastName) as lastName'),
                DB::raw('MAX(monthlySalary) as monthlySalary'),
                DB::raw('MAX(voteCode) as voteCode'),
                DB::raw('MAX(deductionAmount) as deductionAmount'),
                DB::raw('MAX(deptName) as deptName'),
                DB::raw('MAX(checkDate) as checkDate')
            )
            ->where('deductionCode', '=', 667)
            ->groupBy('checkNumber');

        // Apply department filter
        if ($request->filled('deptName')) {
            $query->where('deptName', $request->deptName);
        }
        // Apply check date filter
        if ($request->filled('checkDate')) {
            $query->whereDate('checkDate', $request->checkDate);
        }
        // Apply deduction amount filter
        if ($request->filled('deduction_filter') && $request->filled('deduction_min')) {
            $deductionMin = $request->deduction_min;
            if ($request->deduction_filter == 'greater') {
                $query->where('deductionAmount', '>', $deductionMin);
            } elseif ($request->deduction_filter == 'less') {
                $query->where('deductionAmount', '<', $deductionMin);
            } elseif ($request->deduction_filter == 'between' && $request->filled('deduction_max')) {
                $query->whereBetween('deductionAmount', [$deductionMin, $request->deduction_max]);
            } elseif ($request->deduction_filter == 'exact') {
                $query->where('deductionAmount', $deductionMin);
            } elseif ($request->deduction_filter == 'greater_or_equal') {
                $query->where('deductionAmount', '>=', $deductionMin);
            } elseif ($request->deduction_filter == 'less_or_equal') {
                $query->where('deductionAmount', '<=', $deductionMin);
            }
        }

        $deductions = $query->get();
        $count = $deductions->count();

        return view('deductions.contribution_analysis', compact('departments', 'deductions', 'count'));
    }

    public function export(Request $request)
    {
        // Query ya kuchuja data
        $query = DB::table('deduction_details')
            ->select(
                'checkNumber',
                'nationalId',
                'firstName',
                'middleName',
                'lastName',
                'monthlySalary',
                'voteCode',
                'deductionAmount',
                'deptName',
                'checkDate'
            )
            ->where('deductionCode', '=', 667);

        // Apply filters
        if ($request->filled('deptName')) {
            $query->where('deptName', $request->deptName);
        }
        if ($request->filled('checkDate')) {
            $query->whereDate('checkDate', $request->checkDate);
        }
        if ($request->filled('deduction_filter') && $request->filled('deduction_min')) {
            $deductionMin = $request->deduction_min;
            if ($request->deduction_filter == 'greater') {
                $query->where('deductionAmount', '>', $deductionMin);
            } elseif ($request->deduction_filter == 'less') {
                $query->where('deductionAmount', '<', $deductionMin);
            } elseif ($request->deduction_filter == 'between' && $request->filled('deduction_max')) {
                $query->whereBetween('deductionAmount', [$deductionMin, $request->deduction_max]);
            } elseif ($request->deduction_filter == 'exact') {
                $query->where('deductionAmount', $deductionMin);
            } elseif ($request->deduction_filter == 'greater_or_equal') {
                $query->where('deductionAmount', '>=', $deductionMin);
            } elseif ($request->deduction_filter == 'less_or_equal') {
                $query->where('deductionAmount', '<=', $deductionMin);
            }
        }

        $deductions = $query->get();

        // Hifadhi kwenye faili la muda
        $filename = 'deduction_analysis_' . now()->format('YmdHis') . '.csv';
        $filepath = storage_path('app/' . $filename);

        $file = fopen($filepath, 'w');
        fputcsv($file, ['Check Number', 'National ID', 'First Name', 'Middle Name', 'Last Name', 'Monthly Salary', 'Vote Code', 'Deduction Amount', 'Department', 'Check Date']);

        foreach ($deductions as $deduction) {
            fputcsv($file, [
                $deduction->checkNumber,
                $deduction->nationalId,
                $deduction->firstName,
                $deduction->middleName,
                $deduction->lastName,
                $deduction->monthlySalary,
                $deduction->voteCode,
                $deduction->deductionAmount,
                $deduction->deptName,
                $deduction->checkDate
            ]);
        }
        fclose($file);

        // Download the file
        return response()->download($filepath)->deleteFileAfterSend(true);
    }
}