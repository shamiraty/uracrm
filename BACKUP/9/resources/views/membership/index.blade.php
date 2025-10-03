<!-- resources/views/membership/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h6>Members</h6>
    <button class="btn btn-primary mb-3" id="createMemberBtn">Add Member</button>

    <div class="card shadow-sm basic-data-table">
        <div class="card-body">
            <div class="table-responsive">
    <table class="table border-primary-table mb-0" id="dataTable">
        <thead>
            <tr>
                <th>Check Number</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Last Name</th>
                <th>Member Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $member)
                <tr data-id="{{ $member->check_number }}">
                    <td>{{ $member->check_number }}</td>
                    <td>{{ $member->first_name }}</td>
                    <td>{{ $member->middle_name }}</td>
                    <td>{{ $member->last_name }}</td>
                    <td>{{ $member->member_number }}</td>
                    <td>
                        <button class="btn btn-info editBtn">Edit</button>
                        <button class="btn btn-danger deleteBtn">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Create/Update Member Modal -->
    <div class="modal fade" id="memberModal" tabindex="-1" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="memberModalLabel">Add Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="memberForm">
                        <div class="mb-3">
                            <label for="check_number" class="form-label">Check Number</label>
                            <input type="text" class="form-control" id="check_number" name="check_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middle_name" name="middle_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="member_number" class="form-label">Member Number</label>
                            <input type="text" class="form-control" id="member_number" name="member_number" required>
                        </div>
                        <input type="hidden" id="member_id" name="member_id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveMemberBtn">Save Member</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this member?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="deleteMemberBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var selectedMemberId = null;

        // Open modal for creating a new member
        $('#createMemberBtn').on('click', function () {
            $('#memberForm')[0].reset();
            $('#memberModalLabel').text('Add Member');
            $('#saveMemberBtn').text('Save Member');
            $('#member_id').val('');
            $('#memberModal').modal('show');
        });

        // Open modal for editing an existing member
        $('.editBtn').on('click', function () {
            selectedMemberId = $(this).closest('tr').data('id');
            $.ajax({
                url: '/members/' + selectedMemberId + '/edit',
                type: 'GET',
                success: function (data) {
                    $('#memberModalLabel').text('Edit Member');
                    $('#saveMemberBtn').text('Update Member');
                    $('#check_number').val(data.check_number);
                    $('#first_name').val(data.first_name);
                    $('#middle_name').val(data.middle_name);
                    $('#last_name').val(data.last_name);
                    $('#member_number').val(data.member_number);
                    $('#member_id').val(data.check_number);
                    $('#memberModal').modal('show');
                }
            });
        });

        // Open delete confirmation modal
        $('.deleteBtn').on('click', function () {
            selectedMemberId = $(this).closest('tr').data('id');
            $('#deleteModal').modal('show');
        });

        // Delete member
        $('#deleteMemberBtn').on('click', function () {
            $.ajax({
                url: '/members/' + selectedMemberId,
                type: 'DELETE',
                success: function () {
                    $('#deleteModal').modal('hide');
                    $('tr[data-id="' + selectedMemberId + '"]').remove();
                }
            });
        });

        // Save or update member
        $('#saveMemberBtn').on('click', function () {
            var formData = $('#memberForm').serialize();
            var url = $('#member_id').val() ? '/members/' + $('#member_id').val() : '/members';
            var method = $('#member_id').val() ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: formData,
                success: function (data) {
                    $('#memberModal').modal('hide');
                    var newRow = `
                        <tr data-id="${data.check_number}">
                            <td>${data.check_number}</td>
                            <td>${data.first_name}</td>
                            <td>${data.middle_name}</td>
                            <td>${data.last_name}</td>
                            <td>${data.member_number}</td>
                            <td>
                                <button class="btn btn-info editBtn">Edit</button>
                                <button class="btn btn-danger deleteBtn">Delete</button>
                            </td>
                        </tr>`;
                    if ($('#member_id').val()) {
                        $('tr[data-id="' + data.check_number + '"]').replaceWith(newRow);
                    } else {
                        $('#membersTable tbody').append(newRow);
                    }
                }
            });
        });
    });
</script>
@endsection
