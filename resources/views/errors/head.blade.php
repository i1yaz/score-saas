<x-laravel-ui-adminlte::adminlte-layout>

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
                        <strong></strong>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown user-menu">
                    <span href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <img src="{{asset('img/logo.svg')}}"
                             class="user-image img-circle elevation-2" alt="User Image">
                    </span>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!-- User image -->
                        <li class="user-header bg-primary">
                            <img src="{{asset('img/logo.svg')}}"
                                 class="img-circle elevation-2" alt="User Image">
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <span href="#" class="btn btn-default btn-flat">Profile</span>
                            <span href="#" class="btn btn-default btn-flat float-right"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Sign out
                            </span>

                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <span href="#" class="brand-link">
                <img src="{{asset('img/logo.svg')}}"
                     alt="Logo"
                     class="brand-image img-circle elevation-3">
                <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
            </span>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-child-indent nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
{{--                        @include('layouts.menu')--}}
                    </ul>
                </nav>
            </div>

        </aside>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

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
