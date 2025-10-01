@extends('layouts.app')

@section('title', 'Enquiry Details')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Enquiry Details</h4>
                <p>Date Received: {{ $enquiry->date_received }}</p>
                <p>Force Number: {{ $enquiry->force_no }}</p>
                <p>Account Number: {{ $enquiry->account_number }}</p>
                <p>Bank Name: {{ $enquiry->bank_name }}</p>
                <p>Full Name: {{ $enquiry->full_name }}</p>
                <p>Type: {{ ucfirst(str_replace('_', ' ', $enquiry->type)) }}</p>

                @foreach($enquiry->responses as $response)
                    <h5>Response from {{ $response->user->name }}</h5>
                    <p>Amount: {{ $response->amount }}</p>
                    <p>Interest: {{ $response->interest }}</p>
                    <p>Remarks: {{ $response->remarks }}</p>
                @endforeach

                @if(auth()->user()->canRespondTo($enquiry))
                    <a href="{{ route('responses.create', $enquiry->id) }}" class="btn btn-primary">Respond</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
