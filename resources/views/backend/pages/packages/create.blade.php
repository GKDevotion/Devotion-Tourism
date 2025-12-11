@include('admin.elements.header')
<script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h1>Create Package</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.package') }}">Package</a></li>
                        <li class="breadcrumb-item active">Create Package</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form method="POST" class="dropzone needsclick" action="{{ route('admin.package.store') }}"
                enctype="multipart/form-data" autocomplete="off">
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
                                                            <option value="{{ $ar->id }}">{{ $ar->title }}
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
                                                <div class="error">{{ $errors->first('sub_category_id') }}</div>
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
                                            <label for="price">Price</label>
                                            <input type="text" class="form-control" id="price" name="price"
                                                placeholder="{{ __('Amount') }}" value="{{ old('price') }}">
                                            @if ($errors->has('price'))
                                                <div class="error">{{ $errors->first('price') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="discount">Discount (in %)</label>
                                            <input type="text" class="form-control" id="discount" name="discount"
                                                placeholder="{{ __('Discount (in %)') }}"
                                                value="{{ old('discount') }}">
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
                                    <label class="PackageTags-txt">Add Package Tag </label>
                                    <div class="row">
                                        <div class="col-md-10 toggle-btn">
                                            <div class="position-relative">
                                                <input type="text" class="form-control " placeholder="Tag"
                                                    id="PackageTags-txt">
                                                <ul class="dropdown-menu txt_title_tag p-2 w-100 auto-search-drp"
                                                    role="menu" aria-labelledby="dropdownMenu"
                                                    id="DropdownPackageTags"></ul>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="javascript:void(0)" class="btn btn-outline-primary"
                                                id="addPackageTag"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    <div class="pb-0 p-2">
                                        <div id="package-tag-store"></div>
                                    </div>
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

                                <div class="form-group">
                                    <label for="title">Description</label>
                                    <textarea type="text" class="ckeditor form-control" id="description" name="description"
                                        placeholder="{{ __('Blog Description') }}" rows="16"> {{ old('description') }}</textarea>
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
                                        <div align="center" class="image w-100">
                                            <img src="{{ url('public/img/no-image.png') }}" width="100%"
                                                height="200" id="imageRemoveBtnLotImg_0"><br>
                                            <input type="file" name="lot_file_0" id="lot_file_0"
                                                onchange="readURLCommon(this, 'imageRemoveBtnLotImg_0', 'lot_hidden_0');"
                                                style="display: none;">
                                            <input type="hidden" value="" name="lot_hidden_0"
                                                id="lot_hidden_0">
                                            <div align="center">
                                                <small>
                                                    <a
                                                        onclick="$('#lot_file_0').trigger('click');">Browse</a>&nbsp;|&nbsp;
                                                    <a style="clear:both;"
                                                        onclick="clear_image('imageRemoveBtnLotImg_0');">Clear</a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column_image_size_1 imageRow_1 image col-md-3" id="tr_lot_1">
                                        <div align="center" class="image w-100">
                                            <img src="{{ url('public/img/no-image.png') }}" width="100%"
                                                height="200" id="imageRemoveBtnLotImg_1">
                                            <br>

                                            <input type="file" name="lot_file_1" id="lot_file_1"
                                                onchange="readURLCommon(this, 'imageRemoveBtnLotImg_1', 'lot_hidden_1');"
                                                style="display: none;">
                                            <input type="hidden" value="" name="lot_hidden_1"
                                                id="lot_hidden_1">
                                            <div align="center">
                                                <small>
                                                    <a
                                                        onclick="$('#lot_file_1').trigger('click');">Browse</a>&nbsp;|&nbsp;<a
                                                        style="clear:both;"
                                                        onclick="clear_image('imageRemoveBtnLotImg_1');">Clear</a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column_image_size_2 imageRow_2 image col-md-3" id="tr_lot_2">
                                        <div align="center" class="image w-100">
                                            <img src="{{ url('public/img/no-image.png') }}" width="100%"
                                                height="200" id="imageRemoveBtnLotImg_2"><br>
                                            <input type="file" name="lot_file_2" id="lot_file_2"
                                                onchange="readURLCommon(this, 'imageRemoveBtnLotImg_2', 'lot_hidden_2');"
                                                style="display: none;">
                                            <input type="hidden" value="" name="lot_hidden_2"
                                                id="lot_hidden_2">
                                            <div align="center">
                                                <small>
                                                    <a
                                                        onclick="$('#lot_file_2').trigger('click');">Browse</a>&nbsp;|&nbsp;<a
                                                        style="clear:both;"
                                                        onclick="clear_image('imageRemoveBtnLotImg_2');">Clear</a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column_image_size_3 imageRow_3 image col-md-3" id="tr_lot_3">
                                        <div align="center" class="image w-100">
                                            <img src="{{ url('public/img/no-image.png') }}" width="100%"
                                                height="200" id="imageRemoveBtnLotImg_3"><br>
                                            <input type="file" name="lot_file_3" id="lot_file_3"
                                                onchange="readURLCommon(this, 'imageRemoveBtnLotImg_3', 'lot_hidden_3');"
                                                style="display: none;">
                                            <input type="hidden" value="" name="lot_hidden_3"
                                                id="lot_hidden_3">
                                            <div align="center">
                                                <small>
                                                    <a
                                                        onclick="$('#lot_file_3').trigger('click');">Browse</a>&nbsp;|&nbsp;<a
                                                        style="clear:both;"
                                                        onclick="clear_image('imageRemoveBtnLotImg_3');">Clear</a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>

                    <div class="col-md-12 text-center mb-4">
                        <a href="{{ route('admin.package') }}" class="btn btn-danger"><i class="fa fa-arrow-left"
                                aria-hidden="true"></i> Back</a>
                        <button type="submit" class="btn btn-success"><i class="far fa-save"
                                aria-hidden="true"></i> Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<script>
    $("#category_id").on("change", function() {
        var category_id = $(this).val();
        $("#sub_category_id").find('.sub-category').addClass("d-none");
        $("#sub_category_id").find(".parent-category-" + category_id).removeClass("d-none");
    });
</script>
@include('admin.elements.footer')
