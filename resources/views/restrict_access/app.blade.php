<x-laravel-ui-adminlte::adminlte-layout>
    @push('third_party_stylesheets')
        <link rel="stylesheet" href="{{asset('css/select2-bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}"/>
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
                        @if(\Auth::check())
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!-- User image -->
                        <li class="user-header bg-primary">
                            <img src="{{asset('img/logo.svg')}}"
                                 class="img-circle elevation-2" alt="User Image">
                            @if(\Auth::check())
                                <p>
                                    {{ Auth::user()->email }}
                                    <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                                </p>
                            @endif
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
            @stack('after_third_party_scripts')
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
