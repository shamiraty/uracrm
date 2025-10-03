@extends('layouts.app')

@section('content')

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

<form action="{{ route('card-details.update', $cardDetail->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="jina_kamili">Full Name:</label>
                        <input type="text" readonly class="form-control" id="jina_kamili" name="jina_kamili" value="{{ old('jina_kamili', $cardDetail->jina_kamili ?? '') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="barua_pepe">Email:</label>
                        <input type="email" readonly class="form-control" id="barua_pepe" name="barua_pepe" value="{{ old('barua_pepe', $cardDetail->barua_pepe ?? '') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="namba_ya_simu">Phone Number:</label>
                        <input type="text" class="form-control" id="namba_ya_simu" name="namba_ya_simu" value="{{ old('namba_ya_simu', $cardDetail->namba_ya_simu ?? '') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="jinsia">Gender:</label>
                        <input type="text" readonly class="form-control" value="{{ $cardDetail->jinsia ?? '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="hali_ndoa">Marital Status:</label>
                        <input type="text" readonly class="form-control" value="{{ $cardDetail->hali_ndoa ?? '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="tarehe_ya_kuzaliwa">Date of Birth:</label>
                        <input type="date" readonly class="form-control" value="{{ isset($cardDetail->tarehe_ya_kuzaliwa) ? \Carbon\Carbon::parse($cardDetail->tarehe_ya_kuzaliwa)->format('Y-m-d') : '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="pf_no">PF No:</label>
                        <input type="text" readonly class="form-control" value="{{ $cardDetail->pf_no ?? '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="cheo">Title:</label>
                        <input type="text" readonly class="form-control" value="{{ $cardDetail->cheo ?? '' }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="wilaya_ya_kipolisi">Police District:</label>
                        <input type="text" readonly class="form-control" value="{{ $policeDistrictName ?? '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="mkoa_wa_kipolisi">Police Region:</label>
                        <input type="text" readonly class="form-control" value="{{ $policeRegionName ?? '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="kituo_cha_kazi">Work Station:</label>
                        <input type="text" readonly class="form-control" value="{{ $cardDetail->kituo_cha_kazi ?? '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="check_namba">Check Number:</label>
                        <input type="text" readonly class="form-control" value="{{ $cardDetail->check_namba ?? '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="mkataba_wa_ajira">Employment Contract:</label>
                        <input type="text" readonly class="form-control" value="{{ $cardDetail->mkataba_wa_ajira ?? '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="eneo_unaloishi">Residential Area:</label>
                        <input type="text" readonly class="form-control" value="{{ $cardDetail->eneo_unaloishi ?? '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="status">Status:</label>
                        <select class="form-control" id="status" name="status">
                            <option value="Applied" {{ (old('status', $cardDetail->status ?? '') == 'Applied') ? 'selected' : '' }}>Applied</option>
                            <option value="Processing" {{ (old('status', $cardDetail->status ?? '') == 'Processing') ? 'selected' : '' }}>Processing</option>
                            <option value="Rejected" {{ (old('status', $cardDetail->status ?? '') == 'Rejected') ? 'selected' : '' }}>Rejected</option>
                            <option value="Printed" {{ (old('status', $cardDetail->status ?? '') == 'Printed') ? 'selected' : '' }}>Printed</option>
                            <option value="Issued" {{ (old('status', $cardDetail->status ?? '') == 'Issued') ? 'selected' : '' }}>Issued</option>
                            <option value="Received" {{ (old('status', $cardDetail->status ?? '') == 'Received') ? 'selected' : '' }}>Received</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="comment">Comment:</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3">{{ old('comment', $cardDetail->comment ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="picha_ya_kitambulisho">ID Picture:</label>
                        @if(isset($cardDetail->picha_ya_kitambulisho) && $cardDetail->picha_ya_kitambulisho && file_exists(public_path('uploads/id_pictures/' . $cardDetail->picha_ya_kitambulisho)))
                            <img src="{{ asset('uploads/id_pictures/' . $cardDetail->picha_ya_kitambulisho) }}" alt="ID Picture" class="img-thumbnail mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="picha_ya_kitambulisho_clear" value="1" id="clearIdPicture">
                                <label class="form-check-label" for="clearIdPicture">Clear existing image</label>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="picha_ya_kitambulisho" name="picha_ya_kitambulisho">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="picha_ya_sahihi_yako">Signature Picture:</label>
                        @if(isset($cardDetail->picha_ya_sahihi_yako) && $cardDetail->picha_ya_sahihi_yako && file_exists(public_path('uploads/signature_pictures/' . $cardDetail->picha_ya_sahihi_yako)))
                            <img src="{{ asset('uploads/signature_pictures/' . $cardDetail->picha_ya_sahihi_yako) }}" alt="Signature Picture" class="img-thumbnail mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="picha_ya_sahihi_yako_clear" value="1" id="clearSignaturePicture">
                                <label class="form-check-label" for="clearSignaturePicture">Clear existing image</label>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="picha_ya_sahihi_yako" name="picha_ya_sahihi_yako">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Update Card Detail</button>
        <a href="{{ route('card-details.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
</form>
@endsection
