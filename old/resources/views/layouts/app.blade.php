<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
    <!-- Add this inside the <head> section of your HTML -->

    <!--this is of AJAX crud--->
<meta name="csrf-token" content="{{ csrf_token() }}">

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{ asset('assets/images/uralogo.png') }}" type="image/png" />

  <link rel="stylesheet" href="{{ asset('asset/assets/css/remixicon.css') }}">
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
  <link rel="stylesheet" href="{{ asset('asset/assets/css/style.css') }}">


  


  
  <script>
    document.addEventListener("DOMContentLoaded", function () {
    const selects = document.querySelectorAll(".custom-select-dropdown");

    selects.forEach(select => {
        // Hide original select field
        select.style.display = "none";

        // Create custom dropdown wrapper
        const dropdownWrapper = document.createElement("div");
        dropdownWrapper.className = "dropdown w-100";

        const dropdownButton = document.createElement("button");
        dropdownButton.className = "btn dropdown-toggle w-100 text-start";
        dropdownButton.type = "button";
        dropdownButton.dataset.bsToggle = "dropdown";
        dropdownButton.ariaExpanded = "false";

        // Material Design Styles (Combined for brevity)
        Object.assign(dropdownButton.style, {
            border: "1px solid #ccc",
            background: "white",
            outline: "none",
            borderRadius: "8px",
            padding: "10px 15px",
            transition: "box-shadow 0.2s ease-in-out"
        });

        dropdownButton.addEventListener("focus", () => dropdownButton.style.boxShadow = "0px 4px 10px rgba(0, 0, 0, 0.2)");
        dropdownButton.addEventListener("blur", () => dropdownButton.style.boxShadow = "none");


        let selectedOption = select.options[select.selectedIndex];
        dropdownButton.textContent = selectedOption ? selectedOption.text : "Select an option";

        const dropdownMenu = document.createElement("ul");
        dropdownMenu.className = "dropdown-menu w-100";
        Object.assign(dropdownMenu.style, {
            maxHeight: "250px",
            overflowY: "auto",
            borderRadius: "8px",
            boxShadow: "0px 6px 15px rgba(0, 0, 0, 0.15)",
            padding: "8px",
            background: "white"
        });

        const searchInput = document.createElement("input");
        searchInput.className = "form-control mb-2";
        searchInput.type = "text";
        searchInput.placeholder = "Search...";
        Object.assign(searchInput.style, { borderRadius: "5px", padding: "8px" });
        dropdownMenu.appendChild(searchInput);

        Array.from(select.options).forEach(option => {
            if (option.value !== "") {
                const listItem = document.createElement("li");
                const link = document.createElement("a");
                link.className = "dropdown-item";
                link.href = "#";
                link.textContent = option.text;
                link.dataset.value = option.value;

                Object.assign(link.style, {
                    padding: "10px 15px",
                    borderRadius: "5px",
                    transition: "background 0.3s ease-in-out, color 0.3s ease-in-out",
                    color: "#444",
                    fontWeight: "normal"
                });

                link.addEventListener("mouseenter", () => Object.assign(link.style, { background: "#8cb2fe", color: "white" }));
                link.addEventListener("mouseleave", () => Object.assign(link.style, { background: "transparent", color: "#444" }));

                link.addEventListener("click", function (event) {
                    event.preventDefault();
                    dropdownButton.textContent = this.textContent;
                    select.value = this.dataset.value;
                    dropdownMenu.classList.remove("show");

                    // Trigger change event on the original select
                    select.dispatchEvent(new Event('change')); 
                });

                listItem.appendChild(link);
                dropdownMenu.appendChild(listItem);
            }
        });

        searchInput.addEventListener("keyup", () => {
            const filter = searchInput.value.toLowerCase();
            dropdownMenu.querySelectorAll(".dropdown-item").forEach(item => {
                item.style.display = item.textContent.toLowerCase().includes(filter) ? "" : "none";
            });
        });

        dropdownWrapper.appendChild(dropdownButton);
        dropdownWrapper.appendChild(dropdownMenu);
        select.parentNode.insertBefore(dropdownWrapper, select);
    });


    // Event listener for select changes (for dependent selects)
    selects.forEach(select => {
        select.addEventListener('change', function() {
            const selectedValue = this.value;
            const dependentSelectId = this.dataset.dependentSelect; // Assuming you add a data-dependent-select attribute


            if (dependentSelectId) {
                const dependentSelect = document.getElementById(dependentSelectId);
                if (dependentSelect) {
                    // You'll need to implement logic to populate the dependent select
                    // based on the selectedValue.  Example:

                    // Clear existing options (except the first, if it's a placeholder)
                    while (dependentSelect.options.length > 1) {  // Or 0 if no placeholder
                        dependentSelect.remove(1);
                    }

                    // Example: Fetch options based on selectedValue (replace with your logic)
                    // fetch(`/api/options?value=${selectedValue}`)  // Example API call
                    //     .then(response => response.json())
                    //     .then(options => {
                    //         options.forEach(option => {
                    //             const newOption = document.createElement('option');
                    //             newOption.value = option.value;
                    //             newOption.text = option.text;
                    //             dependentSelect.add(newOption);
                    //         });
                    //     });

                    // Simpler Example (if data is already available):
                    const optionsForValue = someData[selectedValue]; // Replace someData with your data
                    if (optionsForValue) {
                        optionsForValue.forEach(option => {
                            const newOption = document.createElement('option');
                            newOption.value = option.value;
                            newOption.text = option.text;
                            dependentSelect.add(newOption);
                        });
                    }
                }
            }
        });
    });
});


const someData = {  // Example data - replace with your actual data or API call
    "1": [{ value: "1a", text: "Option 1a" }, { value: "1b", text: "Option 1b" }],
    "2": [{ value: "2a", text: "Option 2a" }, { value: "2b", text: "Option 2b" }],
    // ... more data
};
</script>
  


  
 <!--ends  added CSS-->
 @stack('styles')
	<title>URASACCOS CRM</title>
</head>
<!--added style-->
@include('body.sidebar')</div>
<main class="dashboard-main">
    @include('body.header')
    <div class="dashboard-main-body">

                @yield('content')
    </div>
    <footer class="d-footer">
        <div class="row align-items-center justify-content-between">
          <div class="col-auto">
            <p class="mb-0">© 2025 URA SACCOS LTD. All Rights Reserved.</p>
          </div>
          <div class="col-auto">
            <p class="mb-0">Developed by <span class="text-primary-600">ICT DEPERMENT</span></p>
          </div>
        </div>
      </footer>
</main>

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
    // Initialize the first DataTable
    let cleanDataTable = new DataTable('#dataTable');
</script>

<script>
    // Initialize the second DataTable with a different variable name
    let problematicDataTable = new DataTable('#problematicDataTable');
</script>


<!--ends added javascript-->
<!-- SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('asset/assets/js/homeTwoChart.js') }}"></script>


{{--
<script src="{{ asset('asset/assets/select2/style.js') }}"></script>
<link rel="stylesheet" href="{{ asset('asset/assets/select2/style.css') }}">
--}}






<script>
    // =============================== Wizard Step Js Start ================================
    $(document).ready(function() {
        // click on next button
        $('.form-wizard-next-btn').on("click", function() {
            var parentFieldset = $(this).parents('.wizard-fieldset');
            var currentActiveStep = $(this).parents('.form-wizard').find('.form-wizard-list .active');
            var next = $(this);
            var nextWizardStep = true;
            parentFieldset.find('.wizard-required').each(function(){
                var thisValue = $(this).val();

                if( thisValue == "") {
                    $(this).siblings(".wizard-form-error").show();
                    nextWizardStep = false;
                }
                else {
                    $(this).siblings(".wizard-form-error").hide();
                }
            });
            if( nextWizardStep) {
                next.parents('.wizard-fieldset').removeClass("show","400");
                currentActiveStep.removeClass('active').addClass('activated').next().addClass('active',"400");
                next.parents('.wizard-fieldset').next('.wizard-fieldset').addClass("show","400");
                $(document).find('.wizard-fieldset').each(function(){
                    if($(this).hasClass('show')){
                        var formAtrr = $(this).attr('data-tab-content');
                        $(document).find('.form-wizard-list .form-wizard-step-item').each(function(){
                            if($(this).attr('data-attr') == formAtrr){
                                $(this).addClass('active');
                                var innerWidth = $(this).innerWidth();
                                var position = $(this).position();
                                $(document).find('.form-wizard-step-move').css({"left": position.left, "width": innerWidth});
                            }else{
                                $(this).removeClass('active');
                            }
                        });
                    }
                });
            }
        });
        //click on previous button
        $('.form-wizard-previous-btn').on("click",function() {
            var counter = parseInt($(".wizard-counter").text());;
            var prev =$(this);
            var currentActiveStep = $(this).parents('.form-wizard').find('.form-wizard-list .active');
            prev.parents('.wizard-fieldset').removeClass("show","400");
            prev.parents('.wizard-fieldset').prev('.wizard-fieldset').addClass("show","400");
            currentActiveStep.removeClass('active').prev().removeClass('activated').addClass('active',"400");
            $(document).find('.wizard-fieldset').each(function(){
                if($(this).hasClass('show')){
                    var formAtrr = $(this).attr('data-tab-content');
                    $(document).find('.form-wizard-list .form-wizard-step-item').each(function(){
                        if($(this).attr('data-attr') == formAtrr){
                            $(this).addClass('active');
                            var innerWidth = $(this).innerWidth();
                            var position = $(this).position();
                            $(document).find('.form-wizard-step-move').css({"left": position.left, "width": innerWidth});
                        }else{
                            $(this).removeClass('active');
                        }
                    });
                }
            });
        });
        //click on form submit button
        $(document).on("click",".form-wizard .form-wizard-submit" , function(){
            var parentFieldset = $(this).parents('.wizard-fieldset');
            var currentActiveStep = $(this).parents('.form-wizard').find('.form-wizard-list .active');
            parentFieldset.find('.wizard-required').each(function() {
                var thisValue = $(this).val();
                if( thisValue == "" ) {
                    $(this).siblings(".wizard-form-error").show();
                }
                else {
                    $(this).siblings(".wizard-form-error").hide();
                }
            });
        });
        // focus on input field check empty or not
        $(".form-control").on('focus', function(){
            var tmpThis = $(this).val();
            if(tmpThis == '' ) {
                $(this).parent().addClass("focus-input");
            }
            else if(tmpThis !='' ){
                $(this).parent().addClass("focus-input");
            }
        }).on('blur', function(){
            var tmpThis = $(this).val();
            if(tmpThis == '' ) {
                $(this).parent().removeClass("focus-input");
                $(this).siblings(".wizard-form-error").show();
            }
            else if(tmpThis !='' ){
                $(this).parent().addClass("focus-input");
                $(this).siblings(".wizard-form-error").hide();
            }
        });
    });
    // =============================== Wizard Step Js End ================================
</script>
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- jQuery (Required for Toastr) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- jQuery (Required for Toastr) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function () {
        @if(session('message'))
            var type = "{{ session('alert-type', 'info') }}"; // Chagua aina ya notification
            toastr[type]("{{ session('message') }}", type.charAt(0).toUpperCase() + type.slice(1), {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 9000
            });
        @endif

        @if(session('success'))
            toastr.success("{{ session('success') }}", "Success", {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 9000
            });
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}", "Error", {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 9000
            });
        @endif

        @if($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}", "Error", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 9000
                });
            @endforeach
        @endif
    });
</script>


</body>
</html>









