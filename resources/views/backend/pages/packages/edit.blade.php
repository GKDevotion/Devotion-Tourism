@extends('backend.layouts.master')
<script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>
@section('title')
    Package Edit - Admin Panel
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropify/0.2.2/css/dropify.min.css" />
    <style>
        .form-check-label {
            text-transform: capitalize;
        }

        /* Fix Dropify image fit */
        .dropify-wrapper .dropify-preview .dropify-render img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
            /* OR contain */
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
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    {{-- <h4 class="page-title pull-left d-none">Configuration Edit - {{ $data->name }}</h4> --}}
                    <ul class="breadcrumbs pull-left m-2">
                        <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.package.index') }}">All Package</a></li>
                        <li><span>Edit Package</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <p class="float-end">
                    <button type="button" class="btn btn-success pr-4 pl-4" onclick="$('#submitForm').click();">
                        <i class="fa fa-save"></i> Update
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
                <h3 class="pb-3">Update Package</h3>
                <div class="card">
                    <div class="card-body">

                        <!-- @include('backend.layouts.partials.messages') -->

                        <form action="{{ route('admin.package.update', $dataArr->id) }}"
                            onsubmit="return onSubmitValidateForm();" method="POST" enctype="multipart/form-data">
                            @method('PUT')
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
                                                                        <option value="{{ $ar->id }}"
                                                                            {{ $dataArr->category_id == $ar->id ? 'selected' : '' }}>
                                                                            {{ $ar->title }}</option>
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
                                                                                class="d-none sub-category parent-category-{{ $ar->parent_id }}"
                                                                                {{ $dataArr->sub_category_id == $ar->id ? 'selected' : 'd-none' }}>
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
                                                    placeholder="{{ __('Title') }}" value="{{ $dataArr->title }}">
                                                @if ($errors->has('title'))
                                                    <div class="error">{{ $errors->first('title') }}</div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="image">Banner Pic</label>
                                                <input type="file" class="dropify" id="image" name="image"
                                                    data-default-file="{{ url('storage/app/public/' . $dataArr->image) }}">
                                                @if ($errors->has('image'))
                                                    <div class="error">{{ $errors->first('image') }}</div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="location">Location</label>
                                                <input type="text" class="form-control" id="location" name="location"
                                                    placeholder="{{ __('Location') }}" value="{{ $dataArr->location }}">
                                                @if ($errors->has('location'))
                                                    <div class="error">{{ $errors->first('location') }}</div>
                                                @endif
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="price">Price</label>
                                                        <input type="text" class="form-control" id="price"
                                                            name="price" placeholder="{{ __('Package Price') }}"
                                                            value="{{ $dataArr->price }}">
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
                                                            value="{{ $dataArr->adult_price }}">
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
                                                            value="{{ $dataArr->child_price }}">
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
                                                            value="{{ $dataArr->group_price }}">
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
                                                            value="{{ $dataArr->discount }}">
                                                        @if ($errors->has('discount'))
                                                            <div class="error">{{ $errors->first('discount') }}</div>
                                                        @endif
                                                    </div>
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
                                                    placeholder="{{ __('Short Description, Meta Description here') }}" rows="4">{{ $dataArr->short_description }}</textarea>
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
                                                            value="{{ \Carbon\Carbon::parse($dataArr->start_date)->format('Y-m-d') }}">

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
                                                            value="{{ \Carbon\Carbon::parse($dataArr->end_date)->format('Y-m-d') }}">

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
                                                            value="{{ $dataArr->duration }}">
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
                                                            value="{{ $dataArr->adult_size }}">
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
                                                            value="{{ $dataArr->tour_type }}">
                                                        @if ($errors->has('tour_type'))
                                                            <div class="error">{{ $errors->first('tour_type') }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="status">Status</label>
                                                        <select class="form-control" name="status" id="status">
                                                            <option value="1"
                                                                {{ $dataArr->status == 1 ? 'selected' : 'd-none' }}>Active
                                                            </option>
                                                            <option value="0"
                                                                {{ $dataArr->status == 0 ? 'selected' : 'd-none' }}>
                                                                De-Active
                                                            </option>
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
                                                    placeholder="Term Condition" rows="16">{{ $dataArr->term_condition }}</textarea>
                                                <div class="error text-error"></div>
                                            </div>

                                            <div class="form-group">
                                                <label for="title">Description</label>
                                                <textarea type="text" class="ckeditor form-control" id="description" name="description"
                                                    placeholder="{{ __('Package Description') }}" rows="16">{{ $dataArr->description }}</textarea>
                                                @if ($errors->has('description'))
                                                    <div class="error">{{ $errors->first('description') }}</div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="title">Keyword</label>
                                                <textarea type="text" class="form-control" id="keyword" name="keyword" placeholder="{{ __('Blog keyword') }}"
                                                    rows="5">{{ $dataArr->keyword }}</textarea>
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
                                                        data-default-file="{{ isset($cardImages[0]) ? asset('storage/app/public/package/card/' . $cardImages[0]->filename) : '' }}">
                                                </div>

                                                <div class="column_image_size_1 imageRow_1 image col-md-3" id="tr_lot_1">
                                                    <input type="file" name="lot_file_1" id="lot_file_1"
                                                        class="dropify" data-height="200" data-max-file-size="2M"
                                                        data-allowed-file-extensions="jpg jpeg png webp"
                                                        data-default-file="{{ isset($cardImages[1]) ? asset('storage/app/public/package/card/' . $cardImages[1]->filename) : '' }}">
                                                </div>


                                                <div class="column_image_size_2 imageRow_2 image col-md-3" id="tr_lot_2">
                                                    <input type="file" name="lot_file_2" id="lot_file_2"
                                                        class="dropify" data-height="200" data-max-file-size="2M"
                                                        data-allowed-file-extensions="jpg jpeg png webp"
                                                        data-default-file="{{ isset($cardImages[2]) ? asset('storage/app/public/package/card/' . $cardImages[2]->filename) : '' }}">
                                                </div>


                                                <div class="column_image_size_3 imageRow_3 image col-md-3" id="tr_lot_3">
                                                    <input type="file" name="lot_file_3" id="lot_file_3"
                                                        class="dropify" data-height="200" data-max-file-size="2M"
                                                        data-allowed-file-extensions="jpg jpeg png webp"
                                                        data-default-file="{{ isset($cardImages[3]) ? asset('storage/app/public/package/card/' . $cardImages[3]->filename) : '' }}">
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
            <!-- data table end -->
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropify/0.2.2/js/dropify.min.js"></script>
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
            if (!question || !answer) return;

            // create individual JSON for this FAQ
            let faqObj = [{
                    "question": question
                },
                {
                    "answer": answer
                }
            ];
            let faqJsonString = JSON.stringify(faqObj);

            // create hidden input for this FAQ
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'faq[]';
            input.value = faqJsonString;

            document.getElementById('faq-list').appendChild(input);

            // create badge
            let box = document.createElement('div');
            box.className =
                "text-dark p-2 m-1 d-flex align-items-center justify-content-between rounded ";
            box.innerHTML = `
            <div>
            <strong>Question:</strong> ${question} <br>
            <strong>Answer:</strong> ${answer}
             </div>
             <span class="text-dark" style="cursor:pointer;font-weight:bold;">&times;</span>
            `;

            // remove badge and hidden input
            box.querySelector("span").addEventListener("click", function() {
                box.remove();
                input.remove();
            });

            document.getElementById('faq-list').appendChild(box);

            // clear inputs
            document.getElementById('faqQuestionInput').value = '';
            document.getElementById('faqAnswerInput').value = '';
        });

        // ---------- Itinerary ----------
        document.getElementById('addItinerary').addEventListener('click', function() {
            let value = document.getElementById('itineraryInput').value.trim();
            if (!value) return;

            let itineraryObj = [{
                "day": value
            }];
            let input = document.createElement('input');
            input.type = "hidden";
            input.name = "itenery[]";
            input.value = JSON.stringify(itineraryObj);

            let box = document.createElement('div');
            box.className = "text-dark p-2 m-1 d-flex justify-content-between rounded";
            box.innerHTML = `<span>${value}</span><span style="cursor:pointer;font-weight:bold;">&times;</span>`;

            box.querySelector("span:last-child").addEventListener("click", function() {
                box.remove();
                input.remove();
            });

            document.getElementById('itinerary-list').appendChild(box);
            document.getElementById('itinerary-list').appendChild(input);

            document.getElementById('itineraryInput').value = '';
        });
    </script>
@endsection
