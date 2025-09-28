@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
    <h1>Create New Post</h1>
    <form method="post" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="image" class="form-label">Upload Image:</label>
            <input type="file" class="form-control" name="image" required>
        </div>

        <div class="mb-3">
            <label for="caption" class="form-label">Caption:</label>
            <input type="text" class="form-control" name="caption" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea class="form-control" name="description" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Add Post</button>
    </form>
@endsection
