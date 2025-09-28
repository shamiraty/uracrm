@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <h1>Edit Post</h1>
    <form method="post" action="{{ route('posts.update', $post->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="image_path" class="form-label">Image Path:</label>
            <input type="text" class="form-control" name="image_path" value="{{ $post->image_path }}" required>
        </div>

        <div class="mb-3">
            <label for="caption" class="form-label">Caption:</label>
            <input type="text" class="form-control" name="caption" value="{{ $post->caption }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea class="form-control" name="description" required>{{ $post->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Post</button>
    </form>
@endsection
