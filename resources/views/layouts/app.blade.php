<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
    <!-- Add this inside the <head> section of your HTML -->
<meta name="csrf-token" content="{{ csrf_token() }}">

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{ asset('assets/images/uralogo.png') }}" type="image/png" />
	<!--plugins-->
	<link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet"/>
	<link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <!-- DataTables Buttons Extension -->
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('assets/js/pace.min.js') }}"></script>
	<!-- Bootstrap CSS -->

	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	<link href="{{ asset('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}" />

    <!--added CSS-->
    <link href="{{ asset('assets/plugins/awesome/css/all.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/plugins/awesome/css/fontawesome.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/plugins/fonts/font-awesome.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/ajax_cloudfare.css') }}" rel="stylesheet"/>

    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/plugins/select2/css/custom.css') }}" rel="stylesheet"/>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Theme -->
    <link href="{{ asset('css/urasaccos-theme.css') }}" rel="stylesheet">
    <!-- Toastr -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"> --}}

<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">




    <!-- Include Select2 CSS -->

    <!--improved datatable-->
    <link href="{{ asset('assets/plugins/datatable/css/bootstrap_buttons.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
 <!--ends  added CSS-->
 @stack('styles')
	<title>URASACCOS CRM</title>

</head>
<!--added style-->
<style>
        body {
            font-family: 'Roboto', sans-serif;
            font-size: 18px;
            color: #333;
        }
        .bg-primary {
            background-color: #1e57a7 !important;
        }
        .btn-primary {
            background-color: #1e57a7 !important;
        }
        .text-primary {
            color: #1e57a7 !important;
        }
        
        /* Loading Spinner Styles */
        .spinner-overlay {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .spinner-container {
            position: relative;
            width: 80px;
            height: 80px;
        }
        .spinner-ring {
            position: absolute;
            border-radius: 50%;
            animation: spin 2s linear infinite;
        }
        .spinner-ring-1 {
            width: 80px;
            height: 80px;
            border: 4px solid transparent;
            border-top: 4px solid #17479e;
            border-right: 4px solid #164087;
            animation-duration: 1.5s;
        }
        .spinner-ring-2 {
            width: 60px;
            height: 60px;
            top: 10px;
            left: 10px;
            border: 3px solid transparent;
            border-bottom: 3px solid #17479e;
            border-left: 3px solid #164087;
            animation-duration: 2s;
            animation-direction: reverse;
        }
        .spinner-ring-3 {
            width: 40px;
            height: 40px;
            top: 20px;
            left: 20px;
            border: 2px solid transparent;
            border-top: 2px solid #17479e;
            border-right: 2px solid #164087;
            animation-duration: 1s;
        }
        .spinner-core {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 16px;
            height: 16px;
            background: linear-gradient(135deg, #17479e 0%, #164087 100%);
            border-radius: 50%;
            animation: pulse-glow 2s ease-in-out infinite;
            box-shadow: 0 0 20px rgba(23, 71, 158, 0.4);
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes pulse-glow {
            0%, 100% { 
                transform: translate(-50%, -50%) scale(1);
                box-shadow: 0 0 20px rgba(23, 71, 158, 0.4);
            }
            50% { 
                transform: translate(-50%, -50%) scale(1.1);
                box-shadow: 0 0 40px rgba(23, 71, 158, 0.8);
            }
        }
        /* Responsive adjustments */
        @media (max-width: 576px) {
            .spinner-container {
                width: 60px;
                height: 60px;
            }
            
            .spinner-ring-1 { width: 60px; height: 60px; }
            .spinner-ring-2 { width: 45px; height: 45px; top: 7.5px; left: 7.5px; }
            .spinner-ring-3 { width: 30px; height: 30px; top: 15px; left: 15px; }
        }
</style>


<!--added style-->

<body>
    <!-- Loading Spinner Overlay -->
    <div id="loading-spinner" class="spinner-overlay position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="z-index: 9999;">
        
        <!-- Main Spinner Container -->
        <div class="spinner-container">
            <!-- Outer rotating rings -->
            <div class="spinner-ring spinner-ring-1"></div>
            <div class="spinner-ring spinner-ring-2"></div>
            <div class="spinner-ring spinner-ring-3"></div>
            
            <!-- Central pulsing core -->
            <div class="spinner-core"></div>
        </div>
    </div>

<div class="wrapper">
    <div class="sidebar-wrapper">
        <div class="sidebar-header">
            <div>
                <img src="{{ asset('assets/images/uralogo.png') }}" width="55" alt="Logo"class="img-thumbnail">
            </div>
            <div>
                <h2 class="logo-text text-white"> CRM system</h2>
            </div>
            <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
            </div>
         </div>

        @include('body.sidebar')</div>
    @include('body.header')

            <div class="page-wrapper">
            <div class="page-content">
                @yield('content')
            </div>
            </div>
            	<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">Copyright © 2025 URA SACCOS. All right reserved.</p>
		</footer>

        </div>
    </div>
<!--start switcher-->
<div class="switcher-wrapper">
    <div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
    </div>
    <div class="switcher-body">

        <h6 class="mb-0">Header Colors</h6>
        <hr/>
        <div class="header-colors-indigators">
            <div class="row row-cols-auto g-3">
                <div class="col">
                    <div class="indigator headercolor1" id="headercolor1"></div>
                </div>

            </div>
        </div>
        <hr/>
        <h6 class="mb-0">Sidebar Colors</h6>
        <hr/>
        <div class="header-colors-indigators">
            <div class="row row-cols-auto g-3">
                <div class="col">
                    <div class="indigator sidebarcolor1" id="sidebarcolor1"></div>
                </div>

            </div>
        </div>
    </div>
</div>
   <!--plugins-->
	<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
	<!-- Bootstrap JS -->
	<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
	<script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
	<script src="{{ asset('assets/plugins/chartjs/js/chart.js') }}"></script>
	<script src="{{ asset('assets/plugins/sparkline-charts/jquery.sparkline.min.js') }}"></script>


    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


	<!--Morris JavaScript -->
	<script src="{{ asset('assets/plugins/raphael/raphael-min.js') }}"></script>
	<script src="{{ asset('assets/plugins/morris/js/morris.js') }}"></script>
	<script src="{{ asset('assets/js/index2.js') }}"></script>
    <script src="{{ asset('assets/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/bs-stepper/js/main.js') }}"></script>
    <!-- DataTables JS -->
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<!--added js-->
<link href="{{ asset('assets/plugins/select2/js/select2.min.js') }}" rel="stylesheet"/>
<link href="{{ asset('assets/plugins/select2/js/select2-custom.js') }}" rel="stylesheet"/>
<!--ends added javascript-->
<!-- SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <!-- Loading Spinner Script -->
        <script>
            $(document).ready(function() {
                // Hide the spinner when the page is fully loaded
                $(window).on('load', function() {
                    $('#loading-spinner').fadeOut(500, function() {
                        $(this).remove();
                    });
                });
                
                // Fallback: Hide spinner after maximum 5 seconds
                setTimeout(function() {
                    $('#loading-spinner').fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);
            });
        </script>

        <script>

         @if(Session::has('message'))

         var type = "{{ Session::get('alert-type','info') }}"

         switch(type){

            case 'info':

            toastr.info(" {{ Session::get('message') }} ");

            break;



            case 'success':

            toastr.success(" {{ Session::get('message') }} ");

            break;



            case 'warning':

            toastr.warning(" {{ Session::get('message') }} ");

            break;



            case 'error':

            toastr.error(" {{ Session::get('message') }} ");

            break;

         }

         @endif

        </script>


<script>
    $(document).ready(function() {
        $('#example').DataTable();
      } );
</script>
<script>
    $(document).ready(function() {
        $('#Loan_application_Enquiries').DataTable();
      } );
</script>

<script>
    $(document).ready(function() {
        // Common DataTable options
        const dataTableOptions = {
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    //text: '<i class="fa fa-copy text-primary"></i> Copy'
                },
                {
                    extend: 'excel',
                    //text: '<i class="fa fa-file-excel text-primary"></i> Excel'
                },
                {
                    extend: 'pdf',
                    //text: '<i class="fa fa-file-pdf text-primary"></i> PDF'
                },
                {
                    extend: 'print',
                    //text: '<i class="fa fa-print text-primary"></i> Print'
                }

            ],
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            pageLength: 23
        };

        // Initialize DataTables for both tables using a single selector
        $('#Assigned_Enquiries, #payment_table, #dashboard,#Loan_application_Enquiries').DataTable(dataTableOptions);
    });

</script>
<script>
$(document).ready(function() {
    var table = $('#example2').DataTable({
        lengthChange: false,
        buttons: [
            'copy',         // Copy to clipboard
            'excel',        // Export to Excel
            'pdf',          // Export to PDF
            'print',        // Print
            'colvis'        // Column visibility
        ],
        order: [[1, 'desc']]  // Assuming the 'created_at' is in the second column
    });

    // Append buttons to the specified container
    table.buttons().container()
        .appendTo('#example2 .col-md-6:eq(0)');
});
</script>




	// <!--app JS-->

	<script src="{{ asset('assets/js/app.js') }}"></script>
    
    
    
    @stack('scripts')

</body>
</html>