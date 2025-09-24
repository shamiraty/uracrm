@extends('layouts.app')

@section('content')
    <h1>{{ isset($fileSeries) ? 'Edit File Series' : 'Create File Series' }}</h1>
    <form action="{{ isset($fileSeries) ? route('file_series.update', $fileSeries) : route('file_series.store') }}" method="POST">
        @csrf
        @if(isset($fileSeries))
            @method('PUT')
        @endif
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $fileSeries->name ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Code</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $fileSeries->code ?? '') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
