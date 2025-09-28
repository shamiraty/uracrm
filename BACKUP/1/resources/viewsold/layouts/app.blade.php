<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



    <!-- Include Select2 CSS -->

    <!--improved datatable-->
    <link href="{{ asset('assets/plugins/datatable/css/bootstrap_buttons.css') }}" rel="stylesheet" />

 <!--ends  added CSS-->

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
            background-color: #2980b9 !important;
        }
        .btn-primary {
            background-color: #2980b9 !important;
        }
        .text-primary {
            color: #2980b9 !important;
        }
</style>
<!--added style-->

<div class="wrapper">
    <div class="sidebar-wrapper" data-simplebar="true">
        <div class="sidebar-header">
            <div>
                <img src="{{ asset('assets/images/uralogo.png') }}" width="55" alt="Logo">
            </div>
            <div>
                <h2 class="logo-text"> CRM system</h2>
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
			<p class="mb-0">Copyright © 2024 URA SACCOS. All right reserved.</p>
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
   <!-- Bootstrap JS -->
	<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
	<!--plugins-->
	<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
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
    <!-- Bootstrap JS -->
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/new/buttons.colVis.min.js') }}"></script>

<!--added js-->
<link href="{{ asset('assets/plugins/select2/js/select2.min.js') }}" rel="stylesheet"/>
<link href="{{ asset('assets/plugins/select2/js/select2-custom.js') }}" rel="stylesheet"/>
<!--ends added javascript-->
<!-- SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        var table = $('#example2').DataTable( {
            lengthChange: false,
            buttons: [ 'copy', 'excel', 'pdf', 'print']
        } );

        table.buttons().container()
            .appendTo( '#example2_wrapper .col-md-6:eq(0)' );
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


<
	// <!--app JS-->

	<script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
