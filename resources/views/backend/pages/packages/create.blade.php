@extends('backend.layouts.master')

@section('title')
    Package Create - Admin Panel
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropify/0.2.2/css/dropify.min.css" />
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
            <div class="col-sm-7">
                <div class="breadcrumbs-area clearfix">
                    {{-- <h4 class="page-title pull-left d-none">Configuration Create</h4> --}}
                    <ul class="breadcrumbs pull-left m-2">
                        <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.package.index') }}">All Package</a></li>
                        <li><span>Create Package</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-2">
                <p class="float-end">
                    <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <a href="{{ route('admin.package.index') }}" class="btn btn-danger">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </p>
            </div>
            <div class="col-md-1">
                <span class="text-theme">
                    <i class="fa fa-user"></i>
                    {{ auth()->guard('admin')->user()->username }}
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
                <h3 class="pb-3">Create Package</h3>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.package.store') }}" onsubmit="return onSubmitValidateForm();"
                            method="POST" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- left column -->
                                <div class="col-md-6">
                                    <!-- general form elements -->
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Package Content</h3>
                                        </div>
                                        <!-- /.card-header -->

                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="category_id">Parent Category</label>
                                                        <select class="form-control category_id" id="category_id"
                                                            name="category_id">
                                                            <option value="0">Select Category</option>
                                                            @foreach ($categories as $prc)
                                                                @if ($prc->childrenRecursive->isNotEmpty())
                                                                    @foreach ($prc->childrenRecursive as $ar)
                                                                        <option value="{{ $ar->id }}">
                                                                            {{ $ar->title }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('category_id'))
                                                            <div class="error">{{ $errors->first('category_id') }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sub_category_id">Sub Category</label>
                                                        <select class="form-control sub_category_id" id="sub_category_id"
                                                            name="sub_category_id">
                                                            <option value="0">Select Sub Category</option>
                                                            @foreach ($categories as $prc)
                                                                @if ($prc->childrenRecursive->isNotEmpty())
                                                                    @foreach ($prc->childrenRecursive as $child)
                                                                        @foreach ($child->childrenRecursive as $ar)
                                                                            <option value="{{ $ar->id }}"
                                                                                class="d-none sub-category parent-category-{{ $ar->parent_id }}">
                                                                                {{ $ar->title }}</option>
                                                                        @endforeach
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('sub_category_id'))
                                                            <div class="error">{{ $errors->first('sub_category_id') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="title">Title</label>
                                                <input type="text" class="form-control" id="title" name="title"
                                                    placeholder="{{ __('Title') }}" value="{{ old('title') }}">
                                                @if ($errors->has('title'))
                                                    <div class="error">{{ $errors->first('title') }}</div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="image">Banner Pic</label>
                                                <input type="file" class="dropify" id="image" name="image">
                                                @if ($errors->has('image'))
                                                    <div class="error">{{ $errors->first('image') }}</div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="location">Location</label>
                                                <input type="text" class="form-control" id="location" name="location"
                                                    placeholder="{{ __('Location') }}" value="{{ old('location') }}">
                                                @if ($errors->has('location'))
                                                    <div class="error">{{ $errors->first('location') }}</div>
                                                @endif
                                            </div>

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="price">Package Price</label>
                                                        <input type="text" class="form-control" id="price"
                                                            name="price" placeholder="{{ __('Amount') }}"
                                                            value="{{ old('price') }}">
                                                        @if ($errors->has('price'))
                                                            <div class="error">{{ $errors->first('price') }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="adult_price">Adult Price</label>
                                                        <input type="text" class="form-control" id="adult_price"
                                                            name="adult_price" placeholder="{{ __('Adult Price') }}"
                                                            value="{{ old('adult_price') }}">
                                                        @if ($errors->has('adult_price'))
                                                            <div class="error">{{ $errors->first('adult_price') }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="child_price">Child Price</label>
                                                        <input type="text" class="form-control" id="child_price"
                                                            name="child_price" placeholder="{{ __('Child Price') }}"
                                                            value="{{ old('child_price') }}">
                                                        @if ($errors->has('child_price'))
                                                            <div class="error">{{ $errors->first('child_price') }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="group_price">Group Price</label>
                                                        <input type="text" class="form-control" id="group_price"
                                                            name="group_price" placeholder="{{ __('Group Price') }}"
                                                            value="{{ old('group_price') }}">
                                                        @if ($errors->has('group_price'))
                                                            <div class="error">{{ $errors->first('group_price') }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="discount">Discount (in %)</label>
                                                        <input type="text" class="form-control" id="discount"
                                                            name="discount" placeholder="{{ __('Discount (in %)') }}"
                                                            value="{{ old('discount') }}">
                                                        @if ($errors->has('discount'))
                                                            <div class="error">{{ $errors->first('discount') }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mb-2">
                                                    <div class="form-group">
                                                        <label class="mb-0" for="poster">Poster</label>
                                                        <input type="file" class="dropify" id="poster"
                                                            name="poster">
                                                    </div>
                                                    @error('poster')
                                                        <div class="error text-error">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>

                                <!-- Right column -->
                                <div class="col-md-6">
                                    <!-- general form elements -->
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Blog Meta data</h3>
                                        </div>
                                        <!-- /.card-header -->

                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="title">Short Description, Meta Description</label>
                                                <textarea type="text" class="form-control" id="short_description" name="short_description"
                                                    placeholder="{{ __('Short Description, Meta Description here') }}" rows="4">{{ old('short_description') }}</textarea>
                                                @if ($errors->has('short_description'))
                                                    <div class="error">{{ $errors->first('short_description') }}</div>
                                                @endif
                                            </div>
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="start_date">Start Date</label>
                                                        <input type="date" name="start_date" class="form-control"
                                                            id="start_date"
                                                            value="{{ old('start_date', isset($dataArr) ? $dataArr->start_date : '') }}">
                                                        @if ($errors->has('start_date'))
                                                            <div class="error">{{ $errors->first('start_date') }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="end_date">End Date</label>
                                                        <input type="date" name="end_date" class="form-control"
                                                            id="end_date"
                                                            value="{{ old('end_date', isset($dataArr) ? $dataArr->end_date : '') }}">
                                                        @if ($errors->has('end_date'))
                                                            <div class="error">{{ $errors->first('end_date') }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-group mb-0">
                                                <label>Inclusive</label>
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter Inclusive" id="inclusiveInput">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a href="javascript:void(0)" class="btn " id="addInclusive"
                                                            style="background-color: #ab8134">
                                                            <i class="fa fa-plus" style="color: white"></i>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="p-2" id="inclusive-list"></div>
                                            </div>

                                            <div class="form-group mb-0 ">
                                                <label>Exclusive</label>
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter Exclusive" id="exclusiveInput">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a href="javascript:void(0)" class="btn" id="addExclusive"
                                                            style="background-color: #ab8134">
                                                            <i class="fa fa-plus" style="color: white"></i>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="p-2" id="exclusive-list"></div>
                                            </div>


                                            <div class="form-group mb-0">
                                                <label>FAQs</label>
                                                <div class="row mb-2">
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter Question" id="faqQuestionInput">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter Answer" id="faqAnswerInput">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a href="javascript:void(0)" class="btn" id="addFaq"
                                                            style="background-color: #ab8134">
                                                            <i class="fa fa-plus" style="color: white"></i>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="p-2" id="faq-list"></div>
                                            </div>

                                            <div class="form-group mb-0">
                                                <label>Itinerary</label>
                                                <div class="row mb-2">
                                                    <div class="col-md-10 mb-2">
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter Title" id="iteneryTitleInput">
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter Data" id="iteneryDataInput">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a href="javascript:void(0)" class="btn" id="addItinerary"
                                                            style="background-color: #ab8134">
                                                            <i class="fa fa-plus" style="color: white"></i>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="p-2" id="itenery-list"></div>
                                            </div>
                                            {{-- 
                                            <!-- Itinerary Section -->
                                            <div class="form-group mb-0">
                                                <label>Itinerary</label>
                                                <div class="row mb-2">
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter Day Description" id="itineraryInput">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a href="javascript:void(0)" class="btn" id="addItinerary"
                                                            style="background-color:#ab8134">
                                                            <i class="fa fa-plus" style="color:white"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="itinerary-list"></div>
                                            </div>
 --}}


                                            <div class="form-group">
                                                <label for="title">Recommended Package</label>
                                                <select name="package_id" class="form-control package_id">
                                                    @forelse($packageArr as $ar)
                                                        <option value="{{ $ar->id }}">{{ $ar->title }}</option>
                                                    @empty
                                                        <option value="">No package fount yet!.</option>
                                                    @endforelse
                                                </select>
                                                @if ($errors->has('description'))
                                                    <div class="error">{{ $errors->first('description') }}</div>
                                                @endif
                                            </div>

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="duration">Duration</label>
                                                        <input type="text" class="form-control" id="duration"
                                                            name="duration" placeholder="{{ __('Duration') }}"
                                                            value="{{ old('duration') }}">
                                                        @if ($errors->has('duration'))
                                                            <div class="error">{{ $errors->first('duration') }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="adult_size">Adult Size</label>
                                                        <input type="text" class="form-control" id="adult_size"
                                                            name="adult_size" placeholder="{{ __('Adult Size') }}"
                                                            value="{{ old('adult_size') }}">
                                                        @if ($errors->has('adult_size'))
                                                            <div class="error">{{ $errors->first('adult_size') }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="tour_type">Tour Type</label>
                                                        <input type="text" class="form-control" id="tour_type"
                                                            name="tour_type" placeholder="{{ __('Tour Type') }}"
                                                            value="{{ old('tour_type') }}">
                                                        @if ($errors->has('tour_type'))
                                                            <div class="error">{{ $errors->first('tour_type') }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="status">Status</label>
                                                        <select class="form-control" name="status" id="status">
                                                            <option value="1">Active</option>
                                                            <option value="0">De-Active</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                                <!-- /.card -->

                                <!-- Package Description -->
                                <div class="col-md-12">
                                    <!-- general form elements -->
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Package Description</h3>
                                        </div>
                                        <!-- /.card-header -->

                                        <div class="card-body">

                                            <div class="col-md-12 col-sm-12 mb-2">
                                                <label class="mb-0" for="term_condition">Term & Condition <span
                                                        class="text-error">*</span></label>
                                                <textarea type="text" class="ckeditor form-control" id="term_condition" name="term_condition"
                                                    placeholder="Term Condition" rows="16"></textarea>
                                                <div class="error text-error"></div>
                                            </div>

                                            <div class="form-group">
                                                <label for="title">Description</label>
                                                <textarea type="text" class="ckeditor form-control" id="description" name="description"
                                                    placeholder="{{ __('Package Description') }}" rows="16"> {{ old('description') }}</textarea>
                                                @if ($errors->has('description'))
                                                    <div class="error">{{ $errors->first('description') }}</div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="title">Keyword</label>
                                                <textarea type="text" class="form-control" id="keyword" name="keyword" placeholder="{{ __('Blog keyword') }}"
                                                    rows="5">{{ old('keyword') }}</textarea>
                                                @if ($errors->has('keyword'))
                                                    <div class="error">{{ $errors->first('keyword') }}</div>
                                                @endif
                                            </div>

                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>

                                <!-- Package Images -->
                                <div class="col-md-12">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Package Images</h3>
                                        </div>
                                        <!-- /.card-header -->

                                        <style>
                                            .image a {
                                                cursor: pointer;
                                            }
                                        </style>
                                        <div class="card-body">
                                            <div class="form-group row">

                                                <div class="column_image_size_0 imageRow_0 image col-md-3" id="tr_lot_0">
                                                    <input type="file" name="lot_file_0" id="lot_file_0"
                                                        class="dropify" data-height="200" data-max-file-size="2M"
                                                        data-allowed-file-extensions="jpg jpeg png webp"
                                                        data-default-file="">
                                                </div>

                                                <div class="column_image_size_1 imageRow_1 image col-md-3" id="tr_lot_1">
                                                    <input type="file" name="lot_file_1" id="lot_file_1"
                                                        class="dropify" data-height="200" data-max-file-size="2M"
                                                        data-allowed-file-extensions="jpg jpeg png webp"
                                                        data-default-file="">
                                                </div>

                                                <div class="column_image_size_2 imageRow_2 image col-md-3" id="tr_lot_2">
                                                    <input type="file" name="lot_file_2" id="lot_file_2"
                                                        class="dropify" data-height="200" data-max-file-size="2M"
                                                        data-allowed-file-extensions="jpg jpeg png webp"
                                                        data-default-file="">
                                                </div>

                                                <div class="column_image_size_3 imageRow_3 image col-md-3" id="tr_lot_3">
                                                    <input type="file" name="lot_file_3" id="lot_file_3"
                                                        class="dropify" data-height="200" data-max-file-size="2M"
                                                        data-allowed-file-extensions="jpg jpeg png webp"
                                                        data-default-file="">
                                                </div>

                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>

                                <div class="col-md-12 text-center mb-4">
                                    <a href="{{ route('admin.package.index') }}" class="btn btn-danger"><i
                                            class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                                    <button type="submit" class="btn btn-success"><i class="far fa-save"
                                            aria-hidden="true"></i> Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropify/0.2.2/js/dropify.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#description'))
            .then(editor => {
                editorDescriptionInstance = editor;
            })
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
            .create(document.querySelector('#term_condition'))
            .then(editor => {
                editorAdditionFetureInstance = editor;
            })
            .catch(error => {
                console.error(error);
            });

        $('.dropify').dropify();

        $("#category_id").on("change", function() {
            var category_id = $(this).val();
            $("#sub_category_id").find('.sub-category').addClass("d-none");
            $("#sub_category_id").find(".parent-category-" + category_id).removeClass("d-none");
        });

        // ---------- ADD INCLUSIVE ----------
        document.getElementById('addInclusive').addEventListener('click', function() {
            let input = document.getElementById('inclusiveInput');
            let value = input.value.trim();
            if (value === "") return;

            let box = document.createElement('div');
            box.className =
                "text-dark p-2 m-1 d-flex align-items-center justify-content-between rounded"; // justify-content-between pushes × to right
            box.style.display = "block"; // ensures new line
            box.innerHTML = `
            <span>${value}</span>
            <span class="text-dark" style="cursor:pointer;font-weight:bold;">&times;</span>
            <input type="hidden" name="inclusive[]" value="${value}">
            `;

            box.querySelector(".text-dark").addEventListener("click", function() {
                box.remove();
            });

            document.getElementById('inclusive-list').appendChild(box);
            input.value = "";
        });

        // ---------- ADD EXCLUSIVE ----------
        document.getElementById('addExclusive').addEventListener('click', function() {
            let input = document.getElementById('exclusiveInput');
            let value = input.value.trim();
            if (value === "") return;

            let box = document.createElement('div');
            box.className =
                "text-dark p-2 m-1 d-flex align-items-center justify-content-between rounded";
            box.style.display = "block"; // ensures new line
            box.innerHTML = `
            ${value}
            <span class="ml-2 text-dark" style="cursor:pointer;font-weight:bold;">&times;</span>
            <input type="hidden" name="exclusive[]" value="${value}">
            `;

            box.querySelector("span").addEventListener("click", function() {
                box.remove();
            });

            document.getElementById('exclusive-list').appendChild(box);
            input.value = "";
        });

        // ---------- ADD FAQ ----------
        document.getElementById('addFaq').addEventListener('click', function() {
            let question = document.getElementById('faqQuestionInput').value.trim();
            let answer = document.getElementById('faqAnswerInput').value.trim();

            if (!question || !answer) {
                alert('Please enter both Question and Answer');
                return;
            }

            // Create single JSON object for this FAQ
            let faqObj = {
                question: question,
                answer: answer
            };

            // Convert to string to store in hidden input
            let faqJsonString = JSON.stringify(faqObj);

            // Create hidden input for backend
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'faq[]';
            input.value = faqJsonString;

            document.getElementById('faq-list').appendChild(input);

            // Create badge/div to display added FAQ
            let box = document.createElement('div');
            box.className = "text-dark p-2 m-1 d-flex align-items-center justify-content-between rounded border";
            box.innerHTML = `
            <div>
                <strong>Question:</strong> ${question} <br>
                <strong>Answer:</strong> ${answer}
            </div>
            <button type="button" class="btn btn-sm btn-danger delete-btn">
                <i class="fa fa-trash"></i> Delete
            </button>
            `;

            // Remove badge and hidden input on delete
            box.querySelector(".delete-btn").addEventListener("click", function() {
                box.remove();
                input.remove();
            });

            document.getElementById('faq-list').appendChild(box);

            // Clear inputs
            document.getElementById('faqQuestionInput').value = '';
            document.getElementById('faqAnswerInput').value = '';
        });

        document.getElementById('addItinerary').addEventListener('click', function() {
            let title = document.getElementById('iteneryTitleInput').value.trim();
            let data = document.getElementById('iteneryDataInput').value.trim();

            if (!title || !data) {
                alert('Please enter both Title and Data');
                return;
            }

            // Create single JSON object for this itinerary
            let itenaryObj = {
                title: title,
                description: data
            };

            // Convert to string to store in hidden input
            let itenaryJsonString = JSON.stringify(itenaryObj);

            // Create hidden input for form submission
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'itenery[]';
            input.value = itenaryJsonString;

            document.getElementById('itenery-list').appendChild(input);

            // Create badge/div to display added itinerary
            let box = document.createElement('div');
            box.className = "text-dark p-2 m-1 d-flex align-items-center justify-content-between rounded border";
            box.innerHTML = `
            <div>
                <strong>Title:</strong> ${title} <br>
                <strong>Description:</strong> ${data}
            </div>
            <button type="button" class="btn btn-sm btn-danger delete-btn">
                <i class="fa fa-trash"></i> Delete
            </button>
            `;

            // Remove badge and hidden input on delete
            box.querySelector(".delete-btn").addEventListener("click", function() {
                box.remove();
                input.remove();
            });

            document.getElementById('itenery-list').appendChild(box);

            // Clear inputs
            document.getElementById('iteneryTitleInput').value = '';
            document.getElementById('iteneryDataInput').value = '';
        });


        // // ---------- Itinerary ----------
        // document.getElementById('addItinerary').addEventListener('click', function() {
        //     let value = document.getElementById('itineraryInput').value.trim();
        //     if (!value) return;

        //     let itineraryObj = [{
        //         "day": value
        //     }];
        //     let input = document.createElement('input');
        //     input.type = "hidden";
        //     input.name = "itenery[]";
        //     input.value = JSON.stringify(itineraryObj);

        //     let box = document.createElement('div');
        //     box.className = "text-dark p-2 m-1 d-flex justify-content-between rounded";
        //     box.innerHTML = `<span>${value}</span><span style="cursor:pointer;font-weight:bold;">&times;</span>`;

        //     box.querySelector("span:last-child").addEventListener("click", function() {
        //         box.remove();
        //         input.remove();
        //     });

        //     document.getElementById('itinerary-list').appendChild(box);
        //     document.getElementById('itinerary-list').appendChild(input);

        //     document.getElementById('itineraryInput').value = '';
        // });
    </script>
@endsection
