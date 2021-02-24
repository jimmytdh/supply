<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>@yield('title','Inventory System')</title>
    <meta name="author" content="Jimmy Parker">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ url('/') }}/img/favicon.png" sizes="16x16" type="image/png">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ url('/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('/') }}/css/adminlte.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/css/loader.css">
    <link rel="stylesheet" href="{{ url('/') }}/plugins/Lobibox/lobibox.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    @yield('css')
</head>
<body class="hold-transition sidebar-mini">
<div id="loader-wrapper" style="visibility: hidden;">
    <div id="loader"></div>
</div>
<div class="wrapper">
    <!-- Navbar -->
    @include('layout.top')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @if(auth()->user()->isAdmin())
        @include('layout.menu')
    @else
        @include('layout.userMenu')
    @endif

    <!-- Content Wrapper. Contains page content -->

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
            IHOMP &copy; 2021
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2021 CSMC Inventory System.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
@yield('modal')
<script src="{{ url('/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ url('/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ url('/') }}/js/adminlte.min.js"></script>
<script src="{{ url('/') }}/plugins/Lobibox/Lobibox.js"></script>
@include('script.loader')
@include('script.lobibox')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@yield('js')
</body>
</html>
