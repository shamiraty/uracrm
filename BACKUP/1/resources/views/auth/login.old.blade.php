
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


.carousel-inner {
    height: 100%; /* Unaweza kubadilisha urefu wa carousel kulingana na mahitaji yako */
}

.carousel-item img {
    width: 100%; /* Picha itajaza upana wa carousel */
    height: 100%; /* Picha itajaza urefu wa carousel */
    object-fit: cover; /* Inahakikisha picha inajaa na haitaonekana imechanwa au imebanwa */
    object-position: center; /* Inahakikisha picha inazingatia sehemu ya kati */
    max-height: 100%;
    display: block;
}
/* Carousel controls styling */
.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    padding: 40px;
}

.carousel-control-prev-icon:hover,
.carousel-control-next-icon:hover {
    background-color: rgba(0, 0, 0, 0.8);
}

/* Ensure caption stays at the bottom */
.carousel-caption {
    position: absolute;
    bottom: 0; /* Aligns the caption to the bottom */
    left: 0;
    right: 0;
    text-align: center;
    background-color: #1e57a7; /* Slightly dark background to contrast the text */
    padding: 20px 30px; /* Adds some padding for text and description */
    border-radius: 0px; /* Removes the border radius to fit the entire bottom */
}

/* Text styling for caption */
.caption-text {
    font-size: 1.5rem;
    font-weight: bold;
    color: #fff;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    margin-bottom: 10px;
}

.caption-description {
    font-size: 1rem;
    color: #ddd;
    margin-bottom: 0;
}

/* Image border and hover effect */
.carousel-item img.image-border {
    border: 3px solid rgba(255, 255, 255, 0.6);
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.4);
    transition: transform 0.4s ease-in-out;
}

.carousel-item:hover .image-border {
    transform: scale(1.05);
    border-color: rgba(255, 215, 0, 0.8); /* Gold on hover */
}

/* Login form improvements */
.card {
    border-radius: 15px;
    background: #f9f9f9;
}

.card-header {
    background-color: #fff;
    border-bottom: none;
}

.card-header img {
    max-width: 100px;
    margin-bottom: 15px;
}

.card-body {
    padding: 20px 30px;
}

.form-control {
    border-radius: 10px;
    padding: 12px;
}

.btn-primary {
    background-color: #0056b3;
    border: none;
    padding: 12px;
    font-size: 1rem;
    border-radius: 10px;
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background-color: #004096;
}

.form-check-label {
    font-size: 0.9rem;
}

.input-group-text {
    background-color: #e9ecef;
    border: none;
}
.card-header {
        background-color: #fff;
        border-bottom: none;
    }

    .card-header img {
        max-width: 100px;
        margin-bottom: 15px;
    }

    .card-body {
        padding: 20px 30px;
    }

    .form-control {
        border-radius: 10px;
        padding: 12px;
    }

    .btn-primary {
        background-color: #0056b3;
        border: none;
        padding: 12px;
        font-size: 1rem;
        border-radius: 10px;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #004096;
    }

    .form-check-label {
        font-size: 0.9rem;
    }

    .input-group-text {
        background-color: #e9ecef;
        border: none;
    }
</style>

</head>

<body class="">
    <!--wrapper-->

    <div class="wrapper">
        <div class="section-authentication-cover">
            <div class="container-fluid">
                <div class="row g-0">
                    <!-- Left side with the image slider -->
                 
                    <div class="col-12 col-xl-7 col-xxl-8 auth-cover-left d-none d-xl-flex align-items-center">
                        <div class="card shadow-none bg-transparent rounded-0 mb-0 w-100 h-100">
                            <div class="card-body p-0">
                                <!-- Image slider (carousel) -->
                                <div id="loginCarousel" class="carousel slide h-100 primary-border carousel-fade" data-bs-ride="carousel">
                                    <ol class="carousel-indicators">
                                        @foreach($posts as $index => $post)
                                            <li data-bs-target="#loginCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></li>
                                        @endforeach
                                    </ol>
                                    <div class="carousel-inner h-100">
                                        @forelse ($posts as  $index => $post)
                                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                <img src="{{ asset($post->image_path) }}" class="d-block w-100 image-border" alt="{{ $post->caption }}">
                                                <div class="carousel-caption">
                                                    <h5 class="caption-text">{{ $post->caption }}</h5>
                                                    <p class="caption-description">{{ $post->description }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="carousel-item active">
                                                <img src="{{ asset('no-image-found.png') }}" class="d-block w-100 image-border" alt="No images available">
                                                <div class="carousel-caption">
                                                    <h5 class="caption-text">No Posts Available</h5>
                                                    <p class="caption-description">Please check back later.</p>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                    <a class="carousel-control-prev" href="#loginCarousel" role="button" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#loginCarousel" role="button" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right side with the login form -->
<div class="col-12 col-xl-5 col-xxl-4 auth-cover-right d-flex align-items-center justify-content-center">
    <div class="card rounded-4 shadow-lg bg-light border-0 m-3">
        <div class="card-header text-center mb-4 border-bottom-0 p-4  text-white rounded-top"style="background-color:#1e57a7">
            <img src="{{ asset('assets/images/uralogo.png') }}" alt="Logo" class="img-thumbnail mb-3" style="max-width: 80px;">
            <h5 class="mt-2 text-white">URA SACCOS CRM SYSTEM</h5>
            <p class="mb-0">Please log in to your account</p>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('login') }}" class="row g-3">
                @csrf
                <div class="col-12">
                    <label for="inputEmailAddress" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bx bx-envelope"></i>
                        </span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="john@example.com">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <label for="inputPassword" class="form-label">Password</label>
                    <div class="input-group" id="show_hide_password">
                        <span class="input-group-text bg-light">
                            <i class="bx bx-lock"></i>
                        </span>
                        <input type="password" class="form-control border-end-0 @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Enter Password">
                        <button type="button" class="input-group-text bg-light border-start-0" onclick="togglePassword()">
                            <i class="bx bx-hide"></i>
                        </button>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                        <label class="form-check-label" for="rememberMe">Remember Me</label>
                    </div>
                </div>
        
                <div class="col-12">
    <button type="submit" class="btn btn-primary btn-sm w-100" id="submit_btn">
        <span id="btn_text">Sign In</span>
        <span id="btn_spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
    </button>
</div>

            </form>
        </div>
    </div>
</div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const icon = document.querySelector("#show_hide_password .input-group-text i");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("bx-hide");
                icon.classList.add("bx-show");
            } else {
                passwordField.type = "password";
                icon.classList.remove("bx-show");
                icon.classList.add("bx-hide");
            }
        }
    </script>
</body>
</html>

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

<script>
    // Get form and button elements
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submit_btn');
    const btnText = document.getElementById('btn_text');
    const btnSpinner = document.getElementById('btn_spinner');

    // Add event listener to form submission
    form.addEventListener('submit', function(event) {
        // Show spinner and hide button text
        btnText.classList.add('d-none');
        btnSpinner.classList.remove('d-none');

        // Disable the button to prevent multiple submissions
        submitBtn.disabled = true;
    });
</script>
	<!--app JS-->
	<script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
