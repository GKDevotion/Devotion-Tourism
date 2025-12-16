@extends('backend.layouts.master')

@section('title')
    Package - Admin Panel
@endsection

@section('styles')
    <!-- Start datatable css -->
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css"> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
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
                    {{-- <h4 class="page-title pull-left d-none">Configuration</h4> --}}
                    <ul class="breadcrumbs pull-left m-2">
                        <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li><span>All Package</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-2 text-end">
                <a class="btn btn-success text-white" href="{{ route('admin.package.create') }}">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add Package
                </a>
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
            <div class="col-md-12">
                <div class="card">

                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table id="packages" class="table table-bordered table-striped display responsive nowrap"
                            data-order='[[ 0, "desc" ]]'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="">Tour Id</th>
                                    <th class="" width="10%">Image</th>
                                    <th class="">Package</th>
                                    <th class="">Category</th>
                                    <th class="">Sub Category</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Views</th>
                                    <th class="text-center">Start Date</th>
                                    <th class="text-center">End Date</th>
                                    <th class="">Updated At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataArr as $ar)
                                    <tr id="row_{{ $ar->id }}" class="role_row">
                                        <td class="">{{ $ar->id }}</td>
                                        <td class="">{{ $ar->tour_id }}</td>
                                        <td>
                                            <img src="{{ asset('storage/app/public/' . $ar->image) }}"
                                                alt="{{ $ar->title }}" style="width: 100%;height: 100px;">
                                        </td>

                                        <td>
                                            <a href="{{ url(optional($ar->category)->slug . '/' . optional($ar->sub_category)->slug . '/' . $ar->slug . '?advt=0') }}"
                                                target="_blank" title="{{ $ar->title }}">
                                                {{ $ar->title }}
                                            </a>
                                        </td>
                                        <td class="">{{ $ar->category?->title ?? '—' }}</td>

                                        <td class="">{{ $ar->sub_category->title ?? '—' }}</td>
                                        <td>
                                            @if (true)
                                                <i class="fa fa-{{ $ar->status == 0 ? 'times' : 'check' }} update-status"
                                                    data-status="{{ $ar->status }}" data-id="{{ $ar->id }}"
                                                    aria-hidden="true" data-table="packages"></i>
                                            @else
                                                <select
                                                    class="form-control update-status badge {{ $data->status == 0 ? 'bg-warning' : 'bg-success' }} text-white"
                                                    name="status" data-id="{{ $ar->id }}" data-table="packages">
                                                    <option value="1" {{ $ar->status == 1 ? 'selected' : '' }}>
                                                        Active</option>
                                                    <option value="0" {{ $ar->status == 0 ? 'selected' : '' }}>
                                                        De-Active</option>
                                                </select>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ format_number_in_k_notation($ar->view) }}</td>
                                        <td class="">{{ formatDate('d-m-Y h:i', $ar->start_date) }}</td>
                                        <td class="">{{ formatDate('d-m-Y h:i', $ar->end_date) }}</td>
                                        <td class="">{{ formatDate('d-m-Y h:i', $ar->updated_at) }}</td>
                                        <td>
                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                                id="action_menu_{{ $ar->id }}" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                &#x22EE;
                                            </button>

                                            <div class="dropdown-menu" aria-labelledby="action_menu_{{ $ar->id }}">
                                                <a class="btn btn-edit text-white dropdown-item"
                                                    href="{{ route('admin.package.edit', $ar->id) }}">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>

                                                <!-- Delete form -->
                                                <form action="{{ route('admin.package.destroy', $ar->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this package?');"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn dropdown-item">
                                                        <i class="fa fa-trash fa-sm" aria-hidden="true"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td colspan="8">There is no role available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
    </div>
@endsection


@section('scripts')
    <!-- Start datatable js -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

    <script>
        /*================================
                                datatable active
                                ==================================*/
        if ($('#dataTable').length) {
            $('#dataTable').DataTable({
                responsive: true,

                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    }, // Sr
                    {
                        responsivePriority: 2,
                        targets: 1
                    }, // Unique ID
                    {
                        responsivePriority: 3,
                        targets: 2
                    }, // Invoice
                    {
                        responsivePriority: 4,
                        targets: 3
                    }, // Amount
                    {
                        responsivePriority: 5,
                        targets: 4
                    }, // Serial No
                    {
                        responsivePriority: 6,
                        targets: 5
                    }, // Purchase Order

                    // All others move to "+" expandable view
                    {
                        responsivePriority: 10001,
                        targets: [6, 7, 8, 9, 10, 11]
                    }
                ]
            });
        }
    </script>
@endsection
