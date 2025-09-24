{{-- @extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1>Files</h1>
            <a href="{{ route('files.create') }}" class="btn btn-primary">Create New File</a>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($files as $file)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="dropdown ms-auto">
                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                            <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- View Action -->
                            <li><a class="dropdown-item" href="#"><i class="mdi mdi-eye me-2"></i>View Detail</a></li>
                            <li><hr class="dropdown-divider"></li>








<!-- Edit Action -->
<li><a class="dropdown-item"   href="{{ route('files.edit', $file) }}" ><i class="mdi mdi-pencil me-2"></i>Edit</a></li>
<li><hr class="dropdown-divider"></li>


                            <!-- Delete Action -->
                            <li>
                                <form action="{{ route('files.destroy', $file) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="card h-100 text-dark">
                        <!-- Image covering the whole card background -->
                        <img src="{{ asset('assets/images/folder.png') }}" class="card-img" alt="Folder Image" style="opacity: 0.3;">
                        <div class="card-img-overlay d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <h5 class="card-title">{{ $file->reference_number }}</h5>
                                <p class="card-text">{{ $file->file_subject }}</p>
                                <div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
@endsection

 --}}
 {{-- @extends('layouts.app')

 @section('content')
 <div class="container mt-4">
     <div class="card">
         <div class="card-header d-flex justify-content-between align-items-center">
             <h1>Files</h1>
             <a href="{{ route('files.create') }}" class="btn btn-primary">Create New File</a>
         </div>
         <div class="card-body">
             <div class="row">
                 @foreach ($files as $file)
                 <div class="col-md-4 col-sm-6 mb-4">
                     <div class="card h-100 text-dark">
                         <img src="{{ asset('assets/images/folder.png') }}" class="card-img" alt="Folder Image" style="opacity: 0.5;">
                         <div class="card-img-overlay d-flex align-items-center justify-content-center p-0">
                             <div class="text-center p-2" style="background: rgba(0, 0, 0, 0.5); width: 100%; height: 100%;">
                                 <h5 class="card-title text-white">{{ $file->reference_number }}</h5>
                                 <p class="card-text text-white">{{ $file->file_subject }}</p>
                                 <div class="dropdown position-absolute top-0 end-0 p-2">
                                     <a class="btn btn-light btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                         Actions
                                     </a>
                                     <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                         <li><a class="dropdown-item" href="#"><i class="bx bx-show me-2"></i>View Detail</a></li>
                                         <li><a class="dropdown-item" href="{{ route('files.edit', $file) }}"><i class="bx bx-edit me-2"></i>Edit</a></li>
                                         <li>
                                             <form action="{{ route('files.destroy', $file) }}" method="POST">
                                                 @csrf
                                                 @method('DELETE')
                                                 <button type="submit" class="dropdown-item text-danger"><i class="bx bx-trash me-2"></i>Delete</button>
                                             </form>
                                         </li>
                                     </ul>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 @endforeach
             </div>
         </div>
     </div>
 </div>
 @endsection --}}
 @extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Files</h1>
            <a href="{{ route('files.create') }}" class="btn btn-primary">Create New File</a>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($files as $file)
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="card h-100 border-0">
                        <img src="{{ asset('assets/images/folder.png') }}" class="card-img" alt="Folder Image">
                        <span class="badge bg-primary position-absolute top-0 end-0 m-2">{{ $file->folios->count() }} Folios</span>
                        <div class="card-img-overlay d-flex flex-column justify-content-between" style="background: rgba(255, 255, 255, 0);">
                            <div class="d-flex justify-content-start">
                                <!-- Dropdown menu on the top left -->
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="{{ route('files.show', $file->id) }}">View Detail</a></li>
                                        <li><a class="dropdown-item" href="{{ route('files.edit', $file) }}">Edit</a></li>
                                        <li>
                                            <form action="{{ route('files.destroy', $file) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="text-center">
                                <h5 class="card-title  text-primary">{{ $file->reference_number }}</h5>
                                <p class="card-text text-primary">{{ $file->file_subject }}</p>
                            </div>
                            <div> <!-- Placeholder for possible future bottom content --> </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .card-img-overlay {
        background: transparent; /* Fully transparent background for clear content display */
        color: #333; /* Ensuring text is readable over a potential light image */
    }
    .card {
        box-shadow: none; /* Retain the flat design with no shadow */
    }
    .dropdown {
        position: absolute; /* Ensure dropdown is positioned relative to its card */
        top: 10px; /* Space from the top of the card */
        left: 10px; /* Space from the left side of the card */
    }
    .badge {
        font-size: 1em; /* Slightly larger badge text */
    }
</style>
