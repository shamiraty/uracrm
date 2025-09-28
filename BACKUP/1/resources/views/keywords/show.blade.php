@extends('layouts.app')

@section('content')
    <h1>Keyword: {{ $keyword->name }}</h1>
    <div>
        <a href="{{ route('keywords.edit', $keyword) }}" class="btn btn-secondary">Edit</a>
        <form action="{{ route('keywords.destroy', $keyword) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
@endsection
