
@extends('backend.layouts.master')

@section('title')
Dashboard Page - Admin Panel
@endsection

@section('scripts')

@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-2">
            @include('backend.layouts.partials.side-bar-logo')
        </div>
        <div class="col-sm-9">
            <div class="breadcrumarea clearfix">
                {{-- <h4 class="page-title pull-left">Dashboard</h4> --}}
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{url('admin/dashboard')}}">Home</a></li>
                    <li><span>Dashboard</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-1">
            <span class="text-theme">
                <i class="fa fa-user"></i>
                {{auth()->guard('admin')->user()->username}}
            </span>
        </div>
    </div>
    @include('backend.layouts.partials.header-menu')
</div>
<!-- page title area end -->

<script>
    var maxHeight = 0;

    $(document).ready(function () {
        var maxHeight = 0;

        // Find the maximum height
        $(".div-height-set").each(function () {
            var currentHeight = $(this).height();
            if (currentHeight > maxHeight) {
                maxHeight = currentHeight;
            }
        });

        // Set all divs to the maximum height
        $(".div-height-set").height(maxHeight);
    });
</script>

@endsection
