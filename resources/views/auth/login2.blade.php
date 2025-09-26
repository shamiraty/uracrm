
<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">


    <link rel="icon" type="image/png" href="{{ asset('asset/assets/images/favicon.png') }}" sizes="16x16">
  <!-- remix icon font css  -->
  {{-- <link rel="stylesheet" href="{{ asset('asset/assets/css/remixicon.css') }}">
  <!-- BootStrap css -->
  <link rel="stylesheet" href="{{ asset('asset/assets/css/lib/bootstrap.min.css') }}">
  <!-- Apex Chart css -->
  <link rel="stylesheet" href="{{ asset('asset/assets/css/lib/apexcharts.css') }}">
  <!-- Data Table css -->
  <link rel="stylesheet" href="{{ asset('asset/assets/css/lib/dataTables.min.css') }}">
  <!-- Text Editor css -->
  <link rel="stylesheet" href="{{ asset('asset/assets/css/lib/editor-katex.min.css') }}">
  <link rel="stylesheet" href="{{ asset('asset/assets/css/lib/editor.atom-one-dark.min.css') }}">
  <link rel="stylesheet" href="{{ asset('asset/assets/css/lib/editor.quill.snow.css') }}">
  <!-- Date picker css -->
  <link rel="stylesheet" href="{{ asset('asset/assets/css/lib/flatpickr.min.css') }}">
  <!-- Calendar css -->
  <link rel="stylesheet" href="{{ asset('asset/assets/css/lib/full-calendar.css') }}">
  <!-- Vector Map css -->
  <link rel="stylesheet" href="{{ asset('asset/assets/css/lib/jquery-jvectormap-2.0.5.css') }}">
  <!-- Popup css -->
  <link rel="stylesheet" href="{{ asset('asset/assets/css/lib/magnific-popup.css') }}">
  <!-- Slick Slider css -->
  <link rel="stylesheet" href="{{ asset('asset/assets/css/lib/slick.css') }}">
  <!-- prism css -->
  <link rel="stylesheet" href="{{ asset('asset/assets/css/lib/prism.css') }}">
  <!-- file upload css -->
  <link rel="stylesheet" href="{{ asset('asset/assets/css/lib/file-upload.css') }}">

  <link rel="stylesheet" href="{{ asset('asset/assets/css/lib/audioplayer.css') }}">
  <!-- main css -->
  <link rel="stylesheet" href="{{ asset('asset/assets/css/style.css') }}"> --}}
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>


</head>
<body>
    {{-- <section class="auth bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="assets/images/ura3.JPG" alt="">
            </div>
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <div>
                    <a href="index.html" class="mb-40 max-w-290-px">
                        <img src="assets/images/uralogo.png" alt="">
                    </a>
                    <h4 class="mb-12">URASACCOS CRM</h4>
                    <p class="mb-32 text-secondary-light text-lg">Sign In to your Account </p>
                </div>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="icon-field mb-16">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:email"></iconify-icon>
                        </span>
                        <input type="email" class="form-control h-56-px bg-neutral-50 radius-12 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="position-relative mb-20">
                        <div class="icon-field">
                            <span class="icon top-50 translate-middle-y">
                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                            </span>
                            <input type="password" class="form-control h-56-px bg-neutral-50 radius-12 @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required>
                            <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" onclick="togglePasswordVisibility()"></span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="">
                        <div class="d-flex justify-content-between gap-2">
                            <div class="form-check style-check d-flex align-items-center">

                            </div>

                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">Sign In</button>


                </form>
            </div>
        </div>
    </section> --}}
<!-- Login 8 - Bootstrap Brain Component -->
<section class="bg-light p-3 p-md-4 p-xl-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-xxl-11">
          <div class="card border-light-subtle shadow-sm">
            <div class="row g-0">
              <div class="col-12 col-md-6">
                <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy" src="./assets/img/logo-img-1.webp" alt="Welcome back you've been missed!">
              </div>
              <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                <div class="col-12 col-lg-11 col-xl-10">
                  <div class="card-body p-3 p-md-4 p-xl-5">
                    <div class="row">
                      <div class="col-12">
                        <div class="mb-5">
                          <div class="text-center mb-4">
                            <a href="#!">
                              <img src="./assets/img/bsb-logo.svg" alt="BootstrapBrain Logo" width="175" height="57">
                            </a>
                          </div>
                          <h4 class="text-center">Welcome back you've been missed!</h4>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <div class="d-flex gap-3 flex-column">
                          <a href="#!" class="btn btn-lg btn-outline-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                              <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                            </svg>
                            <span class="ms-2 fs-6">Log in with Google</span>
                          </a>
                        </div>
                        <p class="text-center mt-4 mb-5">Or sign in with</p>
                      </div>
                    </div>
                    <form action="#!">
                      <div class="row gy-3 overflow-hidden">
                        <div class="col-12">
                          <div class="form-floating mb-3">
                            <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                            <label for="email" class="form-label">Email</label>
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" required>
                            <label for="password" class="form-label">Password</label>
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" name="remember_me" id="remember_me">
                            <label class="form-check-label text-secondary" for="remember_me">
                              Keep me logged in
                            </label>
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="d-grid">
                            <button class="btn btn-dark btn-lg" type="submit">Log in now</button>
                          </div>
                        </div>
                      </div>
                    </form>
                    <div class="row">
                      <div class="col-12">
                        <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-center mt-5">
                          <a href="#!" class="link-secondary text-decoration-none">Create new account</a>
                          <a href="#!" class="link-secondary text-decoration-none">Forgot password</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

      <!-- jQuery library js -->
      <script src="{{ asset('asset/assets/js/lib/jquery-3.7.1.min.js') }}"></script>
      <!-- Bootstrap js -->
      <script src="{{ asset('asset/assets/js/lib/bootstrap.bundle.min.js') }}"></script>
      <!-- Apex Chart js -->
      <script src="{{ asset('asset/assets/js/lib/apexcharts.min.js') }}"></script>
      <!-- Data Table js -->
      <script src="{{ asset('asset/assets/js/lib/dataTables.min.js') }}"></script>
      <!-- Iconify Font js -->
      <script src="{{ asset('asset/assets/js/lib/iconify-icon.min.js') }}"></script>
      <!-- jQuery UI js -->
      <script src="{{ asset('asset/assets/js/lib/jquery-ui.min.js') }}"></script>
      <!-- Vector Map js -->
      <script src="{{ asset('asset/assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
      <script src="{{ asset('asset/assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
      <!-- Popup js -->
      <script src="{{ asset('asset/assets/js/lib/magnifc-popup.min.js') }}"></script>
      <!-- Slick Slider js -->
      <script src="{{ asset('asset/assets/js/lib/slick.min.js') }}"></script>
      <!-- prism js -->
      <script src="{{ asset('asset/assets/js/lib/prism.js') }}"></script>
      <!-- file upload js -->
      <script src="{{ asset('asset/assets/js/lib/file-upload.js') }}"></script>
      <!-- audioplayer -->
      <script src="{{ asset('asset/assets/js/lib/audioplayer.js') }}"></script>

      <!-- main js -->
      <script src="{{ asset('asset/assets/js/app.js') }}"></script>

    <script>
          // ================== Password Show Hide Js Start ==========
          function initializePasswordToggle(toggleSelector) {
            $(toggleSelector).on('click', function() {
                $(this).toggleClass("ri-eye-off-line");
                var input = $($(this).attr("data-toggle"));
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        }
        // Call the function
        initializePasswordToggle('.toggle-password');
      // ========================= Password Show Hide Js End ===========================
    </script>
</body>
</html>

