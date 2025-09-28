@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Deduction Details Search</h1>
    <form action="{{ route('deduction.details') }}" method="GET">
        <div class="form-group">
            <label for="date">Enter Date:</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Fetch Details</button>
    </form>

    @if(isset($details) && !empty($details))
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Check Date</th>
                    <th>Name</th>
                    <th>Deduction Type</th>
                    <th>Deduction Description</th>
                    <th>Deduction Amount</th>
                    <th>Department</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $detail)
                <tr @if(str_contains($detail['deductionDesc'], 'LOAN')) class="table-danger" @endif>
                    <td>{{ $detail['checkDate'] ?? 'N/A' }}</td>
                    <td>{{ $detail['firstName'] ?? 'N/A' }} {{ $detail['middleName'] ?? '' }} {{ $detail['lastName'] ?? '' }}</td>
                    <td>{{ $detail['deductionCode'] ?? 'Unknown' }}</td>
                    <td>{{ $detail['deductionDesc'] ?? 'No description' }}</td>
                    <td>{{ number_format($detail['deductionAmount'] ?? 0, 2) }}</td>
                    <td>{{ $detail['deptName'] ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No details available for the specified date.</p>
    @endif
</div>
@endsection


