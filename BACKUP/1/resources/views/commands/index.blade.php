@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Alert for success or error -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ implode(', ', $errors->all()) }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Button to trigger Create Modal -->
   

    <!-- Commands Table -->
    <div class="card basic-data-table">
                    <div class="card-header">
                    <button class="btn btn-primary mb-3 btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">Add Command</button>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                    <table class="table border-primary-table mb-0" id="dataTable" data-page-length='10'>
        <thead>
            <tr>
                <th>Name</th>
                <th>Region</th>
                <th>Branch</th>
                <th>District</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($commands as $command)
            <tr>
                <td>{{ucwords($command->name) }}</td>
                <td>{{ ucwords($command->region->name) }}</td>
                <td>{{ ucwords($command->branch->name) }}</td>
                <td>{{ ucwords($command->district->name) }}</td>
                <td>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal" 
                            data-id="{{ $command->id }}" 
                            data-name="{{ $command->name }}"
                            data-region-id="{{ $command->region_id }}"
                            data-branch-id="{{ $command->branch_id }}"
                            data-district-id="{{ $command->district_id }}">
                        Edit
                    </button>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $command->id }}">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('commands.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title text-secondary">Add Command</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="region_id" class="form-label">Region</label>
                        <select name="region_id" id="region_id" class="form-select" required>
                            @foreach ($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="branch_id" class="form-label">Branch</label>
                        <select name="branch_id" id="branch_id" class="form-select" required>
                            @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="district_id" class="form-label">District</label>
                        <select name="district_id" id="district_id" class="form-select" required>
                            @foreach ($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="updateForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h6 class="modal-title text-secondary">Edit Command</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_region_id" class="form-label">Region</label>
                        <select name="region_id" id="edit_region_id" class="form-select" required>
                            @foreach ($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_branch_id" class="form-label">Branch</label>
                        <select name="branch_id" id="edit_branch_id" class="form-select" required>
                            @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_district_id" class="form-label">District</label>
                        <select name="district_id" id="edit_district_id" class="form-select" required>
                            @foreach ($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const updateModal = document.getElementById('updateModal');
    updateModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const regionId = button.getAttribute('data-region-id');
        const branchId = button.getAttribute('data-branch-id');
        const districtId = button.getAttribute('data-district-id');

        document.getElementById('updateForm').setAttribute('action', `/commands/${id}`);
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_region_id').value = regionId;
        document.getElementById('edit_branch_id').value = branchId;
        document.getElementById('edit_district_id').value = districtId;
    });

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            Swal.fire({
                //title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/commands/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        });
    });
    
    
</script>
</div>
</div>
</div>

@endsection
