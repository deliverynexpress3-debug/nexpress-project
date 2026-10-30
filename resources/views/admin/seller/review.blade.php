@extends('admin.layout.page-app')
@section('page_title', __('label.reviews'))
@section('tab_title', __('label.reviews'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')
        <!-- Select2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.reviews') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        <li class="breadcrumb-item"><a href="{{ route('admin.seller.profile',$seller_id) }}">{{ __('label.seller_profile') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.reviews') }}
                        </li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.seller.profile',$seller_id) }}" class="btn btn-default mw-120"
                        style="margin-top:-14px">{{ __('label.back') }}</a>
                </div>
            </div>

            <div class="page-search mb-3 mt-3">
                <div class="input-group" title="Search">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                    </div>
                    <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                </div>
                <div class="sorting mr-2 w-50">
                    <label>{{__('label.sort_by')}}</label>
                    <select class="form-control" name="customer_id" id="input_customer_id">
                        <option value="all">{{__('label.all_customer')}}</option>
                        @foreach ($customer as $key => $value)
                            <option value="{{$value->id}}">
                                {{ $value->user_name }} → {{ $value->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
               
            </div>

            <div class="table-responsive">
                <table class="table table-striped text-center table-bordered" id="datatable">
                    <thead>
                        <tr style="background: #F9FAFF;">
                            <th>{{__('label.#')}}</th>
                            <th>{{__('label.customer')}}</th>
                            <th>{{__('label.reviews')}}</th>
                            <th>{{__('label.rating')}}</th>
                            <th>{{__('label.action')}}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@section('pagescript')
<!-- Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $("#input_customer_id").select2();

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.seller.review',$seller_id) }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                        d.input_customer_id = $('#input_customer_id').val();
                       
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'customer',
                        name: 'customer',
                        render: function(data, type, row) {
                            return `<div style="text-align: center;">${row.customer.full_name || ''} <br><span style="font-size: 14px; font-weight: 600;">${row.customer.user_name || ''}</span>`;
                        }
                    },
                  
                   
                    {
						data: 'review',
						name: 'review',
						render: function(data) {
                            return data ? data : "-" ;
                        }
					},
                    {
                        data: 'rating',
                        name: 'rating',
                        render: function(data) {
                            return data ? '<b class="h5">'+data+'</b>' +'<i class="fa-solid fa-star ml-2 text-warning fa-lg"></i>' : "-";
                        }
                    },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                ],
            });

            $('#input_customer_id').change(function() {
                table.draw();
            });
            $('#input_search').keyup(function() {
                table.draw();
            });
        });
        
        function review_change_status(id) {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var url = "{{route('admin.seller.review_change_status', '')}}" + "/" + id;
                $.ajax({
                    type: "GET",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: id,
                    success: function(resp) {
                        $("#dvloader").hide();

                        if (resp.status == 200) {
                            if (resp.status_code == 1) {
                                $('#' + id).text('{{__("label.show")}}').removeClass('hide-btn').addClass('show-btn');
                            } else {
                                $('#' + id).text('{{__("label.hide")}}').removeClass('show-btn').addClass('hide-btn');
                            }
                            toastr.success(resp.success);
                        } else {
                            toastr.error(resp.errors);
                        }
                        
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                showError();
            }
        };
       
    </script>
@endsection
