<?php
// This Controller fetches and analyses Registered Enquiries 
// By default, shows Current date Enquiries 
// Updated Sunday morning 13/10/24
// Models are: Payment, Region, District, Enquiries 

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\District;
use App\Models\Enquiry;
use App\Models\Payment;  // Add Payment model
use App\Models\LoanApplication;  // Add LoanApplication model
use Illuminate\Http\Request;
use Carbon\Carbon; // Add Carbon for date manipulation

class DashboardTrendsController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all regions with their districts
        $regions = Region::with('districts')->get();
    
        // Prepare data for crosstab
        $districtMetrics = []; // Array to hold metrics for each district
        $regionMetrics = []; // Array to hold aggregated metrics for each region
    
        // Set the default start and end dates to today
        $startDate = $request->get('start_date', Carbon::now()->startOfDay());
        $endDate = $request->get('end_date', Carbon::now()->endOfDay());
    
        // Convert start and end dates to Carbon instances
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();
    
        // Fetch distinct statuses and types from the database
        $statuses = Enquiry::select('status')->distinct()->pluck('status')->toArray();
        $types = Enquiry::select('type')->distinct()->pluck('type')->toArray();
    
        foreach ($regions as $region) {
            foreach ($region->districts as $district) {
                // Fetch enquiries for the current district within the date range
                $enquiries = Enquiry::where('district', $district->id)
                    ->where('region', $region->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
    
                // Initialize metrics for district
                $statusMetrics = [];
                $typeMetrics = [];
    
                // Aggregate metrics for the current district
                foreach ($enquiries as $enquiry) {
                    // Aggregate status metrics
                    $status = $enquiry->status;
                    if (!isset($statusMetrics[$status])) {
                        $statusMetrics[$status] = ['count' => 0, 'sum' => 0];
                    }
                    $statusMetrics[$status]['count']++;
                    $statusMetrics[$status]['sum'] += $enquiry->loan_amount;
    
                    // Aggregate type metrics
                    $type = $enquiry->type; // Ensure you have a relationship to fetch type name
                    if (!isset($typeMetrics[$type])) {
                        $typeMetrics[$type] = ['count' => 0, 'sum' => 0];
                    }
                    $typeMetrics[$type]['count']++;
                    $typeMetrics[$type]['sum'] += $enquiry->loan_amount;
                }
    
                // Store metrics for this district
                $districtMetrics[$region->id][$district->id] = [
                    'statusMetrics' => $statusMetrics,
                    'typeMetrics' => $typeMetrics,
                ];
    
                // Aggregate metrics for the region
                foreach ($statusMetrics as $status => $metrics) {
                    if (!isset($regionMetrics[$region->id]['statusMetrics'][$status])) {
                        $regionMetrics[$region->id]['statusMetrics'][$status] = ['count' => 0, 'sum' => 0];
                    }
                    $regionMetrics[$region->id]['statusMetrics'][$status]['count'] += $metrics['count'];
                    $regionMetrics[$region->id]['statusMetrics'][$status]['sum'] += $metrics['sum'];
                }
    
                foreach ($typeMetrics as $type => $metrics) {
                    if (!isset($regionMetrics[$region->id]['typeMetrics'][$type])) {
                        $regionMetrics[$region->id]['typeMetrics'][$type] = ['count' => 0, 'sum' => 0];
                    }
                    $regionMetrics[$region->id]['typeMetrics'][$type]['count'] += $metrics['count'];
                    $regionMetrics[$region->id]['typeMetrics'][$type]['sum'] += $metrics['sum'];
                }
            }
        }
    
        // Calculate grand totals for all regions
        $grandTotalMetrics = [
            'status' => [],
            'type' => []
        ];
    
        foreach ($regionMetrics as $metrics) {
            foreach ($metrics['statusMetrics'] as $status => $data) {
                if (!isset($grandTotalMetrics['status'][$status])) {
                    $grandTotalMetrics['status'][$status] = ['count' => 0, 'sum' => 0];
                }
                $grandTotalMetrics['status'][$status]['count'] += $data['count'];
                $grandTotalMetrics['status'][$status]['sum'] += $data['sum'];
            }
            foreach ($metrics['typeMetrics'] as $type => $data) {
                if (!isset($grandTotalMetrics['type'][$type])) {
                    $grandTotalMetrics['type'][$type] = ['count' => 0, 'sum' => 0];
                }
                $grandTotalMetrics['type'][$type]['count'] += $data['count'];
                $grandTotalMetrics['type'][$type]['sum'] += $data['sum'];
            }
        }
    
        // Pass both district and region metrics to the view, along with start and end dates
        return view('trends', compact('regions', 'districtMetrics', 'regionMetrics', 'grandTotalMetrics', 'statuses', 'types', 'startDate', 'endDate'));
    }
}