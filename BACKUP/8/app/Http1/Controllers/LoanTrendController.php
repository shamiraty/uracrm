<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanApplication;

class LoanTrendController extends Controller
{
    // Method to show status metrics
    public function index(Request $request)
    {
        // Capture start and end dates from the form or default to today
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // Fetch detailed status metrics
        $statusMetrics = LoanApplication::getDetailedStatusMetrics($startDate, $endDate);

        return view('loan_trends', compact('statusMetrics', 'startDate', 'endDate'));
    }
}
