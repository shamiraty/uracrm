<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DeductionVarianceController extends Controller
{
    public function index(Request $request)
    {
        // Current month
        $currentMonth = Carbon::now()->startOfMonth();

        // End date of the required period (two months ago)
        $endDate = (clone $currentMonth)->subMonths(1)->endOfMonth();

        // Start date of the required period (three months ago)
        $startDate = (clone $endDate)->subMonths(1)->startOfMonth();

        $departmentFilter = $request->input('deptName', 'All'); // Default to 'All' if no department is selected

        if ($request->has('check_date') && !empty($request->check_date)) {
            $dates = explode(' to ', $request->check_date);
            if (count($dates) == 2) {
                $startDate = Carbon::parse(trim($dates[0]))->startOfMonth();
                $endDate = Carbon::parse(trim($dates[1]))->endOfMonth();
            }
        }

        // Fetch records with department and deduction code filter if provided
        $query = DB::table('deduction_details')
            ->whereBetween('checkDate', [$startDate, $endDate])
            ->whereIn('deductionCode', ['769', '769A']); // Apply the deduction code filter

        if ($departmentFilter !== 'All') {
            $query->where('deptName', $departmentFilter);
        }

        $records = $query->select('checkNumber', 'deductionCode', 'firstName', 'middleName', 'lastName',
            'voteCode', 'voteName', 'deptName', 'monthlySalary',
            'deductionAmount', 'balanceAmount', 'deductionDesc', 'checkDate')
            ->addSelect(DB::raw('(balanceAmount / deductionAmount) AS month_computed')) // Compute 'month' as virtual column
            ->orderBy('checkNumber')
            ->orderBy('deductionCode')
            ->orderBy('checkDate')
            ->get()
            ->each(function ($record) {
                $record->month = Carbon::parse($record->checkDate)->format('F Y'); // Modify 'month' to formatted date
            });

        // Group data by checkNumber and deductionCode
        $grouped = $records->groupBy(function ($item) {
            return $item->checkNumber . '-' . $item->deductionCode;
        });

        // Process differences
        $filteredData = [];
        foreach ($grouped as $key => $data) {
            if ($data->count() >= 2) { // Ensure we have at least two months of data
                $firstMonth = $data->first();
                $lastMonth = $data->last();

                $difference = abs($lastMonth->deductionAmount - $firstMonth->deductionAmount);

                if ($difference > 0) {
                    $filteredData[] = [
                        'check_number' => $firstMonth->checkNumber,
                        'deduction_code' => $firstMonth->deductionCode,
                        'name' => "{$firstMonth->firstName} {$firstMonth->middleName} {$firstMonth->lastName}",
                        'vote' => "{$firstMonth->voteCode} - {$firstMonth->voteName}",
                        'department' => $firstMonth->deptName,
                        'monthly_salary' => number_format($firstMonth->monthlySalary, 2),
                        'deduction_month_1' => number_format($firstMonth->deductionAmount, 2),
                        'deduction_month_2' => number_format($lastMonth->deductionAmount, 2),
                        'difference' => number_format($difference, 2),
                        'balance' => number_format($lastMonth->balanceAmount, 2),
                        'deduction_description' => $firstMonth->deductionDesc,
                        'check_date_1' => $firstMonth->checkDate,
                        'check_date_2' => $lastMonth->checkDate,
                        'month_1' => Carbon::parse($firstMonth->checkDate)->format('F Y'), // Use formatted date
                        'month_2' => Carbon::parse($lastMonth->checkDate)->format('F Y'), // Use formatted date
                        'month_computed' => number_format($lastMonth->month_computed, 2), // Include the computed month
                    ];
                }
            }
        }

        session(['filteredData' => $filteredData]); // Store data for export

        $departments = DB::table('deduction_details')
            ->whereIn('deductionCode', ['769', '769A']) // Apply deduction code filter here as well for distinct departments
            ->distinct()
            ->pluck('deptName'); // Get distinct department names for dropdown

        // Pass the count of filtered records to the view
        $filteredCount = count($filteredData);

        return view('deductions.deduction_differences', compact('filteredData', 'startDate', 'endDate', 'departments', 'departmentFilter', 'filteredCount'));
    }

    public function exportCsv()
    {
        $filteredData = session('filteredData', []);

        $response = new StreamedResponse(function () use ($filteredData) {
            $handle = fopen('php://output', 'w');

            // Adjusted CSV headers to include month names and computed month
            if (!empty($filteredData)) {
                fputcsv($handle, [
                    'Check Number', 'Deduction Code', 'Name', 'Vote', 'Department', 'Monthly Salary',
                    'Deduction (' . $filteredData[0]['month_1'] . ')',
                    'Deduction (' . $filteredData[0]['month_2'] . ')',
                    'Difference', 'Balance', 'Computed Month (Balance/Deduction)',
                    'Deduction Description', 'Check Date 1', 'Check Date 2'
                ]);

                foreach ($filteredData as $data) {
                    fputcsv($handle, [
                        $data['check_number'],
                        $data['deduction_code'],
                        $data['name'],
                        $data['vote'],
                        $data['department'],
                        $data['monthly_salary'],
                        $data['deduction_month_1'],
                        $data['deduction_month_2'],
                        $data['difference'],
                        $data['balance'],
                        $data['month_computed'],
                        $data['deduction_description'],
                        $data['check_date_1'],
                        $data['check_date_2']
                    ]);
                }
            } else {
                fputcsv($handle, ['No Data Available']);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="deduction_variance.csv"');

        return $response;
    }
}