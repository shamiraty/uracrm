<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\District;
use Illuminate\Http\Request;

class RegionDistrictController extends Controller
{
    public function getRegions()
    {
        $regions = Region::all();
        return response()->json($regions);
    }

    public function getDistricts($regionId)
    {
        $districts = District::where('region_id', $regionId)->get();
        return response()->json($districts);
    }

}
