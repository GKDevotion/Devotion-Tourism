
@extends('backend.layouts.master')

@section('title')
Notice Board Edit - Admin Panel
@endsection

@section('styles')
<style>
    .form-check-label {
        text-transform: capitalize;
    }
</style>
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-2">
            @include('backend.layouts.partials.side-bar-logo')
        </div>
        <div class="col-md-6">
            <div class="breadcrumbs-area clearfix">
                {{-- <h4 class="page-title pull-left d-none">Notice Board Edit - {{ $data->type }}</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.notice-board.index') }}">All Notice Board</a></li>
                    <li><span>Edit Notice Board</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <p class="float-end">
                @if (Auth::guard('admin')->user()->can('notice-board.edit'))
                    <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                        <i class="fa fa-save"></i> Update
                    </button>
                @endif
                <a href="{{ route('admin.notice-board.index') }}" class="btn btn-danger">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </p>
        </div>
        <div class="col-md-1">
            <span class="text-theme">
                <i class="fa fa-user"></i>
                {{auth()->guard('admin')->user()->username}}
            </span>
        </div>
        @include('backend.layouts.partials.header-menu')
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-3">
            <h3 class="pb-3">Update Notice Board</h3>
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.notice-board.update', $data->id) }}" onsubmit="return onSubmitValidateForm();" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">

                                    <div class="col-md-12 mb-2">
                                        <div class="form-group">
                                            <label for="type" class="mb-0">Notice Type<span class="text-error">*</span></label>
                                            <input class="form-control type" data-required="yes" id="type" name="type" placeholder="Notice Type" value="{{$data->type}}">
                                        </div>
                                        @error('type')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <div class="form-group">
                                            <label for="description" class="mb-0">Description<span class="text-error">*</span></label>
                                            <textarea class="form-control description" data-required="yes" id="description" name="description" placeholder="Description">{{$data->description}}</textarea>
                                        </div>
                                        @error('description')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <div class="form-group">
                                            <label for="date" class="mb-0">Date<span class="text-error">*</span></label>
                                            <input type="date" data-required="yes" class="form-control" id="date" name="date" placeholder="Date" value="{{$data->date}}">
                                        </div>
                                        @error('date')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <div class="form-group">
                                            <label for="notice_by" class="mb-0">Notice By<span class="text-error">*</span></label>
                                            <input class="form-control by" data-required="yes" id="notice_by" name="notice_by" placeholder="Notice By" value="{{$data->notice_by}}">
                                        </div>
                                        @error('notice_by')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <label for="attachement">Attachement</label>
                                        <input type="file" data-default-file="{{url( 'storage/'.$data->attachement )}}" class="attachement" id="attachement" name="attachement" accept="image/jpg, image/jpeg, image/png">
                                        @if($errors->has('attachement'))
                                            <div class="error">{{ $message }}</div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="sort_order" class="mb-0">Sort Order</label>
                                            <input type="number" class="form-control allow-only-number" id="sort_order" name="sort_order" placeholder="Sort Order" value="{{$data->sort_order}}">
                                        </div>
                                        @error('sort_order')
                                            <div class="error text-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="status" class="mb-0">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="1" {{$data->status == 1 ? 'selected' : ''}}>Active</option>
                                                <option value="0" {{$data->status == 0 ? 'selected' : ''}}>De Active</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success pr-4 pl-4" id="submitForm">
                                    <i class="fa fa-save"></i> Update
                                </button>
                                <a href="{{ route('admin.award.index') }}" class="btn btn-danger pr-4 pl-4">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const dropifyClass = ["attachement"];
        $( dropifyClass ).each(function( index, className ) {

            if( $('.'+className).length > 0){
                $('.'+className).dropify();
            }
        });
    })
</script>
@endsection
