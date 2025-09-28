
<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{ asset('assets/images/uralogo.png') }}" type="image/png" />
	<!--plugins-->
	<link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
	<!-- loader-->
	<link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('assets/js/pace.min.js') }}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
	<title>SACCOs Enquiry System - Login</title>
	<style>
    /* Ensure the carousel and images cover the full div */
    .carousel,
    .carousel-item,
    .carousel-inner {
        height: 100%;
    }

    .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        width: 40px;
        height: 40px;
    }

    .carousel-control-prev-icon:hover,
    .carousel-control-next-icon:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }

    .carousel-caption {
        background-color: rgba(0, 0, 0, 0.6);
        padding: 15px;
        border-radius: 10px;
    }

    .caption-text {
        font-size: 1.5rem;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .caption-description {
        font-size: 1rem;
        background-color: rgba(255, 255, 255, 0.3);
        padding: 10px;
        border-radius: 5px;
        display: inline-block;
    }

    .carousel-item img.image-border {
        border: 5px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        transition: transform 0.5s ease-in-out;
    }

    .carousel-item:hover .image-border {
        transform: scale(1.05);
        border-color: rgba(255, 215, 0, 0.9); /* Gold color on hover */
    }
</style>

</head>

<body class="">
    <!--wrapper-->
    <div class="wrapper">
        <div class="section-authentication-cover">
            <div class="">
                <div class="row g-0">
                    <div class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">
                        <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0 w-100 h-100">
                            <div class="card-body p-0">
                                <!-- Enhanced Carousel with Professional Transitions -->
<div id="enhancedCarousel" class="carousel slide h-100 primary-border carousel-fade" data-bs-ride="carousel">
    <ol class="carousel-indicators">
        <li data-bs-target="#enhancedCarousel" data-bs-slide-to="0" class="active"></li>
        <li data-bs-target="#enhancedCarousel" data-bs-slide-to="1"></li>
        <li data-bs-target="#enhancedCarousel" data-bs-slide-to="2"></li>
    </ol>
    <div class="carousel-inner h-100">
        @forelse ($posts as $index => $post)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <img src="{{ asset('/' . $post->image_path) }}" class="d-block w-100 image-border" alt="{{ $post->caption }}">
                <div class="carousel-caption d-none d-md-block">
                    <h5 class="caption-text">{{ $post->caption }}</h5>
                    <p class="caption-description">{{ $post->description }}</p>
                </div>
            </div>
        @empty
            <div class="carousel-item active">
                <img src="{{ asset('no-image-found.png') }}" class="d-block w-100 image-border" alt="No images available">
                <div class="carousel-caption d-none d-md-block">
                    <h5 class="caption-text">No Posts Available</h5>
                    <p class="caption-description">Please check back later.</p>
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
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-5 col-xxl-4 auth-cover-right d-flex align-items-center justify-content-center">
                        <div class="card rounded-0 m-3 shadow-lg bg-light">
                            <div class="card-header text-center mb-4 border-bottom">
                                <img src="{{ asset('assets/images/uralogo.png') }}" width="114" alt="Logo" class="img-fluid">
                                <h5 class="mt-3">URA SACCOS CRM System</h5>
                                <p class="mb-0 text-muted">Please log in to your account</p>
                            </div>
                            <div class="card-body p-4 p-sm-5">
                                <div class="form-body">
                                    <form method="POST" action="{{ route('login') }}" class="row g-3">
                                        @csrf
                                        <div class="col-12">
                                            <label for="inputEmailAddress" class="form-label">Email Address</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email') }}" 
                                                   required autocomplete="email" autofocus placeholder="john@example.com">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="inputChoosePassword" class="form-label">Password</label>
                                            <div class="input-group" id="show_hide_password">
                                                <input type="password" class="form-control border-end-0 @error('password') is-invalid @enderror" 
                                                       id="password" name="password" required autocomplete="current-password" 
                                                       placeholder="Enter Password">
                                                <button type="button" class="input-group-text bg-light border-start-0" onclick="togglePassword()">
                                                    <i class="bx bx-hide"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 d-flex align-items-center">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                                                <label class="form-check-label" for="rememberMe">Remember Me</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot Password?</a>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Sign In</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>



				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
	<!--plugins-->
	<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
	<!-- Password show & hide js -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
	<!--app JS-->
	<script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
