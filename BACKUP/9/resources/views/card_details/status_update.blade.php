@extends('layouts.app')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h4 class="card-title mb-4">Update Status for Card</h4>

        {{-- SECTION: Immediately Visible Information --}}
        <div class="row mb-4 align-items-start">
            {{-- ID Picture & Signature Picture (Left Column) --}}
            <div class="col-md-4 text-center">
                {{-- ID Picture --}}
                <div class="form-group mb-3 position-relative"> {{-- Added position-relative for status button --}}
                    <label class="form-label fw-bold">ID Picture:</label>
                    <div class="mt-2 image-container border p-2 rounded">
                        @if(isset($cardDetail->picha_ya_kitambulisho) && $cardDetail->picha_ya_kitambulisho)
                            @php
                                $idPicturePath = 'uploads/id_pictures/' . $cardDetail->picha_ya_kitambulisho;
                                $idPictureUrl = asset($idPicturePath);
                            @endphp
                            <a href="{{ $idPictureUrl }}" download="{{ $cardDetail->check_namba ?? 'id_picture' }}_id_card.{{ pathinfo($idPicturePath, PATHINFO_EXTENSION) ?? 'png' }}">
                                <img src="{{ $idPictureUrl }}" alt="ID Picture" class="img-fluid img-thumbnail" style="max-height: 150px; object-fit: contain;">
                                <br>
                               <span class="badge bg-primary me-1"> Download ID Picture</span>
                            </a>
                        @else
                            <p class="text-muted">No ID Picture available.</p>
                        @endif

                        {{-- Status Button/Badge Overlay --}}
                        <button type="button" class="btn btn-sm position-absolute top-0 end-0 mt-1 me-1 shadow-sm
                            @if($cardDetail->status == 'Applied') btn-warning
                            @elseif($cardDetail->status == 'Processing') btn-info
                            @elseif($cardDetail->status == 'Rejected') btn-danger
                            @elseif($cardDetail->status == 'Printed') btn-primary
                            @elseif($cardDetail->status == 'Issued' || $cardDetail->status == 'Received') btn-success
                            @else btn-light text-dark
                            @endif"
                            style="pointer-events: none; z-index: 10;">
                            <i class="fas fa-info-circle me-1"></i> {{ ucwords($cardDetail->status ?? 'N/A') }}
                        </button>
                    </div>
                </div>

                {{-- Signature Picture --}}
                <div class="form-group mb-3 mt-4"> {{-- Added mt-4 for spacing --}}
                    <label class="form-label fw-bold">Signature Picture:</label>
                    <div class="mt-2 image-container border p-2 rounded">
                        @if(isset($cardDetail->picha_ya_sahihi_yako) && $cardDetail->picha_ya_sahihi_yako)
                            @php
                                $signaturePicturePath = 'uploads/signature_pictures/' . $cardDetail->picha_ya_sahihi_yako;
                                $signaturePictureUrl = asset($signaturePicturePath);
                            @endphp
                            <a href="{{ $signaturePictureUrl }}" download="{{ $cardDetail->check_namba ?? 'signature' }}_signature.{{ pathinfo($signaturePicturePath, PATHINFO_EXTENSION) ?? 'png' }}">
                                <img src="{{ $signaturePictureUrl }}" alt="Signature Picture" class="img-fluid img-thumbnail" style="max-height: 100px; object-fit: contain;"> {{-- Reduced max-height for signature --}}
                                <br>
                               <span class="badge bg-primary me-1"> Download Signature</span>
                            </a>
                        @else
                            <p class="text-muted">No Signature Picture available.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Key Details (Right Column) --}}
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="jina_kamili" class="form-label fw-bold">Full Name:</label>
                            <input type="text" class="form-control" id="jina_kamili" value="{{  strtoupper($cardDetail->jina_kamili ?? 'N/A') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="check_namba" class="form-label fw-bold">Check Number:</label>
                            <input type="text" class="form-control" id="check_namba" value="{{ $cardDetail->check_namba ?? 'N/A' }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="mkoa_wa_kipolisi" class="form-label fw-bold">Police Region:</label>
                            <input type="text" class="form-control" id="mkoa_wa_kipolisi" value="{{ ucwords($policeRegionName ?? $cardDetail->mkoa_wa_kipolisi ?? 'N/A') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="wilaya_ya_kipolisi" class="form-label fw-bold">Police District:</label>
                            <input type="text" class="form-control" id="wilaya_ya_kipolisi" value="{{ ucwords($policeDistrictName ?? $cardDetail->wilaya_ya_kipolisi ?? 'N/A') }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="namba_ya_simu" class="form-label fw-bold">Phone Number:</label>
                            <input type="text" class="form-control" id="namba_ya_simu" value="{{ $cardDetail->namba_ya_simu ?? 'N/A' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="registered_date" class="form-label fw-bold">Application Date:</label>
                            <input type="text" class="form-control" id="registered_date" value="{{ $cardDetail->registered_date?->format('F j, Y') ?? 'N/A' }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('card-details.updateStatus', $cardDetail->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="form-group mb-3">
                <label for="status" class="form-label">Select New Status:</label>
                <select class="form-control" id="status" name="status">
                    @foreach($availableStatuses as $statusOption)
                        <option value="{{ $statusOption }}" {{ (old('status', $cardDetail->status ?? '') == $statusOption) ? 'selected' : '' }}>
                            {{ $statusOption }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">Update Status</button>
                <a href="{{ route('card-details.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </form>
    </div>
</div>
@endsection