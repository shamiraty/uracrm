@extends('layouts.app')

@section('content')
    <h1>{{ isset($keyword) ? 'Edit Keyword' : 'Create Keyword' }}</h1>
    <form action="{{ isset($keyword) ? route('keywords.update', $keyword) : route('keywords.store') }}" method="POST">
        @csrf
        @if(isset($keyword))
            @method('PUT')
        @endif
        <div class="mb-3">
            <label for="name" class="form-label">Keyword Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $keyword->name ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Code</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $fileSeries->code ?? '') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">{{ isset($keyword) ? 'Update' : 'Create' }}</button>
    </form>
@endsection
