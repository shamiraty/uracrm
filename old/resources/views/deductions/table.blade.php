@if(count($details) > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Check Date</th>
                <th>Name</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Department</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($details as $detail)
            <tr>
                <td>{{ $detail['checkDate'] ?? 'N/A' }}</td>
                <td>{{ $detail['firstName'] ?? 'N/A' }} {{ $detail['middleName'] ?? '' }} {{ $detail['lastName'] ?? '' }}</td>
                <td>{{ $detail['deductionDesc'] ?? 'No description' }}</td>
                <td>{{ number_format($detail['deductionAmount'] ?? 0, 2) }}</td>
                <td>{{ $detail['deptName'] ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No records available.</p>
@endif
