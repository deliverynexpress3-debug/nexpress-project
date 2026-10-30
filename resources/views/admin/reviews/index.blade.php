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
            <h1 class="page-title-sm">{{ __('label.reviews') }}</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.reviews') }}
                        </li>
                    </ol>
                </div>
             

            </div>

            <!-- Search -->
            <div class="page-search mb-3 mt-3">
                <div class="input-group" title="Search">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                    </div>
                    <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                </div>
                <div class="sorting mr-2 w-75">
                    <label>{{__('label.sort_by')}}</label>
                    <select class="form-control" name="input_type" id="input_type">
                        <option value="all">{{__('label.all')}}</option>
                        <option value="1">{{__('label.item')}}</option>
                        <option value="2">{{__('label.seller')}}</option>
                    </select>
                </div>
                <div class="sorting mr-2 w-75">
                    <select class="form-control" name="customer_id" id="input_customer_id">
                        <option value="all">{{__('label.all_customer')}}</option>
                        @foreach ($customer as $key => $value)
                            <option value="{{$value->id}}">
                                {{ $value->user_name }} → {{ $value->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sorting mr-2 w-75">
                    <select class="form-control" name="seller_id" id="input_seller_id">
                        <option value="all">{{__('label.all_seller')}}</option>
                        @foreach ($seller as $key => $value)
                            <option value="{{$value->id}}">
                                {{ $value->user_name }} → {{ $value->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sorting w-75">
                    <select class="form-control" name="item_id" id="input_item_id">
                        <option value="all">{{__('label.all_item')}}</option>
                        @foreach ($item as $key => $value)
                            <option value="{{$value->id}}">
                                {{ $value->name }}
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
                            <th>{{__('label.type')}}</th>
                            <th>{{__('label.content')}}</th>
                            <th>{{__('label.reviews')}}</th>
                            <th>{{__('label.rating_out_of_5')}}</th>
                            <th>{{__('label.status')}}</th>
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
    sidebar_down(700);

    $('#input_customer_id, #input_seller_id ,#input_item_id').select2();

    $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.reviews.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                        d.input_customer_id = $('#input_customer_id').val();
                        d.input_seller_id = $('#input_seller_id').val();
                        d.input_item_id = $('#input_item_id').val();
                        d.input_type = $('#input_type').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'customer',
                        name: 'customer',
                        render: function(data) {
                            return data ? data.user_name : "-";
                        }
                    },
                    {
                        data: 'type',
                        name: 'type',
                        render: function(data) {
                            if(data == 1){
                                return "{{__('label.item')}}";
                            } else if(data == 2) {
                                return "{{__('label.seller')}}";
                            } else {
                                return "-";
                            }
                            
                        }
                    },
                    {
                        data: null,
                        name: 'name',
                        render: function(data, type, row) {

                            function limitText(text, limit) {
                                if (!text) return "-";
                                return text.length > limit 
                                    ? text.substring(0, limit) + "..." 
                                    : text;
                            }

                            if (row.type == 1) {
                                return row.item ? limitText(row.item.name, 80) : "-";
                            } else {
                                return row.seller ? row.seller.user_name : "-";
                            }

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
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });

            $('#input_customer_id, #input_seller_id, #input_item_id, #input_type').change(function() {
                table.draw();
            });
            $('#input_search').keyup(function() {
                table.draw();
            });
        });

        function change_status(id, status) {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                $("#dvloader").show();
                var url = `{{ route('admin.reviews.show', '') }}/${id}`;

                $.ajax({
                    type: "GET",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
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
