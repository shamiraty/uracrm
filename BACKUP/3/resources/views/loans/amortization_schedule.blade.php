{{-- resources/views/loans/amortization_schedule.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Amortization Schedule for {{ $member->fullName }}</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Period</th>
                <th>EMI (TSh)</th>
                <th>Interest (TSh)</th>
                <th>Principal (TSh)</th>
                <th>Balance (TSh)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($amortizationSchedule as $item)
                <tr>
                    <td>{{ $item['Period'] }}</td>
                    <td>{{ number_format($item['EMI'], 2) }}</td>
                    <td>{{ number_format($item['Interest'], 2) }}</td>
                    <td>{{ number_format($item['Principal'], 2) }}</td>
                    <td>{{ number_format($item['Balance'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
