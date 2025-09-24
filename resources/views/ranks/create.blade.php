@extends('layouts.app')
@section('content')

<div class="container-fluid">
    <div class="page-breadcrumb d-flex align-items-center mb-4">
        <div class="breadcrumb-title pe-3">Ranks</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('enquiries.index') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Ranks Management</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="ms-auto">
        <button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#addRankModal">
            <i class="fas fa-plus-circle"></i> Add Rank
        </button>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm basic-data-table">
                <div class="card-header">
                    <h6 class="mb-0 text-primary">Ranks List</h6>
                </div>
                <div class="card-body p-24 d-flex flex-column gap-4">
                    <div class="table-responsive">
                        <table class="table border-primary-table mb-0 w-100" id="dataTable">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Rank Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ranks as $rank)
                        <tr id="rank-{{ $rank->id }}">
                            <td>{{ $rank->id }}</td>
                            <td>{{ $rank->name }}</td>
                            <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editRankModal" onclick="editRank({{ $rank->id }})">Edit</button>
<button class="btn btn-danger btn-sm" onclick="deleteRank({{ $rank->id }})">Delete</button>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for adding new rank -->
<div class="modal fade" id="addRankModal" tabindex="-1" aria-labelledby="addRankModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addRankForm" action="{{ route('ranks.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-primary" id="addRankModalLabel">Add Rank</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rankName" class="form-label">Rank Name</label>
                        <input type="text" class="form-control" id="rankName" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Rank</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal for editing existing rank -->
<div class="modal fade" id="editRankModal" tabindex="-1" aria-labelledby="editRankModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editRankForm" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-primary" id="editRankModalLabel">Edit Rank</h6>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editRankName" class="form-label">Rank Name</label>
                        <input type="text" class="form-control" id="editRankName" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Function to populate the edit modal with the rank data
    function editRank(id) {
        fetch(`/ranks/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('editRankName').value = data.name;
                document.getElementById('editRankForm').action = `/ranks/${id}`;
            });
    }

    // Function to delete a rank
    function deleteRank(id) {
        Swal.fire({
            //title: 'Are you sure?',
            text: 'This will permanently delete the rank.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/ranks/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`rank-${id}`).remove();
                        Swal.fire('', data.message, 'success');
                    }
                });
            }
        });
    }
</script>

@if(session('success'))
    <script>
        Swal.fire({
            //title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'Okay'
        });
    </script>
@endif

@endsection
