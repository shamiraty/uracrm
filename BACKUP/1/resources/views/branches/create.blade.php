{{-- resources/views/branches/create.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Form inside a card -->
    <div class="card">
        <h5 class="mb-4 card-header">Create New Branch</h5>
        <div class="card-body p-4">           
            <form method="POST" action="{{ route('branches.store') }}">
                @csrf
                
                <!-- Branch Name Input -->
                <div class="row mb-3">
                    <label for="name" class="col-sm-3 col-form-label">Branch Name</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text"><i class='bx bx-home'></i></span>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter branch name" required>
                        </div>
                    </div>
                </div>

                <!-- District Selection -->
                <div class="row mb-3">
                    <label for="district_id" class="col-sm-3 col-form-label">District</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text"><i class='bx bx-map'></i></span>
                            <select name="district_id" class="form-select" id="district_id" required>
                                @foreach(App\Models\District::all() as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Region Selection -->
                <div class="row mb-3">
                    <label for="region_id" class="col-sm-3 col-form-label">Region</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-text"><i class='bx bx-globe'></i></span>
                            <select name="region_id" class="form-select" id="region_id" required>
                                @foreach(App\Models\Region::all() as $region)
                                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Confirm creation checkbox -->
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

@section('scripts')

<script>
    $(document).ready(function() {
        // Initialize Select2 for district and region dropdowns
        $('#district_id').select2({
            placeholder: 'Select a district',
            allowClear: true
        });

        $('#region_id').select2({
            placeholder: 'Select a region',
            allowClear: true
        });
    });
</script>
@endsection

@endsection
