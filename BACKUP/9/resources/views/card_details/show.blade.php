@extends('layouts.app')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header">
        <span class="mb-0">{{ ucwords($cardDetail->jina_kamili ?? 'N/A') }}</span>
    </div>
    <div class="card-body">
        {{-- SECTION: Immediately Visible Information --}}
        <div class="row mb-4 align-items-center">
            {{-- ID Picture (Left Column) --}}
            <div class="col-md-4 text-center position-relative"> {{-- Added position-relative here --}}
                <div class="form-group">
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
                                <span class="badge bg-primary me-1"> Download</span>
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
                            style="pointer-events: none; z-index: 10;"> {{-- pointer-events: none makes it non-clickable --}}
                            <i class="fas fa-info-circle me-1"></i> {{ ucwords($cardDetail->status ?? 'N/A') }}
                        </button>
                    </div>
                </div>
            </div>

            {{-- Personal Details (Right Column) --}}
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="jina_kamili_top" class="form-label fw-bold">Full Name:</label>
                            <input type="text" class="form-control" id="jina_kamili_top" value="{{  strtoupper($cardDetail->jina_kamili ?? 'N/A') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="namba_ya_simu_top" class="form-label fw-bold">Phone Number:</label>
                            <input type="text" class="form-control" id="namba_ya_simu_top" value="{{ $cardDetail->namba_ya_simu ?? 'N/A' }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="police_region_top" class="form-label fw-bold">Police Region:</label>
                            <input type="text" class="form-control" id="police_region_top" value="{{ ucwords($policeRegionName ?? 'N/A') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="police_district_top" class="form-label fw-bold">Police District:</label>
                            <input type="text" class="form-control" id="police_district_top" value="{{ ucwords($policeDistrictName ?? 'N/A') }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="accordion" id="cardDetailsAccordion">

            {{-- Card Information Section --}}
            <div class="accordion-item mt-3">
                <h6 class="accordion-header" id="headingCardInformation">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCardInformation" aria-expanded="false" aria-controls="collapseCardInformation">
                        <span>Card Information</span>
                    </button>
                </h6>
                <div id="collapseCardInformation" class="accordion-collapse collapse" aria-labelledby="headingCardInformation" data-bs-parent="#cardDetailsAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="approval_date" class="form-label fw-bold">Approval Date (Last Updated):</label>
                                    <input type="text" class="form-control" id="approval_date" value="{{ $cardDetail->updated_at?->format('F j, Y H:i:s') ?? 'N/A' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="comment" class="form-label fw-bold">Comment:</label>
                                    <input type="text" class="form-control" id="comment" value="{{ ucwords($cardDetail->comment ?? 'N/A') }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            {{-- Signature Picture --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3 text-center">
                                    <label class="form-label fw-bold">Signature Picture:</label>
                                    <div class="mt-2 image-container border p-2 rounded">
                                        @if(isset($cardDetail->picha_ya_sahihi_yako) && $cardDetail->picha_ya_sahihi_yako)
                                            @php
                                                $signaturePicturePath = 'uploads/signature_pictures/' . $cardDetail->picha_ya_sahihi_yako;
                                                $signaturePictureUrl = asset($signaturePicturePath);
                                            @endphp
                                            <a href="{{ $signaturePictureUrl }}" download="{{ $cardDetail->check_namba ?? 'signature' }}_signature.{{ pathinfo($signaturePicturePath, PATHINFO_EXTENSION) ?? 'png' }}">
                                                <img src="{{ $signaturePictureUrl }}" alt="Signature Picture" class="img-fluid img-thumbnail" style="max-height: 200px; object-fit: contain;">
                                                <br>
                                                <span class="badge bg-primary me-1"> Download Signature Picture</span>
                                            </a>
                                        @else
                                            <p class="text-muted">No Signature Picture available.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Personal Detail Section --}}
            <div class="accordion-item mt-3">
                <h6 class="accordion-header" id="headingPersonalDetail">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePersonalDetail" aria-expanded="false" aria-controls="collapsePersonalDetail">
                        <span>Personal Detail</span>
                    </button>
                </h6>
                <div id="collapsePersonalDetail" class="accordion-collapse collapse" aria-labelledby="headingPersonalDetail" data-bs-parent="#cardDetailsAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="check_namba" class="form-label fw-bold">Check Number:</label>
                                    <input type="text" class="form-control" id="check_namba" value="{{ $cardDetail->check_namba ?? 'N/A' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="jinsia" class="form-label fw-bold">Gender:</label>
                                    <input type="text" class="form-control" id="jinsia" value="{{ ucwords($cardDetail->jinsia ?? 'N/A') }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="kituo_cha_kazi" class="form-label fw-bold">Work Station:</label>
                                    <input type="text" class="form-control" id="kituo_cha_kazi" value="{{ ucwords($cardDetail->kituo_cha_kazi ?? 'N/A') }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="hali_ndoa" class="form-label fw-bold">Marital Status:</label>
                                    <input type="text" class="form-control" id="hali_ndoa" value="{{ ucwords($cardDetail->hali_ndoa ?? 'N/A') }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="barua_pepe" class="form-label fw-bold">Email:</label>
                                    <input type="text" class="form-control" id="barua_pepe" value="{{ $cardDetail->barua_pepe ?? 'N/A' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="mkataba_wa_ajira" class="form-label fw-bold">Employment Contract:</label>
                                    <input type="text" class="form-control" id="mkataba_wa_ajira" value="{{ ucwords($cardDetail->mkataba_wa_ajira ?? 'N/A') }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="eneo_unaloishi" class="form-label fw-bold">Residential Area:</label>
                                    <input type="text" class="form-control" id="eneo_unaloishi" value="{{ ucwords($cardDetail->eneo_unaloishi ?? 'N/A') }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="pf_no" class="form-label fw-bold">PF No:</label>
                                    <input type="text" class="form-control" id="pf_no" value="{{ $cardDetail->pf_no ?? 'N/A' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="cheo" class="form-label fw-bold">Rank (Title):</label>
                                    <input type="text" class="form-control" id="cheo" value="{{ ucwords($cardDetail->cheo ?? 'N/A') }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Application Detail Section --}}
            <div class="accordion-item mt-3">
                <h6 class="accordion-header" id="headingApplicationDetail">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseApplicationDetail" aria-expanded="false" aria-controls="collapseApplicationDetail">
                        <span>Application Detail</span>
                    </button>
                </h6>
                <div id="collapseApplicationDetail" class="accordion-collapse collapse" aria-labelledby="headingApplicationDetail" data-bs-parent="#cardDetailsAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="application_date" class="form-label fw-bold">Application Date (Registered Date):</label>
                                    <input type="text" class="form-control" id="application_date" value="{{ $cardDetail->registered_date?->format('F j, Y') ?? 'N/A' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="loaded_date" class="form-label fw-bold">Loaded Date:</label>
                                    <input type="text" class="form-control" id="loaded_date" value="{{ $cardDetail->created_at?->format('F j, Y H:i:s') ?? 'N/A' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="api_id" class="form-label fw-bold">API ID: <small>(ID from external API)</small></label>
                                    <input type="text" class="form-control" id="api_id" value="{{ $cardDetail->api_id ?? 'N/A' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="local_id" class="form-label fw-bold">Local ID: <small>(ID in this application's database)</small></label>
                                    <input type="text" class="form-control" id="local_id" value="{{ $cardDetail->id ?? 'N/A' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="trackingPIN" class="form-label fw-bold">Tracking PIN:</label>
                                    <input type="text" class="form-control" id="trackingPIN" value="{{ $cardDetail->trackingPIN ?? 'N/A' }}" readonly>
                                </div>
                            </div>
                            {{-- Status field was here, now moved to top --}}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="text-center mt-4">
            <a href="{{ route('card-details.index') }}" class="btn btn-secondary btn-lg">Back to List</a>
        </div>
    </div>
</div>
@endsection