@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Initiate Payment for Enquiry #{{ $enquiry->id }}</h2>
    @if(session('message'))
        <div class="alert alert-info">{{ session('message') }}</div>
    @endif
    <form action="{{ route('payments.store', $enquiry->id) }}" method="post">
        @csrf
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" class="form-control" id="amount" name="amount" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit Payment</button>
    </form>
</div>
@endsection
