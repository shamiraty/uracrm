@extends('layouts.app')

@section('content')
<div class="container">
    <h2>User Profile</h2>

    <!-- Display User Profile Information -->
    <div id="profile-info">
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Phone Number:</strong> {{ $user->phone_number }}</p>

        <h3>Branch Information</h3>
        <p><strong>Branch:</strong> {{ $user->branch ? $user->branch->name : 'None' }}</p>
        <p><strong>District:</strong> {{ $user->branch && $user->branch->district ? $user->branch->district->name : 'None' }}</p>
        <p><strong>Region:</strong> {{ $user->branch && $user->branch->region ? $user->branch->region->name : 'None' }}</p>

        <h3>Role Information</h3>
        <p><strong>Role:</strong> {{ $user->role ? $user->role->name : 'None' }}</p>

        <h3>Departments</h3>
        @if($user->branch && $user->branch->departments)
            @if($user->branch->departments->isEmpty())
                <p>None</p>
            @else
                <ul>
                    @foreach($user->branch->departments as $department)
                        <li>{{ $department->name }}</li>
                    @endforeach
                </ul>
            @endif
        @else
            <p>None</p>
        @endif

        <h3>Enquiries</h3>
        @if($user->enquiries->isEmpty())
            <p>None</p>
        @else
            <ul>
                @foreach($user->enquiries as $enquiry)
                    <li>{{ $enquiry->title }}</li>
                @endforeach
            </ul>
        @endif

        <button id="edit-profile-btn" class="btn btn-primary">Edit Profile</button>
    </div>

    <!-- Edit Profile Form -->
    <div id="edit-profile-form" style="display: none;">
        <h2>Edit Profile</h2>
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" class="form-control" name="phone_number" value="{{ $user->phone_number }}" required>
            </div>

            <button type="submit" class="btn btn-success">Update Profile</button>
        </form>

        <hr>

        <h3>Change Password</h3>
        <form action="{{ route('profile.change-password') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" class="form-control" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" name="new_password" required>
            </div>

            <div class="form-group">
                <label for="new_password_confirmation">Confirm New Password</label>
                <input type="password" class="form-control" name="new_password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-danger">Change Password</button>
        </form>
    </div>
</div>

<script>
    // Toggle between displaying profile information and edit form
    document.getElementById('edit-profile-btn').onclick = function() {
        var profileInfo = document.getElementById('profile-info');
        var editForm = document.getElementById('edit-profile-form');

        if (profileInfo.style.display === "none") {
            profileInfo.style.display = "block";
            editForm.style.display = "none";
            this.textContent = "Edit Profile"; // Change button text back
        } else {
            profileInfo.style.display = "none";
            editForm.style.display = "block";
            this.textContent = "View Profile"; // Change button text to view
        }
    };
</script>
@endsection
