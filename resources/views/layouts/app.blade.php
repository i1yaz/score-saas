<x-laravel-ui-adminlte::adminlte-layout>
    @push('third_party_stylesheets')
        <link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/jquery-ui/jquery-ui.min.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="{{asset('css/select2-bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.css')}}"/>
        <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
        <style>
            .required:after{
                content:'(*)';
                color:red;
                padding-left:5px;
            }
        </style>
    @endpush
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <!-- Main Header -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                                class="fas fa-bars"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                            <strong>{{getRoleDescriptionOfLoggedInUser()}}</strong>
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            <img src="{{asset('img/logo.svg')}}"
                                class="user-image img-circle elevation-2" alt="User Image">
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <!-- User image -->
                            <li class="user-header bg-primary">
                                <img src="{{asset('img/logo.svg')}}"
                                    class="img-circle elevation-2" alt="User Image">
                                <p>
                                    {{ Auth::user()->email }}
                                    <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                                <a href="#" class="btn btn-default btn-flat float-right"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Sign out
                                </a>
                                <form id="logout-form" action="{{ route('logout',['guard'=>\Illuminate\Support\Facades\Auth::guard()->name]) }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <!-- Left side column. contains the logo and sidebar -->
            @include('layouts.sidebar')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @yield('content')
            </div>
            @push('third_party_scripts')
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha512-lzilC+JFd6YV8+vQRNRtU7DOqv5Sa9Ek53lXt/k91HZTJpytHS1L6l1mMKR9K6VVoDt4LiEXaa6XBrYk1YhGTQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                <script src="{{asset("plugins/toastr/toastr.min.js")}}"></script>
                <script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                <script src="{{asset('plugins/bootstrap/js/bootstrap.min.js')}}"></script>
                <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
                <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
                <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
                <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
                <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
                <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
                <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
                <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
{{--                <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.1/cdn.min.js" integrity="sha512-qxuuYirD/2PiyVS9pjdPbm8Uixg0uq1jywNIP8gsmoYpSs7J7nTHTTFvCW2mMYPQPRaTaIxOlXJJc8S+B7OBvw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>--}}
                {{--    <script src="../../plugins/jszip/jszip.min.js"></script>--}}
                {{--    <script src="../../plugins/pdfmake/pdfmake.min.js"></script>--}}
                {{--    <script src="../../plugins/pdfmake/vfs_fonts.js"></script>--}}
                {{--    <script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>--}}
                {{--    <script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>--}}
                {{--    <script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>--}}

                <script type="text/javascript">
                    toastr.options.closeDuration = 3000;
                    $('#start_date').datepicker()
                    $('.date-input').datepicker()
                    $("input[type='submit']").on("click", function (e) {
                        $(this).attr("disabled", "disabled");
                        $(this).parents("form").submit();
                    });
                    function ToggleBtnLoader(btnLoader) {

                        let spinner = "<span class='spinner-border spinner-border-sm'></span> Processing...";

                        if (!btnLoader.is(":disabled")) {
                            btnLoader.attr("data-old-text", btnLoader.text());
                            btnLoader
                                .html(spinner)
                                .prop("disabled", true);
                        }else{
                            let oldText = btnLoader.attr("data-old-text")
                            btnLoader.html(oldText).prop("disabled", false);
                        }

                    }

                </script>
            @endpush

            <!-- Main Footer -->
            <footer class="main-footer">
                <div class="float-right d-none d-sm-block">
{{--                    <b>Version</b> 3.1.0--}}
                </div>
                <strong>Copyright &copy; 2014-2023 <a href="{{config('app.url')}}">{{config('app.name')}}</a></strong> All rights
                reserved.
            </footer>
        </div>
    </body>
</x-laravel-ui-adminlte::adminlte-layout>
