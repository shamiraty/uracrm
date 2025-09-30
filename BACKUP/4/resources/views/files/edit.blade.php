@extends('layouts.app')

@section('content')
    <h1>{{ isset($file) ? 'Edit File' : 'Create File' }}</h1>
    <form action="{{ isset($file) ? route('files.update', $file) : route('files.store') }}" method="POST">
        @csrf
        @if(isset($file))
            @method('PUT')
        @endif

        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <label for="file_series_id" class="form-label">File Series</label>
                <select class="form-control" id="file_series_id" name="file_series_id">
                    @foreach($fileSeries as $series)
                        <option value="{{ $series->id }}"{{ (isset($file) && $file->file_series_id == $series->id) ? ' selected' : '' }}>{{ $series->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-lg-6">
                <label for="keyword1_id" class="form-label">Keywords 1</label>
                <select class="form-control" id="keyword1_id" name="keyword1_id">
                    <option value="">Select Keyword</option>
                    @foreach($keywords as $keyword)
                        <option value="{{ $keyword->id }}"{{ (isset($file) && $file->keyword1_id == $keyword->id) ? ' selected' : '' }}>{{ $keyword->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-lg-6">
                <label for="keyword2_id" class="form-label">Keywords 2</label>
                <select class="form-control" id="keyword2_id" name="keyword2_id">
                    <option value="">Select Keyword</option>
                    @foreach($keywords as $keyword)
                        <option value="{{ $keyword->id }}"{{ (isset($file) && $file->keyword2_id == the keyword->id) ? ' selected' : '' }}>{{ $keyword->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-lg-6">
                <label for="running_number" class="form-label">Running Number</label>
                <input type="number" class="form-control" id="running_number" name="running_number" value="{{ old('running_number', $file->running_number ?? '') }}" required>
            </div>

            <div class="col-12 col-lg-6">
                <label for="file_part" class="form-label">File Part</label>
                <input type="number" class="form-control" id="file_part" name="file_part" value="{{ old('file_part', $file->file_part ?? 1) }}" required>
            </div>

            <div class="col-12 col-lg-6">
                <label for="file_subject" class="form-label">File Subject</label>
                <input type="text" class="form-control" id="file_subject" name="file_subject" value="{{ $file->file_subject ?? '' }}" required>
            </div>

            <div class="col-12 col-lg-6">
                <label for="reference_number" class="form-label">Reference Number</label>
                <input type="text" class="form-control" id="reference_number" name="reference_number" value="{{ $file->reference_number ?? '' }}" required>
            </div>

            <div class="col-12 col-lg-6">
                <label for="department_id" class="form-label">Department</label>
                <select class="form-control" id="department_id" name="department_id">
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}"{{ (isset($file) && $file->department_id == $department->id) ? ' selected' : '' }}>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ isset($file) ? 'Update' : 'Create' }}</button>
            </div>
        </div>
    </form>
@endsection
