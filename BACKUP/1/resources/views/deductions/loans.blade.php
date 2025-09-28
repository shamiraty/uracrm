
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Search Loans</h1>
    <form action="{{ route('deductions.loans.show') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="date">Enter Date:</label>
            <input type="date" class="form-control" id="date" name="date" required value="{{ $date ?? '' }}">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    @if(isset($loans))
        @include('deductions.table', ['details' => $loans])
    @endif
</div>
@endsection