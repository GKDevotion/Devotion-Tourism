
@extends('backend.layouts.master')

@section('title')
Country Page - Admin Panel
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
    <style>
        td{
            text-align: left;
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
                {{-- <h4 class="page-title pull-left d-none">Country</h4> --}}
                <ul class="breadcrumbs pull-left m-2">
                    <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li><span>All Country</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3 text-end">
            @if (Auth::guard('admin')->user()->can('country.edit'))
                <a class="btn btn-success text-white" href="{{ route('admin.country.create') }}">
                    <i class="fa fa-plus"></i> Country
                </a>
            @endif
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
            <h3 class="pb-3">Country History</h3>
            <div class="card">
                <div class="card-body">

                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table id="countries_index" class="text-center">
                            <thead id="countries" class="bg-light text-capitalize">
                                <tr>
                                    <th width="1%">Sr</th>
                                    <th width="20%">Name</th>
                                    <th width="5%">Continent</th>
                                    <th width="2%">ISO (3, 2 )</th>
                                    <th width="2%">Number Code</th>
                                    <th width="3%">Capital</th>
                                    <th width="5%">Lat, Long</th>
                                    <th width="2%">Status</th>
                                    <th width="3%">Updated At</th>
                                    <th width="3%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach ($dataArr as $data)
                                <tr id="row_{{$data->id}}" class="countries_row">
                                        <td>{{ $loop->index+1 }}</td>
                                        <td class="text-left">{{$data->name}} ({{ $data->currency}})</td>
                                        <td class="text-left">{{$data->continent->name}}</td>
                                        <td class="">{{$data->iso3}}, {{$data->iso2}}</td>
                                        <td class="">{{$data->numeric_code}}</td>
                                        <td class="text-left">{{$data->capital}} ({{$data->currency_symbol}})</td>
                                        <td class="text-left">{{round( $data->latitude, 3 )}}, {{round( $data->longitude, 3 )}}</td>
                                        <td>
                                            @if( true )
                                                <i class="fa fa-{{ ( $data->status == 0 ) ? 'times' : 'check' }} update-status" data-status="{{$data->status}}" data-id="{{$data->id}}" aria-hidden="true" data-table="countries"></i>
                                            @else
                                                <select class="form-control update-status badge {{ ( $data->status == 0 ) ? 'bg-warning' : 'bg-success' }} text-white" name="status" data-id="{{$data->id}}" data-table="contries">
                                                    <option value="1" {{($data->status == 1) ? 'selected' : ''}}>Active</option>
                                                    <option value="0" {{($data->status == 0) ? 'selected' : ''}}>De-Active</option>
                                                </select>
                                            @endif
                                        </td>
                                        <td class="text-left">{{formatDate( "Y-m-d H:i", $data->updated_at )}}</td>
                                        <td>

                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="action_menu_{{$data->id}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                &#x22EE;
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="action_menu_{{$data->id}}">

                                                @if (Auth::guard('admin')->user()->can('country.edit'))
                                                    <a class="btn btn-edit text-white dropdown-item" href="{{ route('admin.country.edit', $data->id) }}">
                                                        <i class="fa fa-pencil"></i> Edit
                                                    </a>
                                                @endif

                                                @if (Auth::guard('admin')->user()->can('country.delete'))
                                                    <button class="btn btn-edit text-white delete-record dropdown-item" data-id="{{$data->id}}" data-title="{{ $data->name }}" data-segment="countries">
                                                        <i class="fa fa-trash fa-sm" aria-hidden="true"></i> Delete
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>
@endsection


@section('scripts')

    @include('backend.layouts.partials.data-table')

     <script>
         /*================================
        datatable active
        ==================================*/
        if ($('#countries_index').length) {
            $('#countries_index').DataTable({
                responsive: true,
                dom: '<"row"<"col-md-4"B><"col-md-4 text-left"l><"col-md-4 text-right"f>>' +
                    'rt' +
                    '<"row"<"col-md-6"i><"col-md-6"p>>', // Custom structure with multiple parameters
                buttons: ['excel', 'pdf'],
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                pageLength: 10,
            });
        }

     </script>
@endsection
