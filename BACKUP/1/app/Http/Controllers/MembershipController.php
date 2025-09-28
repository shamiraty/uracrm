<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        $members = Member::all();
        return view('membership.index', compact('members'));
    }

    public function store(Request $request)
    {
        $member = Member::updateOrCreate(
            ['check_number' => $request->check_number],
            $request->only('first_name', 'middle_name', 'last_name', 'member_number')
        );

        return response()->json($member);
    }

    public function edit($check_number)
    {
        $member = Member::findOrFail($check_number);
        return response()->json($member);
    }

    public function destroy($check_number)
    {
        Member::findOrFail($check_number)->delete();
        return response()->json(['success' => true]);
    }
}
