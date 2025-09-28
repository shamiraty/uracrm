<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeductionDifferencesController extends Controller
{
    public function index(Request $request)
    {
        // Fetch distinct department names for the dropdown
        $departments = DB::table('deduction_details')
            ->where('deductionCode', 667)
            ->distinct()
            ->pluck('deptName');

        $differences = [];

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $department = $request->deptName;

            $query = DB::table('deduction_details')
                ->select(
                    'checkNumber',
                    DB::raw('YEAR(checkDate) as year'),
                    DB::raw('MONTH(checkDate) as month'),
                    DB::raw('MAX(deductionAmount) as deductionAmount'),
                    DB::raw('MAX(firstName) as firstName'),
                    DB::raw('MAX(middleName) as middleName'),
                    DB::raw('MAX(lastName) as lastName'),
                    DB::raw('MAX(monthlySalary) as monthlySalary'),
                    DB::raw('MAX(voteCode) as voteCode'),
                    DB::raw('MAX(deptName) as deptName'),
                    DB::raw('MAX(checkDate) as checkDate')
                )
                ->where('deductionCode', 667)
                ->whereBetween('checkDate', [$startDate, $endDate]);

            if ($department) {
                $query->where('deptName', $department);
            }

            $groupedDeductions = $query->groupBy('checkNumber', 'year', 'month')
                ->get()
                ->groupBy('checkNumber');

            $differences = $groupedDeductions->filter(function ($deductions) {
                return $deductions->pluck('deductionAmount')->unique()->count() > 1;
            })->map(function ($deductions) {
                $firstDeduction = $deductions->sortBy('checkDate')->first();
                $lastDeduction = $deductions->sortByDesc('checkDate')->first();
                $changeComment = '';

                if ($firstDeduction && $lastDeduction) {
                    if ($lastDeduction->deductionAmount > $firstDeduction->deductionAmount) {
                        $changeComment = 'Increase';
                    } elseif ($lastDeduction->deductionAmount < $firstDeduction->deductionAmount) {
                        $changeComment = 'Decrease';
                    }
                }

                return [
                    'firstName' => $firstDeduction->firstName ?? null,
                    'middleName' => $firstDeduction->middleName ?? null,
                    'lastName' => $firstDeduction->lastName ?? null,
                    'checkNumber' => $firstDeduction->checkNumber ?? null,
                    'monthlySalary' => $firstDeduction->monthlySalary ?? null,
                    'voteCode' => $firstDeduction->voteCode ?? null,
                    'deptName' => $firstDeduction->deptName ?? null,
                    'details' => $deductions->mapWithKeys(function ($item) {
                        return [sprintf('%s-%02d', $item->year, $item->month) => $item->deductionAmount];
                    })->toArray(),
                    'difference_count' => $deductions->pluck('deductionAmount')->unique()->count(),
                    'change_comment' => $changeComment,
                ];
            })->values()->toArray();
        }

        return view('deductions.contribution_differences', compact('departments', 'differences', 'request'));
    }

    public function export(Request $request)
    {
        if (!$request->filled('start_date') || !$request->filled('end_date')) {
            return redirect()->route('deduction667.differences.index')->with('error', 'Please select a date range to export.');
        }

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $department = $request->deptName;

        $query = DB::table('deduction_details')
            ->select(
                'checkNumber',
                DB::raw('YEAR(checkDate) as year'),
                DB::raw('MONTH(checkDate) as month'),
                DB::raw('MAX(deductionAmount) as deductionAmount'),
                DB::raw('MAX(firstName) as firstName'),
                DB::raw('MAX(middleName) as middleName'),
                DB::raw('MAX(lastName) as lastName'),
                DB::raw('MAX(monthlySalary) as monthlySalary'),
                DB::raw('MAX(voteCode) as voteCode'),
                DB::raw('MAX(deptName) as deptName'),
                DB::raw('MAX(checkDate) as checkDate')
            )
            ->where('deductionCode', 667)
            ->whereBetween('checkDate', [$startDate, $endDate]);

        if ($department) {
            $query->where('deptName', $department);
        }

        $groupedDeductions = $query->groupBy('checkNumber', 'year', 'month')
            ->get()
            ->groupBy('checkNumber');

        $differences = $groupedDeductions->filter(function ($deductions) {
            return $deductions->pluck('deductionAmount')->unique()->count() > 1;
        })->map(function ($deductions) {
            $firstDeduction = $deductions->sortBy('checkDate')->first();
            $lastDeduction = $deductions->sortByDesc('checkDate')->first();
            $changeComment = '';

            if ($firstDeduction && $lastDeduction) {
                if ($lastDeduction->deductionAmount > $firstDeduction->deductionAmount) {
                    $changeComment = 'Increase';
                } elseif ($lastDeduction->deductionAmount < $firstDeduction->deductionAmount) {
                    $changeComment = 'Decrease';
                }
            }

            return [
                'firstName' => $firstDeduction->firstName ?? null,
                'middleName' => $firstDeduction->middleName ?? null,
                'lastName' => $firstDeduction->lastName ?? null,
                'checkNumber' => $firstDeduction->checkNumber ?? null,
                'monthlySalary' => $firstDeduction->monthlySalary ?? null,
                'voteCode' => $firstDeduction->voteCode ?? null,
                'deptName' => $firstDeduction->deptName ?? null,
                'differences' => $deductions->mapWithKeys(function ($item) {
                    return [sprintf('%s-%02d', $item->year, $item->month) => $item->deductionAmount];
                })->toArray(),
                'change_comment' => $changeComment,
            ];
        })->values()->toArray();

        $filename = 'deduction_667_differences_' . now()->format('YmdHis') . '.csv';
        $filepath = storage_path('app/' . $filename);
        $file = fopen($filepath, 'w');

        // Add CSV headers
        $headers = ['First Name', 'Middle Name', 'Last Name', 'Check Number', 'Monthly Salary', 'Vote Code', 'Department', 'Change'];
        $monthYears = collect($differences)->flatMap(fn($diff) => array_keys($diff['differences']))->unique()->sort()->values()->toArray();
        $headers = array_merge($headers, $monthYears);
        fputcsv($file, $headers);

        foreach ($differences as $difference) {
            $row = [
                $difference['firstName'],
                $difference['middleName'],
                $difference['lastName'],
                $difference['checkNumber'],
                $difference['monthlySalary'],
                $difference['voteCode'],
                $difference['deptName'],
                $difference['change_comment'],
            ];
            foreach ($monthYears as $my) {
                $row[] = $difference['differences'][$my] ?? '';
            }
            fputcsv($file, $row);
        }

        fclose($file);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }
}