{{-- @extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>{{ $file->name }} - {{ $file->folios->count() }} Folios</h2>
    <div class="accordion" id="filesAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{ $file->id }}">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $file->id }}" aria-expanded="true" aria-controls="collapse{{ $file->id }}">
                    View Folios
                </button>
            </h2>
            <div id="collapse{{ $file->id }}" class="accordion-collapse collapse show" aria-labelledby="heading{{ $file->id }}" data-bs-parent="#filesAccordion">

                <div class="accordion-body">
                    <ul class="list-group">
                        @php $totalFolios = $file->folios->count(); @endphp
                        @foreach ($file->folios->sortByDesc('created_at') as $folio)
                            <li class="list-group-item">
                                Folio{{ $totalFolios - $loop->index }}:
                                <a href="{{ asset('attachments/' . basename($folio->file_path)) }}" target="_blank">{{ basename($folio->file_path) }}</a>
                                <p>Enquirer: {{ $folio->folioable->full_name }}</p>
                                <p>Check Number: {{ $folio->folioable->check_number }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>






            </div>
        </div>
    </div>
</div>
@endsection --}}
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">{{ $file->name }} - {{ $file->folios->count() }} Folios</h2>
    <div class="row">
        <!-- Folio List Column wrapped in a card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Folio List</h4>
                </div>
                <div class="list-group list-group-flush" id="folioList" role="tablist">
                    @php $totalFolios = $file->folios->count(); @endphp
                    @foreach ($file->folios->sortByDesc('created_at') as $folio)
                        <a class="list-group-item list-group-item-action" id="list-{{ $folio->id }}-list" data-bs-toggle="list" href="#list-{{ $folio->id }}" role="tab" aria-controls="list-{{ $folio->id }}">
                            Folio{{ $totalFolios - $loop->index }}: {{ basename($folio->file_path) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Folio Details Column wrapped in a card -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Folio Details</h4>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="nav-tabContent">
                        @foreach ($file->folios->sortByDesc('created_at') as $folio)
                            <div class="tab-pane fade" id="list-{{ $folio->id }}" role="tabpanel" aria-labelledby="list-{{ $folio->id }}-list">
                               
                                <p><strong>Enquirer:</strong> {{ $folio->folioable->full_name }}</p>
                                <p><strong>Check Number:</strong> {{ $folio->folioable->check_number }}</p>
                                @if (strtolower(pathinfo($folio->file_path, PATHINFO_EXTENSION)) === 'pdf')
                                    <object data="{{ asset('/' . $folio->file_path) }}" type="application/pdf" width="100%" height="500px">
                                        <p>Your browser does not support PDFs. Please download the PDF to view it: <a href="{{ asset('/' . $folio->file_path) }}">Download PDF</a>.</p>
                                    </object>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
