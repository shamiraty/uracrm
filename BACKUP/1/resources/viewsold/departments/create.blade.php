{{-- resources/views/departments/create.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Form inside a card -->
    <div class="card">
    <h5 class="mb-4 card-header">Create New Department</h5>
        <div class="card-body p-4">           
            <form method="POST" action="{{ route('departments.store') }}">
                @csrf
                <!-- Department Name Input -->
                <div class="row mb-3">
                    <label for="name" class="col-sm-3 col-form-label">Department Name</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text"><i class='bx bx-building'></i></span>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter department name" required>
                        </div>
                    </div>
                </div>

                <!-- Department Description Input -->
                <div class="row mb-3">
                    <label for="description" class="col-sm-3 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text"><i class='bx bx-comment-detail'></i></span>
                            <textarea class="form-control" id="description" name="description" placeholder="Enter department description"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Check me out checkbox -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label"></label>
                    <div class="col-sm-9">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirm" required>
                            <label class="form-check-label" for="confirm">Confirm creation</label>
                        </div>
                    </div>
                </div>

                <!-- Submit and Reset Buttons -->
                <div class="row">
                    <label class="col-sm-3 col-form-label"></label>
                    <div class="col-sm-9">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4"><i class='bx bx-save'></i> Submit</button>
                            <button type="reset" class="btn btn-light px-4"><i class='bx bx-reset'></i> Reset</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
