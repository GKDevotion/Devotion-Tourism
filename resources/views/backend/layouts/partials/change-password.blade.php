
@extends('backend.layouts.master')

@section('title')
Change Password - Admin Panel
@endsection

@section('styles')
    <style>
        .badge-info {
            min-width: 100px;
            padding: 8px;
            margin: 2px;
        }
        .child{
            text-align: left;
        }
    </style>
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-md-7">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left d-none">Admins</h4>
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><span>Change Password</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3 text-end">

        </div>
        <div class="col-md-2 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <h3 class="text-center m-3">Change Password</h3>
                        <div class="row">
                            <div class="col-md-4 offset-md-4">
                                <form id="sendOtpForm" class="">
                                    <input class="form-control" type="email" name="email" value="{{Auth::guard('admin')->user()->email}}" placeholder="Enter your email" disabled>
                                    <button class="btn btn-success mt-3" type="submit">
                                        Send OTP <i class="fa fa-send" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-md-4 offset-md-4">
                                <form id="changePasswordForm" class="d-none">
                                    <input class="form-control mb-2" type="email" name="email" value="{{Auth::guard('admin')->user()->email}}" placeholder="Enter your email" disabled>
                                    <input class="form-control mb-2" type="text" name="otp" placeholder="Enter OTP" required>
                                    <input class="form-control mb-2" type="password" name="password" placeholder="Enter new password" required>
                                    <input class="form-control mb-2" type="password" name="password_confirmation" placeholder="Confirm new password" required>
                                    <button class="btn btn-success mt-2" type="submit">
                                        <i class="fa fa-key" aria-hidden="true"></i> Change Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>
@endsection


@section('scripts')
     <script>
        $(document).ready(function () {
            // Send OTP form submission
            $('#sendOtpForm').on('submit', function (e) {
                e.preventDefault();

                const email = $('input[name="email"]').val();
                const sendButton = $(this).find('button');

                // Disable the button to prevent multiple clicks
                sendButton.prop('disabled', true).text('Sending...');

                $.ajax({
                    url: url+'/admin/send-otp',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: { email: email },
                    success: function (response) {
                        showToast(response.message);
                        $('#sendOtpForm').addClass('d-none'); // hide send otp form
                        $('#changePasswordForm').removeClass('d-none'); // hide send otp form
                    },
                    error: function (xhr) {
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            showToast(Object.values(errors).join('\n')); // Show validation errors
                        } else {
                            showToast('An error occurred. Please try again.');
                        }
                    },
                    complete: function () {
                        sendButton.prop('disabled', false).text('Send OTP');
                    }
                });
            });

            // Change Password form submission
            $('#changePasswordForm').on('submit', function (e) {
                e.preventDefault();

                const formData = $(this).serializeArray();
                const submitButton = $(this).find('button');

                // Disable the button to prevent multiple clicks
                submitButton.prop('disabled', true).text('Processing...');

                $.ajax({
                    url: url+'/admin/change-password',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: formData,
                    success: function (response) {
                        showToast(response.message);

                        // Clear the form fields after successful password change
                        $('#changePasswordForm')[0].reset();
                    },
                    error: function (xhr) {
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            showToast(Object.values(errors).join('\n')); // Show validation errors
                        } else {
                            showToast('An error occurred. Please try again.');
                        }
                    },
                    complete: function () {
                        submitButton.prop('disabled', false).text('Change Password');
                    }
                });
            });
        });
    </script>
@endsection
