@extends('layouts.app')

@section('title', 'Carousel')

@section('content')
    <div id="enhancedCarousel" class="carousel slide h-100 primary-border" data-bs-ride="carousel">
        <ol class="carousel-indicators">
            @foreach ($posts as $index => $post)
                <li data-bs-target="#enhancedCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></li>
            @endforeach
        </ol>
        <div class="carousel-inner h-100">
            @forelse ($posts as $index => $post)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <img src="{{ asset('slides/' . $post->image_path) }}" class="d-block w-100 image-border" alt="{{ $post->caption }}">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>{{ $post->caption }}</h5>
                        <p>{{ $post->description }}</p>
                    </div>
                </div>
            @empty
                <div class="carousel-item active">
                    <img src="{{ asset('no-image-found.png') }}" class="d-block w-100 image-border" alt="No images available">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>No Posts Available</h5>
                        <p>Please check back later.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <a class="carousel-control-prev" href="#enhancedCarousel" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </a>
        <a class="carousel-control-next" href="#enhancedCarousel" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </a>
    </div>
@endsection
