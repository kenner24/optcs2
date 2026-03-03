<!DOCTYPE html>
<html lang="en">
    <head>
        @include('superAdmin.include.header')
        @stack('style')
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            @include('superAdmin.include.navbar')
            @include('superAdmin.include.sidebar')
            <div class="content-wrapper">
                @yield('content')
            </div>
            @include('superAdmin.include.footer')
            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        @include('superAdmin.include.scripts')
        @yield('scripts')
        @stack('scripts')
    </body>
</html>