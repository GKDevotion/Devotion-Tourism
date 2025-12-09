<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'Laravel Role Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('backend.layouts.partials.styles')
    @yield('styles')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var token = $('meta[name="csrf-token"]').attr("content");
        var url = '{{url("/")}}';
    </script>
</head>

<body>
    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->
    <!-- page container area start -->
    <div class="page-container">

       {{-- @include('backend.layouts.partials.sidebar') --}}

        <!-- main content area start -->
        <div class="main-content">
            @include('backend.layouts.partials.header')
            @yield('admin-content')
        </div>
        <!-- main content area end -->
        @include('backend.layouts.partials.footer')
    </div>
    <!-- page container area end -->

    @include('backend.layouts.partials.offsets')
    @include('backend.layouts.partials.scripts')
    @yield('scripts')
</body>
<script>
    // window.addEventListener('beforeunload', function () {
    //     // Send an AJAX request to log out the user
    //     navigator.sendBeacon('admin/logout/submit');
    // });
</script>
</html>
